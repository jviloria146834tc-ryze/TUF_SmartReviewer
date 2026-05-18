<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class QuizController extends Controller
{
    /**
     * Display the specified quiz session.
     */
    public function show(Quiz $quiz)
    {
        // Fallback for tests
        if (! $quiz->exists && $id = request()->route('quiz')) {
            $quiz = Quiz::findOrFail($id);
        }

        Gate::authorize('view', $quiz);

        // Find ongoing attempt or create a new one
        $attempt = QuizAttempt::firstOrCreate(
            ['user_id' => Auth::id(), 'quiz_id' => $quiz->id, 'completed_at' => null],
            ['total_questions' => $quiz->questions()->count(), 'score' => 0, 'time_taken' => 0, 'answers_json' => []]
        );

        $questions = $quiz->questions;

        return view('quiz-session', compact('quiz', 'questions', 'attempt'));
    }

    /**
     * Save quiz progress asynchronously.
     */
    public function saveProgress(Request $request, QuizAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id() || $attempt->completed_at !== null) {
            abort(403);
        }

        $attempt->update([
            'answers_json' => $request->input('answers', []),
            'time_taken' => $request->input('time_taken', $attempt->time_taken),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Handle the quiz submission.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        // Fallback for tests
        if (! $quiz->exists && $id = $request->route('quiz')) {
            $quiz = Quiz::findOrFail($id);
        }

        Gate::authorize('view', $quiz);

        $attempt = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->whereNull('completed_at')
            ->latest()
            ->firstOrFail();

        $userAnswers = $request->input('answers', []); // Expected format: [question_id => selected_letter]
        $timeTaken = $request->input('time_taken', $attempt->time_taken);
        $questions = $quiz->questions;

        $score = 0;
        $totalQuestions = $questions->count();
        $results = [];

        foreach ($questions as $question) {
            $userAnswer = $userAnswers[$question->id] ?? null;
            $correctAnswer = $question->correct_answer;

            // Normalize for comparison (case-insensitive, trimmed)
            $userAnswerNorm = strtolower(trim($userAnswer ?? ''));
            $correctAnswerNorm = strtolower(trim($correctAnswer ?? ''));

            $isCorrect = ($userAnswerNorm === $correctAnswerNorm);

            if ($isCorrect) {
                $score++;
            }

            $results[$question->id] = [
                'user_answer' => $userAnswer,
                'correct_answer' => $correctAnswer,
                'is_correct' => $isCorrect,
            ];
        }

        $attempt->update([
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

    /**
     * Delete the quiz.
     */
    public function destroy(Quiz $quiz)
    {
        Gate::authorize('view', $quiz);
        $quiz->delete();

        return back()->with('success', 'Quiz deleted successfully.');
    }
}
