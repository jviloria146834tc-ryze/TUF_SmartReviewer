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
            ->with(['quiz.questions:id,quiz_id,type'])
            ->latest()
            ->paginate(15);

        $validAttempts = $user->quizAttempts()->where('total_questions', '>', 0);

        $avgScore = $validAttempts->exists()
            ? round($validAttempts->get()->avg(fn ($a) => ($a->score / $a->total_questions) * 100))
            : 0;

        $totalQuizzes = $user->quizAttempts()->count();

        // Calculate Accuracy Rate (Total Correct / Total Questions across all attempts)
        $accuracyData = $user->quizAttempts()
            ->where('total_questions', '>', 0)
            ->selectRaw('SUM(score) as total_correct')
            ->selectRaw('SUM(total_questions) as total_questions_sum')
            ->first();

        $accuracyRate = ($accuracyData && $accuracyData->total_questions_sum > 0)
            ? round(($accuracyData->total_correct / $accuracyData->total_questions_sum) * 100)
            : 0;

        // Session-based change tracking (Replicating Dashboard behavior)
        if (! session()->has('session_start_avg_score')) {
            session(['session_start_avg_score' => $avgScore]);
        }
        if (! session()->has('session_start_quizzes_count')) {
            session(['session_start_quizzes_count' => $totalQuizzes]);
        }
        if (! session()->has('session_start_accuracy_rate')) {
            session(['session_start_accuracy_rate' => $accuracyRate]);
        }

        $avgScoreDelta = $avgScore - session('session_start_avg_score');
        $quizzesDelta = $totalQuizzes - session('session_start_quizzes_count');
        $accuracyDelta = $accuracyRate - session('session_start_accuracy_rate');

        $stats = [
            'avg_score' => $avgScore,
            'avg_score_delta' => $avgScoreDelta,
            'quizzes_count' => $totalQuizzes,
            'quizzes_delta' => $quizzesDelta > 0 ? '+'.$quizzesDelta : null,
            'accuracy_rate' => $accuracyRate,
            'accuracy_delta' => $accuracyDelta,
        ];

        // Score Trends (Last 10 attempts for line graph)
        $scoreTrends = $validAttempts->with('quiz')->latest()->take(10)->get()
            ->reverse()
            ->map(fn ($a) => [
                'score' => round(($a->score / $a->total_questions) * 100),
                'title' => $a->quiz->title ?? 'Untitled Quiz',
                'date' => $a->created_at->format('M d'),
            ])
            ->values()
            ->toArray();

        // Calculate improvement (Split available attempts in half)
        $allScores = $validAttempts->latest()->take(10)->get();
        $scoreCount = $allScores->count();
        $improvement = 0;

        if ($scoreCount >= 2) {
            $half = ceil($scoreCount / 2);
            $recentAvg = $allScores->take($half)->avg(fn ($a) => ($a->score / $a->total_questions) * 100);
            $previousAvg = $allScores->slice($half)->avg(fn ($a) => ($a->score / $a->total_questions) * 100) ?? $allScores->last()->score;

            if ($previousAvg > 0) {
                $improvement = round((($recentAvg - $previousAvg) / $previousAvg) * 100, 1);
            } elseif ($recentAvg > 0) {
                $improvement = 100;
            }
        }

        return view('results', compact('attempts', 'stats', 'scoreTrends', 'improvement'));
    }
}
