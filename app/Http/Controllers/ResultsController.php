<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ResultsController extends Controller
{
    /**
     * Display the performance history and metrics.
     */
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $attempts = $user->quizAttempts()
            ->with('quiz')
            ->latest()
            ->paginate(15);

        // Calculate aggregate metrics (Percentage based)
        $validAttempts = $user->quizAttempts()->where('total_questions', '>', 0);

        $avgScore = $validAttempts->exists()
            ? round($validAttempts->get()->avg(fn ($a) => ($a->score / $a->total_questions) * 100))
            : 0;

        $bestScore = $validAttempts->exists()
            ? round($validAttempts->get()->max(fn ($a) => ($a->score / $a->total_questions) * 100))
            : 0;
        $totalQuizzes = $user->quizAttempts()->count();

        return view('results', compact('attempts', 'avgScore', 'bestScore', 'totalQuizzes'));
    }
}
