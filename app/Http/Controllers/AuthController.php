<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function showLogin()
    {
        return view('login', [
            'lastStreak' => request()->cookie('last_streak', 0),
            'lastStreakColor' => request()->cookie('last_streak_color', '#7C7167'),
            'lastAvgScore' => request()->cookie('last_avg_score', 0),
            'lastMaterialsCount' => request()->cookie('last_materials_count', 0),
            'lastMasteryCount' => request()->cookie('last_mastery_count', 0),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::user();
            $user->updateStreak();

            // Set cookies for persistent motivation on login screen
            Cookie::queue('last_streak', $user->current_streak, 60 * 24 * 30); // 30 days
            Cookie::queue('last_streak_color', $user->streak_color, 60 * 24 * 30);

            $validAttempts = $user->quizAttempts()->where('total_questions', '>', 0);
            $avgScore = $validAttempts->exists()
                ? round($validAttempts->avg(\Illuminate\Support\Facades\DB::raw('score * 100.0 / total_questions')))
                : 0;
            
            Cookie::queue('last_avg_score', $avgScore, 60 * 24 * 30);
            Cookie::queue('last_materials_count', $user->materials()->count(), 60 * 24 * 30);
            Cookie::queue('last_mastery_count', $user->flashcards()->where('is_mastered', true)->count(), 60 * 24 * 30);

            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user) {
            // Refresh cookies with latest data before logging out
            Cookie::queue('last_streak', $user->current_streak, 60 * 24 * 30);
            Cookie::queue('last_streak_color', $user->streak_color, 60 * 24 * 30);

            $validAttempts = $user->quizAttempts()->where('total_questions', '>', 0);
            $avgScore = $validAttempts->exists()
                ? round($validAttempts->avg(\Illuminate\Support\Facades\DB::raw('score * 100.0 / total_questions')))
                : 0;
                
            Cookie::queue('last_avg_score', $avgScore, 60 * 24 * 30);
            Cookie::queue('last_materials_count', $user->materials()->count(), 60 * 24 * 30);
            Cookie::queue('last_mastery_count', $user->flashcards()->where('is_mastered', true)->count(), 60 * 24 * 30);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('logout.success');
    }
}
