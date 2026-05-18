<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateFlashcardsJob;
use App\Models\Flashcard;
use App\Models\Material;
use App\Services\OCRService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FlashcardController extends Controller
{
    /**
     * Store a newly generated set of flashcards for a material.
     */
    public function store(Request $request, Material $material): JsonResponse
    {
        Gate::authorize('update', $material);

        $request->validate([
            'num_cards' => 'nullable|integer|min:1|max:50',
        ]);

        $numCards = $request->input('num_cards', 10);

        try {
            $fullPaths = [];
            $files = Storage::disk('public')->files($material->original_path);

            if (count($files) > 0) {
                foreach ($files as $file) {
                    $fullPaths[] = Storage::disk('public')->path($file);
                }
            } else {
                $fullPaths[] = Storage::disk('public')->path($material->original_path);
            }

            // Dispatch background job for flashcard generation
            GenerateFlashcardsJob::dispatch(
                $material->id,
                $numCards,
                $fullPaths,
                $material->file_hash,
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Flashcard generation started in the background.',
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Toggle the mastery status of a flashcard.
     */
    public function toggleMastery(Flashcard $flashcard): JsonResponse
    {
        Gate::authorize('update', $flashcard);

        $flashcard->update([
            'is_mastered' => ! $flashcard->is_mastered,
        ]);

        return response()->json([
            'success' => true,
            'is_mastered' => $flashcard->is_mastered,
        ]);
    }

    /**
     * Reset the mastery status of all flashcards for a material.
     */
    public function resetMastery(Material $material): JsonResponse
    {
        Gate::authorize('update', $material);

        $material->flashcards()->update(['is_mastered' => false]);

        return response()->json([
            'success' => true,
        ]);
    }
}
