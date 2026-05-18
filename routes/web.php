<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResultsController;
use App\Models\Material;
use App\Models\Quiz;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/about', 'about')->name('about');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verify-code', function (Request $request) {
    $request->validate(['code' => 'required|string|size:6']);

    $user = $request->user();

    if ($user->hasVerifiedEmail()) {
        return redirect('/dashboard');
    }

    $cachedCode = Cache::get('email_verification_code_'.$user->id);

    if (! $cachedCode || $cachedCode !== $request->code) {
        return back()->withErrors(['code' => 'The verification code is invalid or has expired.']);
    }

    if ($user->markEmailAsVerified()) {
        event(new Verified($user));
    }

    Cache::forget('email_verification_code_'.$user->id);

    return redirect('/dashboard')->with('success', 'Email verified successfully!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.verify_code');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'A new verification code has been sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1')->name('register.post');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->middleware(['guest', 'throttle:6,1'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.update');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/logout-success', function () {
    return view('logout-success');
})->name('logout.success');

Route::middleware(['auth', 'prevent-back', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/upload', function () {
        return view('upload');
    })->name('materials.upload');

    Route::post('/upload', [MaterialController::class, 'store'])->name('materials.store');
    Route::post('/materials/{material}/reprocess', [MaterialController::class, 'reprocess'])->name('materials.reprocess');
    Route::post('/materials/{material}/generate-quiz', [MaterialController::class, 'generateQuiz'])->name('materials.generate_quiz');
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');

    // Flashcard Routes
    Route::get('/materials/{material}/flashcards', function (Material $material) {
        Gate::authorize('view', $material);

        $flashcards = $material->flashcards()->oldest()->get();

        return view('flashcards.index', [
            'material' => $material,
            'flashcards' => $flashcards,
        ]);
    })->name('flashcards.index');

    Route::post('/materials/{material}/generate-flashcards', [FlashcardController::class, 'store'])->name('flashcards.generate');
    Route::post('/flashcards/{flashcard}/toggle-mastery', [FlashcardController::class, 'toggleMastery'])->name('flashcards.toggle_mastery');
    Route::post('/materials/{material}/reset-flashcard-mastery', [FlashcardController::class, 'resetMastery'])->name('flashcards.reset_mastery');

    Route::get('/materials', function () {
        $query = auth()->user()->materials()->latest();

        if (request()->filled('search')) {
            $query->where('title', 'like', '%'.request('search').'%');
        }

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        $materials = $query->paginate(10)->withQueryString();

        return view('materials', ['materials' => $materials]);
    })->name('materials.index');

    Route::get('/reviewer/{material?}', function (?Material $material = null) {
        if (request()->has('reset')) {
            session()->forget('last_viewed_material_id');

            return redirect()->route('reviewer');
        }

        if (! $material) {
            $materialId = session('last_viewed_material_id');
            if ($materialId) {
                $material = Material::find($materialId);
            }
        }

        if ($material) {
            Gate::authorize('view', $material);
            session(['last_viewed_material_id' => $material->id]);
        }

        $allMaterials = auth()->user()->materials()
            ->where('status', 'completed')
            ->withCount(['flashcards', 'flashcards as mastered_flashcards_count' => function ($query) {
                $query->where('is_mastered', true);
            }])
            ->latest()
            ->get();

        $latestUnattemptedQuiz = null;
        $allFlashcardsMastered = false;

        if ($material) {
            $latestUnattemptedQuiz = $material->quizzes()
                ->whereDoesntHave('attempts', function ($q) {
                    $q->where('user_id', auth()->id())->whereNotNull('completed_at');
                })
                ->latest()
                ->first();

            $flashcardsCount = $material->flashcards()->count();
            $allFlashcardsMastered = $flashcardsCount > 0 &&
                                    $material->flashcards()->where('is_mastered', true)->count() === $flashcardsCount;
        }

        return view('reviewer', [
            'material' => $material,
            'allMaterials' => $allMaterials,
            'latestUnattemptedQuiz' => $latestUnattemptedQuiz,
            'allFlashcardsMastered' => $allFlashcardsMastered,
        ]);
    })->name('reviewer');

    // Quiz Routes
    Route::get('/quizzes', function () {
        $currentTab = request('tab', 'active');
        $user = auth()->user();

        $activeAttemptQuizIds = $user->quizAttempts()->whereNull('completed_at')->pluck('quiz_id');
        $anyCompletedQuizIds = $user->quizAttempts()->whereNotNull('completed_at')->pluck('quiz_id');

        // A quiz is fully completed only if it has completed attempts and NO active attempts
        $completedQuizIds = $anyCompletedQuizIds->diff($activeAttemptQuizIds);

        $attemptedQuizIds = $user->quizAttempts()->pluck('quiz_id');

        if ($currentTab === 'completed') {
            $quizzes = Quiz::with(['material', 'questions:id,quiz_id,type'])->whereIn('id', $completedQuizIds)->latest()->get();
        } else {
            $quizzes = Quiz::with(['material', 'questions:id,quiz_id,type'])->whereHas('material', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->whereNotIn('id', $completedQuizIds)->latest()->get();
        }

        $materials = $user->materials()->with('quizzes')->where('status', 'completed')->latest()->get();

        return view('quizzes', [
            'quizzes' => $quizzes,
            'currentTab' => $currentTab,
            'materials' => $materials,
            'attemptedQuizIds' => $attemptedQuizIds,
            'completedQuizIds' => $completedQuizIds,
        ]);
    })->name('quizzes.index');

    Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quizzes.session');
    Route::post('/quiz/attempt/{attempt}/save', [QuizController::class, 'saveProgress'])->name('quizzes.save_progress');
    Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quiz/breakdown/{attempt}', [QuizController::class, 'breakdown'])->name('quizzes.breakdown');
    Route::delete('/quiz/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');

    Route::get('/results', [ResultsController::class, 'index'])->name('quizzes.results');
});
