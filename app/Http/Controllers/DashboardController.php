<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $materials = $user->materials()->latest()->take(10)->get();

        // Get a stable motivational message for this session
        $messages = [
            'What topics are we crushing today?',
            'Ready to ace your next exam?',
            'Let\'s turn those notes into knowledge.',
            'Consistency is the key to mastery. Keep it up!',
            'Another day, another chance to grow.',
            'Success starts with a single study session.',
            'Ready to level up your understanding?',
        ];

        if (! session()->has('dashboard_message')) {
            session(['dashboard_message' => $messages[array_rand($messages)]]);
        }

        $dashboardMessage = session('dashboard_message');

        // Get completed quiz IDs to filter them out from "Active Quizzes"
        $activeAttemptQuizIds = $user->quizAttempts()->whereNull('completed_at')->pluck('quiz_id');
        $anyCompletedQuizIds = $user->quizAttempts()->whereNotNull('completed_at')->pluck('quiz_id');
        $completedQuizIds = $anyCompletedQuizIds->diff($activeAttemptQuizIds);

        // Get recent unattempted quizzes for materials that are completed
        $quizzes = Quiz::whereIn('material_id', $user->materials()->where('status', 'completed')->pluck('id'))
            ->whereNotIn('id', $completedQuizIds)
            ->withCount('questions')
            ->with('questions:id,quiz_id,type')
            ->latest()
            ->take(10)
            ->get();

        $validAttempts = $user->quizAttempts()->where('total_questions', '>', 0);

        // OPTIMIZED: Native SQL average calculation to prevent memory leaks with large datasets
        $avgScoreValue = $validAttempts->exists()
            ? round($validAttempts->avg(DB::raw('score * 100.0 / total_questions')))
            : 0;

        $materialsCount = $user->materials()->count();
        $quizzesCount = $user->quizAttempts()->count();
        $masteredFlashcards = $user->flashcards()->where('is_mastered', true)->count();

        // Session-based change tracking
        if (! session()->has('session_start_materials_count')) {
            session(['session_start_materials_count' => $materialsCount]);
        }
        if (! session()->has('session_start_quizzes_count')) {
            session(['session_start_quizzes_count' => $quizzesCount]);
        }
        if (! session()->has('session_start_avg_score')) {
            session(['session_start_avg_score' => $avgScoreValue]);
        }
        if (! session()->has('session_start_mastery_count')) {
            session(['session_start_mastery_count' => $masteredFlashcards]);
        }

        $materialsDelta = $materialsCount - session('session_start_materials_count');
        $quizzesDelta = $quizzesCount - session('session_start_quizzes_count');
        $avgScoreDelta = $avgScoreValue - session('session_start_avg_score');
        $masteryDelta = $masteredFlashcards - session('session_start_mastery_count');

        $stats = [
            'materials_count' => $materialsCount,
            'quizzes_count' => $quizzesCount,
            'avg_score' => $avgScoreValue.'%',
            'materials_delta' => $materialsDelta > 0 ? '+'.$materialsDelta : null,
            'quizzes_delta' => $quizzesDelta > 0 ? '+'.$quizzesDelta : null,
            'avg_score_delta' => $avgScoreDelta,
            'mastered_flashcards' => $masteredFlashcards,
            'mastery_delta' => $masteryDelta > 0 ? '+'.$masteryDelta : null,
        ];

        // Weekly Performance (Last 7 days)
        $startOfWeek = now()->subDays(6)->startOfDay();
        $endOfWeek = now()->endOfDay();

        $weeklyAttempts = $user->quizAttempts()
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->with('quiz.material')
            ->get();

        $weeklyCards = $user->flashcards()
            ->where('is_mastered', true)
            ->whereBetween('flashcards.updated_at', [$startOfWeek, $endOfWeek])
            ->with('material')
            ->get();

        $weeklyMaterials = $user->materials()
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get();

        $weeklyPerformance = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->format('D');
            $fullDayName = now()->subDays($i)->format('l');

            $dayAttempts = $weeklyAttempts->filter(fn ($a) => $a->created_at->format('Y-m-d') === $date);
            $dayCards = $weeklyCards->filter(fn ($c) => $c->updated_at->format('Y-m-d') === $date);
            $dayMaterials = $weeklyMaterials->filter(fn ($m) => $m->created_at->format('Y-m-d') === $date);

            // Group by material
            $details = [];
            foreach ($dayMaterials as $material) {
                $mTitle = $material->title;
                $details[$mTitle]['uploaded'] = ($details[$mTitle]['uploaded'] ?? 0) + 1;
            }
            foreach ($dayAttempts as $attempt) {
                $mTitle = $attempt->quiz->material->title;
                $details[$mTitle]['quizzes'] = ($details[$mTitle]['quizzes'] ?? 0) + 1;
            }
            foreach ($dayCards as $card) {
                $mTitle = $card->material->title;
                $details[$mTitle]['cards'] = ($details[$mTitle]['cards'] ?? 0) + 1;
            }

            $weeklyPerformance[$dayName] = [
                'full_day' => $fullDayName,
                'count' => $dayAttempts->count() + $dayCards->count() + $dayMaterials->count(),
                'details' => $details,
            ];
        }

        // Mastery Insights
        $totalFlashcards = $user->flashcards()->count();
        $masteryPercentage = $totalFlashcards > 0 ? round(($masteredFlashcards / $totalFlashcards) * 100) : 0;

        $masteryMaterialsQuery = $user->materials()->whereHas('flashcards');
        $masteryMaterialsCount = $masteryMaterialsQuery->count();

        $masteryDetails = $masteryMaterialsQuery
            ->withCount(['flashcards', 'flashcards as mastered_count' => function ($query) {
                $query->where('is_mastered', true);
            }])
            ->get()
            ->map(fn ($m) => [
                'title' => $m->title,
                'total' => $m->flashcards_count,
                'mastered' => $m->mastered_count,
                'percent' => $m->flashcards_count > 0 ? round(($m->mastered_count / $m->flashcards_count) * 100) : 0,
            ])
            ->sortByDesc('total')
            ->take(5)
            ->values()
            ->toArray();

        // Score Trends (Last 10 attempts for line graph)
        $scoreTrends = $validAttempts->with('quiz')->latest()->take(10)->get()
            ->reverse()
            ->map(fn ($a) => [
                'score' => round(($a->score / $a->total_questions) * 100),
                'title' => $a->quiz->title,
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

            // If previousAvg is still 0 (all scores 0), avoid division by zero
            if ($previousAvg > 0) {
                $improvement = round((($recentAvg - $previousAvg) / $previousAvg) * 100, 1);
            } elseif ($recentAvg > 0) {
                $improvement = 100; // From 0 to something is 100% improvement
            }
        }

        return view('dashboard', [
            'materials' => $materials,
            'quizzes' => $quizzes,
            'stats' => $stats,
            'weeklyPerformance' => $weeklyPerformance,
            'masteryPercentage' => $masteryPercentage,
            'masteryDetails' => $masteryDetails,
            'masteryMaterialsCount' => $masteryMaterialsCount,
            'scoreTrends' => $scoreTrends,
            'improvement' => $improvement,
            'dashboardMessage' => $dashboardMessage,
        ]);
    }
}
