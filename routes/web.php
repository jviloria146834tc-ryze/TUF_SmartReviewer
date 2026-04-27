<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/loading', function () {
    return view('loading');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/upload', function () {
    return view('upload');
});

Route::get('/materials', function () {
    return view('materials');
});

Route::get('/reviewer', function () {
    return view('reviewer');
});

Route::get('/quizzes', function () {
    return view('quizzes');
});

Route::get('/quiz-session', function () {
    return view('quiz-session');
});

Route::get('/results', function () {
    return view('results');
});

Route::get('/quiz-breakdown', function () {
    return view('quiz-breakdown');
});

Route::post('/quiz-submit', function () {
    // When the user submits the quiz from /quiz-session, 
    // it processes the score and redirects them to their specific breakdown.
    return redirect('/quiz-breakdown');
});

Route::get('/logout-success', function () {
    return view('logout-success');
});
Route::get('/register', function () {
    return view('register');
});

Route::post('/register', function (Request $request) {
    dd($request->all()); 
});

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

    return redirect('/register')->with('success', 'Registered successfully!');
});

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', function (Request $request) {

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials',
    ]);
});