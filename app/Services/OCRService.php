<?php

namespace App\Services;

use Gemini\Data\Blob;
use Gemini\Enums\MimeType;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class OCRService
{
    /**
     * Process study material using Gemini to extract text, summarize, identify concepts, and generate quizzes.
     *
     * @param  string  $filePath  Absolute path to the file
     * @return array Structured data including summary, concepts, and questions
     */
    public function processMaterial(string $filePath): array
    {
        try {
            $apiKey = config('gemini.api_key');
            if (empty($apiKey)) {
                throw new \Exception('Gemini API key is not configured in .env file.');
            }
            
            $client = \Gemini::client($apiKey);

            $mimeTypeString = File::mimeType($filePath);
            Log::info('OCR Processing started', ['path' => $filePath, 'mime' => $mimeTypeString]);
            
            $fileContent = File::get($filePath);
            
            try {
                $mimeType = MimeType::from($mimeTypeString);
            } catch (\ValueError $e) {
                Log::error('Unsupported MimeType', ['mime' => $mimeTypeString]);
                throw new \Exception('The file type "' . $mimeTypeString . '" is not supported for AI analysis.');
            }

            // Prompt engineering for "Smart Tutor" engine
            $prompt = <<<'PROMPT'
            Analyze the provided study material and return a structured JSON response.

            CRITICAL VALIDATION:
            If the material is too short (e.g., only a few words), completely nonsensical, or lacks sufficient information to generate a study guide, you MUST return a JSON object with ONLY this key:
            { "error": "The provided content is too brief or doesn't appear to be valid study material. Please provide more detailed notes or a proper document." }

            Otherwise, return a single JSON object with these keys:
            1. "summary": A concise 2-sentence summary of the material.
            2. "concepts": An array of 5-7 key concepts. Each concept must be an object with:
               - "title": The name of the concept (3-5 words max).
               - "short_explanation": A 1-2 sentence explanation of the concept.
            3. "questions": An array of 5 Multiple Choice Questions. Each question object must have:
               - "question_text": The text of the question.
               - "options": An array of 4 distinct options (strings).
               - "correct_answer": The letter (A, B, C, or D) corresponding to the correct option.
               - "explanation": A brief explanation of why that answer is correct.

            Return ONLY the raw JSON object. Do not include markdown formatting.
            PROMPT;

            Log::info('Gemini API call started', ['model' => 'gemini-flash-latest']);
            $result = $client->generativeModel(model: 'gemini-flash-latest')
                ->generateContent([
                    $prompt,
                    new Blob(
                        mimeType: $mimeType,
                        data: base64_encode($fileContent)
                    ),
                ]);
            Log::info('Gemini API call finished');

            $jsonResponse = $result->text();

            // Robust JSON extraction using regex to find the first { and last }
            if (preg_match('/\{.*\}/s', $jsonResponse, $matches)) {
                $jsonResponse = $matches[0];
            }

            $data = json_decode($jsonResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Gemini JSON parsing error: '.json_last_error_msg(), ['response' => $jsonResponse]);
                throw new \Exception('Failed to parse AI response as valid JSON.');
            }

            // Check if AI returned a validation error
            if (isset($data['error'])) {
                return $data;
            }

            // Basic validation of the structure
            if (! isset($data['summary']) || ! isset($data['concepts']) || ! isset($data['questions'])) {
                throw new \Exception('AI response is missing required fields.');
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('Smart Tutor Engine Error: '.$e->getMessage());

            return [
                'error' => 'Smart Tutor Error: '.$e->getMessage(),
                'summary' => 'Unable to generate summary due to an error.',
                'concepts' => [],
                'questions' => [],
            ];
        }
    }

    /**
     * Legacy method for simple text extraction (if still needed elsewhere)
     */
    public function extractText(string $filePath): string
    {
        $data = $this->processMaterial($filePath);

        if (isset($data['error'])) {
            return $data['error'];
        }

        // Return a combined string of everything as a fallback for the old "raw_content" column
        $output = "SUMMARY:\n".$data['summary']."\n\n";
        $output .= "CONCEPTS:\n- ".implode("\n- ", $data['concepts'])."\n\n";

        return $output;
    }
}
