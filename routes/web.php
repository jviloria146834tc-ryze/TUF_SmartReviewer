<?php

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
