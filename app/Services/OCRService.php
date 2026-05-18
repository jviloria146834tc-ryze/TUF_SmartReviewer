<?php

namespace App\Services;

use Gemini\Data\Blob;
use Gemini\Data\GenerationConfig;
use Gemini\Enums\MimeType;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class OCRService
{
    /**
     * Execute a Gemini API call with rate limit handling and retries.
     */
    protected function callGemini(callable $callback): mixed
    {
        return retry(3, function () use ($callback) {
            try {
                return $callback();
            } catch (\Exception $e) {
                // Check for 429 (Rate Limit) or 503 (Overloaded) which are common with Flash Lite
                $message = $e->getMessage();
                if (str_contains($message, '429') ||
                    str_contains($message, 'Too Many Requests') ||
                    str_contains($message, '503') ||
                    str_contains($message, 'overloaded')) {

                    Log::warning('Gemini API Rate Limit or Overload hit. Retrying...', [
                        'error' => $message,
                    ]);

                    // Wait 2 seconds before first retry, Laravel's retry() will handle subsequent delays
                    sleep(2);
                }
                throw $e;
            }
        }, 3000); // 3 seconds between retries
    }

    /**
     * Process study material using Gemini to extract text, summarize, and identify concepts.
     */
    public function processMaterial(array $filePaths, ?string $contentHash = null): array
    {
        $cacheKey = $contentHash ? 'summary_'.$contentHash : null;

        if ($cacheKey && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $apiKey = config('gemini.api_key');
            if (empty($apiKey)) {
                throw new \Exception('Gemini API key is not configured in .env file.');
            }

            $client = \Gemini::client($apiKey);

            $prompt = <<<'PROMPT'
            Analyze the provided study material and return a structured JSON response.

            CRITICAL VALIDATION:
            If the material is too short (e.g., only a few words), completely nonsensical, or lacks sufficient information to generate a study guide, you MUST return a JSON object with ONLY this key:
            { "error": "The provided content is too brief or doesn't appear to be valid study material. Please provide more detailed notes or a proper document." }

            Otherwise, return a single JSON object with these keys:
            1. "title": A short, contextual, and catchy title for this study guide (3-6 words).
            2. "raw_content": The full text extracted from the material, cleaned and formatted for readability.
            3. "summary": A Comprehensive Study Guide adaptive to the length and depth of the material. It MUST be formatted as a single string using Markdown. DO NOT use raw LaTeX or dollar signs ($) for math notation; use plain text or standard Markdown instead. Include exactly these Markdown headings:
               - "### Overview": An introductory paragraph summarizing the material.
               - "### Core Theories": A paragraph explaining the main concepts.
               - "### Crucial Exam Points": A section formatted as a Markdown bulleted list (e.g., "- Point 1") detailing the most important facts, formulas, or takeaways a student needs to memorize for an exam.
            4. "concepts": An array of ALL key concepts found in the material. Do not limit to a specific number; extract every valid concept you can find. DO NOT use dollar signs ($) in explanations. Each concept must be an object with:
               - "title": The name of the concept (3-5 words max).
               - "short_explanation": A concise explanation of the concept adaptive to its complexity.

            Return ONLY the raw JSON object. Do not include markdown formatting.
            PROMPT;

            $parts = $this->preparePromptParts($prompt, $filePaths);

            if (count($parts) <= 1) {
                throw new \Exception('No valid content found in the provided files.');
            }

            $data = $this->callGemini(function () use ($client, $parts, $filePaths) {
                Log::info('Gemini Material Processing started', ['fileCount' => count($filePaths)]);
                $modelName = config('gemini.model', 'gemini-1.5-flash-lite');

                $model = $client->generativeModel(model: $modelName)
                    ->withGenerationConfig(new GenerationConfig(
                        temperature: (float) config('gemini.temperature', 0.7),
                        topP: (float) config('gemini.top_p', 0.95),
                        topK: (int) config('gemini.top_k', 40),
                    ));

                $result = $model->generateContent($parts);
                $jsonResponse = $result->text();

                if (preg_match('/\{.*\}/s', $jsonResponse, $matches)) {
                    $jsonResponse = $matches[0];
                }

                $decoded = json_decode($jsonResponse, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Failed to parse AI response as valid JSON.');
                }

                return $decoded;
            });

            if ($cacheKey && ! isset($data['error']) && isset($data['summary']) && ! empty($data['summary'])) {
                Cache::put($cacheKey, $data, now()->addHours(24));
            }

            return $data;

        } catch (\Exception $e) {
            Log::error('OCR processMaterial Error: '.$e->getMessage());

            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Generate quiz questions based on the provided material content.
     */
    public function generateQuiz(array $filePaths, int $numQuestions = 5, string $quizType = 'mixed', ?string $fileHash = null, ?int $userId = null): array
    {
        $cacheKey = ($fileHash && $userId) ? "quiz_v1_{$userId}_{$fileHash}_{$numQuestions}_{$quizType}" : null;

        if ($cacheKey && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $apiKey = config('gemini.api_key');
            $client = \Gemini::client($apiKey);

            $typeInstructions = match ($quizType) {
                'multiple_choice' => 'CRITICAL: ONLY generate multiple-choice questions (multiple_choice). Each must have exactly 4 options. DO NOT generate any other question types.',
                'true_false' => 'CRITICAL: ONLY generate True/False questions (true_false). DO NOT generate any other question types.',
                'fill_in_the_blank' => 'CRITICAL: ONLY generate fill-in-the-blank questions (fill_in_the_blank). DO NOT generate any other question types.',
                default => 'Generate a balanced mix of multiple choice, true/false, and fill-in-the-blank questions.'
            };

            $prompt = <<<PROMPT
            Analyze the provided study material and generate a high-quality educational quiz with EXACTLY {$numQuestions} questions.
            
            FORMAT REQUIREMENT:
            {$typeInstructions}

            PEDAGOGICAL REQUIREMENTS:
            1. Distractors: For multiple-choice questions, ensure that incorrect options (distractors) are plausible and reflect common misconceptions about the topic. Avoid "None of the above" or "All of the above" unless absolutely necessary.
            2. Clarity: Questions should be unambiguous and focus on significant concepts rather than trivial details.
            3. Explanations: The "explanation" field must be detailed, explaining NOT ONLY why the correct answer is right but also briefly WHY the distractors are wrong (where applicable).
            
            Rules:
            - For multiple choice (multiple_choice): include an "options" array of 4 strings. The "correct_answer" MUST BE the EXACT text of one of these options.
            - For true/false (true_false): include an "options" array with EXACTLY two strings: ["True", "False"]. The "correct_answer" MUST BE either "True" or "False".
            - For fill-in-the-blank (fill_in_the_blank): the "correct_answer" should be the exact word or short phrase.
            - Each question must include a pedagogical "explanation" field justifying the correct answer based on the material.
            - Ensure the "type" field for EVERY question is strictly: "{$quizType}" (unless "mixed" was requested, in which case use the appropriate type for each question).

            Return a single JSON object with this key:
            1. "questions": An array of EXACTLY {$numQuestions} objects, each with:
               - "type": (string, MUST BE "multiple_choice", "true_false", or "fill_in_the_blank")
               - "question_text": (string)
               - "options": (array of strings, REQUIRED for multiple_choice and true_false)
               - "correct_answer": (string, must exactly match one of the options for MC/TF)
               - "explanation": (string)

            Return ONLY the raw JSON object. Do not include markdown formatting.
            PROMPT;

            $parts = $this->preparePromptParts($prompt, $filePaths);

            $data = $this->callGemini(function () use ($client, $parts, $numQuestions, $quizType) {
                Log::info('Gemini Quiz Generation started', ['num' => $numQuestions, 'type' => $quizType]);
                $modelName = config('gemini.model', 'gemini-1.5-flash-lite');

                $model = $client->generativeModel(model: $modelName)
                    ->withGenerationConfig(new GenerationConfig(
                        temperature: (float) config('gemini.temperature', 0.7),
                        topP: (float) config('gemini.top_p', 0.95),
                        topK: (int) config('gemini.top_k', 40),
                    ));

                $result = $model->generateContent($parts);
                $jsonResponse = $result->text();

                if (preg_match('/\{.*\}/s', $jsonResponse, $matches)) {
                    $jsonResponse = $matches[0];
                }

                $decoded = json_decode($jsonResponse, true);
                if (json_last_error() !== JSON_ERROR_NONE || ! isset($decoded['questions'])) {
                    throw new \Exception('Failed to generate valid quiz questions JSON.');
                }

                return $decoded;
            });

            if ($cacheKey && ! isset($data['error']) && isset($data['questions']) && count($data['questions']) > 0) {
                Cache::put($cacheKey, $data, now()->addHours(24));
            }

            return $data;

        } catch (\Exception $e) {
            Log::error('OCR generateQuiz Error: '.$e->getMessage());

            return ['error' => $e->getMessage(), 'questions' => []];
        }
    }

    /**
     * Generate flashcards based on the provided material content.
     */
    public function generateFlashcards(array $filePaths, int $numCards = 10, ?string $fileHash = null, ?int $userId = null): array
    {
        $cacheKey = ($fileHash && $userId) ? "flashcards_v1_{$userId}_{$fileHash}" : null;

        if ($cacheKey && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $apiKey = config('gemini.api_key');
            $client = \Gemini::client($apiKey);

            $prompt = <<<PROMPT
            Analyze the provided study material and generate {$numCards} 'Active Recall' flashcards for high-retention learning.
            
            ACTIVE RECALL PRINCIPLES:
            1. Single Concept: Each card should focus on ONE specific concept or fact. Avoid overloaded "fronts".
            2. Q&A Format: Prefer a question on the "front" and a direct answer on the "back".
            3. Precision: The "back" should be concise but contain all necessary information to verify the answer.
            4. Context: If a term is ambiguous, provide a small amount of context in parentheses on the "front".

            Rules:
            - "front": A clear question, a specific term, or a "fill-in-the-blank" statement.
            - "back": A concise, accurate answer or definition that provides clarity.
            - Focus on core principles, critical formulas, important dates, and key definitions found in the material.

            Return a single JSON object with this key:
            1. "flashcards": An array of EXACTLY {$numCards} objects, each with "front" and "back" keys.

            Return ONLY the raw JSON object. Do not include markdown formatting.
            PROMPT;

            $parts = $this->preparePromptParts($prompt, $filePaths);

            $data = $this->callGemini(function () use ($client, $parts) {
                Log::info('Gemini Flashcard Generation started');
                $modelName = config('gemini.model', 'gemini-1.5-flash-lite');

                $model = $client->generativeModel(model: $modelName)
                    ->withGenerationConfig(new GenerationConfig(
                        temperature: (float) config('gemini.temperature', 0.7),
                        topP: (float) config('gemini.top_p', 0.95),
                        topK: (int) config('gemini.top_k', 40),
                    ));

                $result = $model->generateContent($parts);
                $jsonResponse = $result->text();

                if (preg_match('/\{.*\}/s', $jsonResponse, $matches)) {
                    $jsonResponse = $matches[0];
                }

                $decoded = json_decode($jsonResponse, true);
                if (json_last_error() !== JSON_ERROR_NONE || ! isset($decoded['flashcards'])) {
                    throw new \Exception('Failed to generate valid flashcards JSON.');
                }

                return $decoded;
            });

            if ($cacheKey && ! isset($data['error']) && isset($data['flashcards']) && count($data['flashcards']) > 0) {
                Cache::put($cacheKey, $data, now()->addHours(24));
            }

            return $data;

        } catch (\Exception $e) {
            Log::error('OCR generateFlashcards Error: '.$e->getMessage());

            return ['error' => $e->getMessage(), 'flashcards' => []];
        }
    }

    /**
     * Prepares parts for Gemini API call, handling different file types.
     */
    protected function preparePromptParts(string $prompt, array $filePaths): array
    {
        $parts = [$prompt];

        foreach ($filePaths as $filePath) {
            if (! File::exists($filePath)) {
                continue;
            }

            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            if ($extension === 'docx') {
                $text = $this->extractTextFromDocx($filePath);
                if (! empty($text)) {
                    $parts[] = "Content from DOCX file:\n\n".$text;
                }

                continue;
            }

            $mimeTypeString = File::mimeType($filePath);
            $fileContent = File::get($filePath);

            try {
                $mimeType = MimeType::from($mimeTypeString);
                $parts[] = new Blob(
                    mimeType: $mimeType,
                    data: base64_encode($fileContent)
                );
            } catch (\ValueError $e) {
                Log::warning('Unsupported MimeType for Gemini Blob', [
                    'mime' => $mimeTypeString,
                    'path' => $filePath,
                ]);
            }
        }

        return $parts;
    }

    /**
     * Extract text from a DOCX file.
     */
    protected function extractTextFromDocx(string $filePath): string
    {
        $text = '';
        $zip = new \ZipArchive;

        if ($zip->open($filePath) === true) {
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $data = $zip->getFromIndex($index);
                // Remove XML tags but keep space/newlines for paragraphs
                $data = str_replace(['</w:p>', '</w:r>', '<w:tab/>'], ["\n", ' ', "\t"], $data);
                $text = strip_tags($data);
            }
            $zip->close();
        }

        return trim($text);
    }
}
