<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display the specified quiz session.
     */
    public function show($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);
        $questions = $quiz->questions;

        return view('quiz-session', compact('quiz', 'questions'));
    }

    /**
     * Handle the quiz submission.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $userAnswers = $request->input('answers', []); // Expected format: [question_id => selected_letter]
        $timeTaken = $request->input('time_taken', 0);
        $questions = $quiz->questions;

        $score = 0;
        $totalQuestions = $questions->count();
        $results = [];
        $alphabet = range('A', 'Z');

        foreach ($questions as $question) {
            $userLetter = $userAnswers[$question->id] ?? null;
            $correctLetter = strtoupper($question->correct_answer);
            $isCorrect = ($userLetter === $correctLetter);

            if ($isCorrect) {
                $score++;
            }

            // Map letters back to full text for the breakdown view
            $userIndex = array_search($userLetter, $alphabet);
            $userText = $question->options[$userIndex] ?? $userLetter;

            $correctIndex = array_search($correctLetter, $alphabet);
            $correctText = $question->options[$correctIndex] ?? $correctLetter;

            $results[$question->id] = [
                'user_answer' => $userText,
                'user_letter' => $userLetter,
                'correct_answer' => $correctText,
                'correct_letter' => $correctLetter,
                'is_correct' => $isCorrect,
            ];
        }

        $attempt = QuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'total_questions' => $totalQuestions,
            'answers_json' => $results,
            'time_taken' => $timeTaken,
            'completed_at' => now(),
        ]);

        return redirect()->route('quizzes.breakdown', $attempt->id);
    }

    /**
     * Display the quiz breakdown/results.
     */
    public function breakdown(QuizAttempt $attempt)
    {
        // Ensure the user owns this attempt
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        return view('quiz-breakdown', compact('attempt'));
    }
}
