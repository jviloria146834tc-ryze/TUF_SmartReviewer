<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Services\OCRService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Store a newly created resource in storage and perform OCR synchronously.
     */
    public function store(Request $request, OCRService $ocrService): JsonResponse
    {
        // Increase execution time for large files/AI processing
        set_time_limit(180);

        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'file' => 'required_without:content|nullable|file|mimes:pdf,png,jpg,jpeg|max:25600',
                'content' => 'required_without:file|nullable|string',
            ]);

            if ($validator->fails()) {
                $errorMessage = collect($validator->errors()->all())->first();
                return response()->json(['error' => $errorMessage], 422);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $path = $file->store('uploads', 'public');
                $fullPath = storage_path('app/public/'.$path);
            } else {
                $originalName = 'Pasted Content - '.now()->format('Y-m-d H:i');
                $path = 'uploads/'.uniqid().'.txt';
                Storage::disk('public')->put($path, $request->input('content'));
                $fullPath = storage_path('app/public/'.$path);
            }

            $material = Material::create([
                'user_id' => auth()->id() ?? 1,
                'title' => $originalName,
                'original_path' => $path,
                'status' => 'processing',
            ]);

            // Perform Smart Tutor processing synchronously
            $data = $ocrService->processMaterial($fullPath);

            if (isset($data['error'])) {
                $material->update(['status' => 'failed']);

                return response()->json(['error' => $data['error']], 422);
            }

            // Update material with raw summary as content fallback
            $material->update([
                'raw_content' => $data['summary'],
                'status' => 'completed',
            ]);

            // Create the Quiz
            $quiz = $material->quizzes()->create([
                'title' => 'Quiz for '.$originalName,
                'summary' => $data['summary'],
                'concepts' => $data['concepts'],
            ]);

            // Create the Questions
            foreach ($data['questions'] as $q) {
                $quiz->questions()->create([
                    'question_text' => $q['question_text'],
                    'options' => $q['options'],
                    'correct_answer' => $q['correct_answer'],
                    'explanation' => $q['explanation'],
                ]);
            }

            return response()->json([
                'success' => true,
                'redirect' => route('reviewer', $material->id),
                'material_id' => $material->id,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material): \Illuminate\Http\RedirectResponse
    {
        // Ensure user owns material
        if ($material->user_id !== auth()->id()) {
            abort(403);
        }

        // Delete associated file
        if ($material->original_path && Storage::disk('public')->exists($material->original_path)) {
            Storage::disk('public')->delete($material->original_path);
        }

        $material->delete();

        return back();
    }
}
