<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRules;

class AuthController extends Controller
{
    /**
     * Send a password reset link to the user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // We will send the password reset link to this user. Once it has been sent
        // we will examine the response then see the message we need to show to the user.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display the password reset view for the given token.
     */
    public function showResetForm(Request $request, ?string $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Handle a password reset request for the application.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRules::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the login view with a success message. Otherwise we will redirect back
        // to the previous display with the error message.
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

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
            'password' => ['required', 'confirmed', PasswordRules::defaults()],
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
                ? round($validAttempts->avg(DB::raw('score * 100.0 / total_questions')))
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
                ? round($validAttempts->avg(DB::raw('score * 100.0 / total_questions')))
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
