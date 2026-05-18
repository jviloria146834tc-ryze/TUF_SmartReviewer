<?php

namespace App\Jobs;

use App\Models\Material;
use App\Models\Quiz;
use App\Services\OCRService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateQuizJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;

    public function __construct(
        public int $materialId,
        public int $numQuestions,
        public string $quizType,
        public array $fullPaths,
        public string $fileHash,
        public int $userId
    ) {}

    public function handle(OCRService $ocrService): void
    {
        $material = Material::find($this->materialId);
        if (!$material) return;

        try {
            $data = $ocrService->generateQuiz($this->fullPaths, $this->numQuestions, $this->quizType, $this->fileHash, $this->userId);

            if (isset($data['error'])) {
                // You might want to log this or notify the user
                return;
            }

            $quiz = $material->quizzes()->create([
                'title' => 'Quiz for '.$material->title,
                'summary' => $material->summary,
                'concepts' => $material->concepts,
            ]);

            foreach ($data['questions'] as $q) {
                $finalType = ($this->quizType !== 'mixed') ? $this->quizType : ($q['type'] ?? 'multiple_choice');

                $quiz->questions()->create([
                    'type' => $finalType,
                    'question_text' => $q['question_text'],
                    'options' => $q['options'] ?? [],
                    'correct_answer' => $q['correct_answer'],
                    'explanation' => $q['explanation'] ?? '',
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
