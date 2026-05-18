<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateQuizRequest;
use App\Http\Requests\StoreMaterialRequest;
use App\Jobs\GenerateQuizJob;
use App\Jobs\ProcessMaterialJob;
use App\Models\Material;
use App\Services\OCRService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    /**
     * Store a newly created material and generate its study guide summary.
     */
    public function store(StoreMaterialRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            // Generate Hash
            $fileHash = '';
            if ($request->hasFile('files')) {
                $hashes = [];
                foreach ($request->file('files') as $file) {
                    $hashes[] = md5_file($file->getRealPath());
                }
                sort($hashes);
                $fileHash = md5(implode('', $hashes));
            } else {
                $fileHash = md5($request->input('content') ?? '');
            }

            $userId = auth()->id();

            // Database Check - check if user already has this exact material
            $existingMaterialForUser = Material::where('file_hash', $fileHash)
                ->where('user_id', $userId)
                ->first();

            if ($existingMaterialForUser) {
                return response()->json([
                    'success' => true,
                    'material_id' => $existingMaterialForUser->id,
                ]);
            }

            // Global Deduplication Check
            $existingGlobalMaterial = Material::where('file_hash', $fileHash)->first();

            $fullPaths = [];
            $firstFilePath = null;
            $originalName = '';

            if ($existingGlobalMaterial) {
                $dirPath = $existingGlobalMaterial->original_path;
                // Reuse existing files
                $files = Storage::disk('public')->files($dirPath);
                if (count($files) > 0) {
                    $firstFilePath = $files[0];
                    foreach ($files as $file) {
                        $fullPaths[] = Storage::disk('public')->path($file);
                    }
                } else {
                    $firstFilePath = $dirPath;
                    $fullPaths[] = Storage::disk('public')->path($dirPath);
                }
                $originalName = $request->hasFile('files') ? (count($request->file('files')) > 1 ? count($request->file('files')).' files' : $request->file('files')[0]->getClientOriginalName()) : 'Pasted Content - '.now()->format('Y-m-d H:i');
            } else {
                $dirPath = 'uploads/'.uniqid();

                if ($request->hasFile('files')) {
                    $originalName = count($request->file('files')) > 1 ? count($request->file('files')).' files' : $request->file('files')[0]->getClientOriginalName();
                    foreach ($request->file('files') as $file) {
                        $path = $file->store($dirPath, 'public');
                        if (! $firstFilePath) {
                            $firstFilePath = $path;
                        }
                        $fullPaths[] = Storage::disk('public')->path($path);
                    }
                } else {
                    $originalName = 'Pasted Content - '.now()->format('Y-m-d H:i');
                    $path = $dirPath.'/content.txt';
                    Storage::disk('public')->put($path, $request->input('content'));
                    $firstFilePath = $path;
                    $fullPaths[] = Storage::disk('public')->path($path);
                }
            }

            $material = Material::create([
                'user_id' => $userId,
                'title' => $originalName,
                'original_path' => $dirPath,
                'file_path' => $firstFilePath,
                'status' => 'processing',
                'file_hash' => $fileHash,
            ]);

            // Dispatch background job for heavy OCR/AI processing
            ProcessMaterialJob::dispatch(
                $material->id,
                $fullPaths,
                $fileHash,
                $userId,
                $originalName
            );

            session(['last_viewed_material_id' => $material->id]);

            return response()->json([
                'success' => true,
                'material_id' => $material->id,
                'message' => 'Processing started in the background.',
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate a quiz for an existing material.
     */
    public function generateQuiz(GenerateQuizRequest $request, Material $material): JsonResponse
    {
        Gate::authorize('view', $material);

        try {
            $numQuestions = $request->input('num_questions', 5);
            $quizType = $request->input('quiz_type', 'mixed');

            $fullPaths = [];
            $files = Storage::disk('public')->files($material->original_path);

            if (count($files) > 0) {
                foreach ($files as $file) {
                    $fullPaths[] = Storage::disk('public')->path($file);
                }
            } else {
                $fullPaths[] = Storage::disk('public')->path($material->original_path);
            }

            $userId = auth()->id() ?? 1;

            // Dispatch background job for quiz generation
            GenerateQuizJob::dispatch(
                $material->id,
                $numQuestions,
                $quizType,
                $fullPaths,
                $material->file_hash,
                $userId
            );

            return response()->json([
                'success' => true,
                'message' => 'Quiz generation started in the background.',
                'redirect' => route('quizzes.index'),
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Regenerate a quiz (legacy support / redirect to generateQuiz)
     */
    public function regenerateQuiz(GenerateQuizRequest $request, Material $material): JsonResponse
    {
        return $this->generateQuiz($request, $material);
    }

    /**
     * Re-process a material, clearing its associated cache.
     */
    public function reprocess(Material $material): JsonResponse
    {
        Gate::authorize('update', $material);

        $userId = auth()->id();

        try {
            // Clear cache for this material using its hash
            if ($material->file_hash) {
                Cache::forget("summary_v1_{$userId}_{$material->file_hash}");
                foreach ([5, 10, 15, 20, 30, 40, 50, 60] as $count) {
                    foreach (['mixed', 'multiple_choice', 'true_false', 'fill_in_the_blank'] as $type) {
                        Cache::forget("quiz_v1_{$userId}_{$material->file_hash}_{$count}_{$type}");
                    }
                    Cache::forget("quiz_v1_{$userId}_{$material->file_hash}_{$count}");
                }
            }

            $fullPaths = [];
            $files = Storage::disk('public')->files($material->original_path);

            foreach ($files as $file) {
                $fullPaths[] = Storage::disk('public')->path($file);
            }

            if (empty($fullPaths)) {
                $fullPaths[] = Storage::disk('public')->path($material->original_path);
            }

            $material->update(['status' => 'processing']);

            // Dispatch background job for re-processing
            ProcessMaterialJob::dispatch(
                $material->id,
                $fullPaths,
                $material->file_hash,
                $userId,
                $material->title
            );

            return response()->json([
                'success' => true,
                'message' => 'Reprocessing started in the background.',
                'material_id' => $material->id,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material): RedirectResponse
    {
        Gate::authorize('delete', $material);

        $userId = auth()->id();

        // Clean up caches related to this material
        if ($material->file_hash) {
            Cache::forget("summary_v1_{$userId}_{$material->file_hash}");
            Cache::forget("flashcards_v1_{$userId}_{$material->file_hash}");
            foreach ([5, 10, 15, 20, 30, 40, 50, 60] as $count) {
                foreach (['mixed', 'multiple_choice', 'true_false', 'fill_in_the_blank'] as $type) {
                    Cache::forget("quiz_v1_{$userId}_{$material->file_hash}_{$count}_{$type}");
                }
                Cache::forget("quiz_v1_{$userId}_{$material->file_hash}_{$count}");
            }
        }

        // Delete associated file if no other material is using it
        $isUsedElsewhere = Material::where('file_hash', $material->file_hash)
            ->where('id', '!=', $material->id)
            ->exists();

        if (! $isUsedElsewhere && $material->original_path && Storage::disk('public')->exists($material->original_path)) {
            Storage::disk('public')->deleteDirectory($material->original_path);
            Storage::disk('public')->delete($material->original_path);
        }

        if (session('last_viewed_material_id') == $material->id) {
            session()->forget('last_viewed_material_id');
        }

        $material->delete();

        return back();
    }
}
