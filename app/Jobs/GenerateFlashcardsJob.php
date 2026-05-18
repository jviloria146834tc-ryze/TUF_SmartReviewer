<?php

namespace App\Jobs;

use App\Models\Material;
use App\Services\OCRService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateFlashcardsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;

    public function __construct(
        public int $materialId,
        public int $numCards,
        public array $fullPaths,
        public string $fileHash,
        public int $userId
    ) {}

    public function handle(OCRService $ocrService): void
    {
        $material = Material::find($this->materialId);
        if (!$material) return;

        try {
            $data = $ocrService->generateFlashcards($this->fullPaths, $this->numCards, $this->fileHash, $this->userId);

            if (isset($data['error'])) {
                return;
            }

            // Clear existing flashcards before saving new ones to avoid duplication
            $material->flashcards()->delete();

            // Save the Flashcards
            foreach ($data['flashcards'] as $card) {
                $material->flashcards()->create([
                    'front' => $card['front'],
                    'back' => $card['back'],
                    'is_mastered' => false,
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
