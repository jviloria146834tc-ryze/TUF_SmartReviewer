<?php

use App\Http\Controllers\MaterialController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResultsController;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', function (Request $request) {
    $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ]);

    User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect('/login')->with('success', 'Registered successfully!');
})->name('register.post');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials',
    ]);
})->name('login.post');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/logout-success');
})->name('logout');

Route::get('/logout-success', function () {
    return view('logout-success');
})->name('logout.success');

Route::get('/dashboard', function () {
    $materials = collect();
    $quizzes = collect();
    $stats = [
        'materials_count' => 0,
        'quizzes_count' => 0,
        'avg_score' => 'N/A',
    ];

    if (auth()->check()) {
        $user = auth()->user();
        $materials = $user->materials()->latest()->take(5)->get();

        // Get recent quizzes for materials that are completed
        $quizzes = Quiz::whereIn('material_id', $user->materials()->where('status', 'completed')->pluck('id'))
            ->latest()
            ->take(5)
            ->get();

        $stats['materials_count'] = $user->materials()->count();
        $stats['quizzes_count'] = $user->quizAttempts()->count();

        $validAttempts = $user->quizAttempts()->where('total_questions', '>', 0);
        if ($validAttempts->exists()) {
            $stats['avg_score'] = round($validAttempts->get()->avg(fn ($a) => ($a->score / $a->total_questions) * 100)).'%';
        }
    }

    return view('dashboard', [
        'materials' => $materials,
        'quizzes' => $quizzes,
        'stats' => $stats,
    ]);
})->name('dashboard');

Route::get('/upload', function () {
    return view('upload');
})->name('materials.upload');

Route::post('/upload', [MaterialController::class, 'store'])->name('materials.store');
Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');

Route::get('/materials', function () {
    $materials = collect();
    if (auth()->check()) {
        $query = auth()->user()->materials()->latest();

        if (request()->filled('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        $materials = $query->paginate(10)->withQueryString();
    }

    return view('materials', ['materials' => $materials]);
})->name('materials.index');

Route::get('/reviewer/{material?}', function (?Material $material = null) {
    if (! $material && auth()->check()) {
        $material = auth()->user()->materials()->where('status', 'completed')->latest()->first();
    }

    return view('reviewer', ['material' => $material]);
})->name('reviewer');

// Quiz Routes
Route::get('/quizzes', function () {
    $currentTab = request('tab', 'active');
    $quizzes = collect();

    if (auth()->check()) {
        $quizIds = auth()->user()->quizAttempts()->pluck('quiz_id');

        if ($currentTab === 'completed') {
            $quizzes = Quiz::whereIn('id', $quizIds)->latest()->get();
        } else {
            $quizzes = Quiz::whereHas('material', function ($q) {
                $q->where('user_id', auth()->id());
            })->whereNotIn('id', $quizIds)->latest()->get();
        }
    }

    return view('quizzes', ['quizzes' => $quizzes, 'currentTab' => $currentTab]);
})->name('quizzes.index');

Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quizzes.session');
Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
Route::get('/quiz/breakdown/{attempt}', [QuizController::class, 'breakdown'])->name('quizzes.breakdown');

Route::get('/results', [ResultsController::class, 'index'])->name('quizzes.results');
