<?php

namespace App\Models;

use App\Notifications\VerifyEmailCode;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Send the email verification notification.
     * Overrides the default signed URL verification to send an OTP code instead.
     */
    public function sendEmailVerificationNotification(): void
    {
        $code = sprintf('%06d', mt_rand(100000, 999999));
        Cache::put('email_verification_code_'.$this->id, $code, now()->addMinutes(15));

        $this->notify(new VerifyEmailCode($code));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_streak_updated_at' => 'datetime',
        ];
    }

    /**
     * Update the user's login streak.
     */
    public function updateStreak(): void
    {
        $today = now()->startOfDay();
        $lastUpdate = $this->last_streak_updated_at ? $this->last_streak_updated_at->startOfDay() : null;

        if (! $lastUpdate) {
            $this->current_streak = 1;
        } elseif ($lastUpdate->equalTo($today)) {
            // Already updated today
            return;
        } elseif ($lastUpdate->equalTo($today->copy()->subDay())) {
            // Logged in yesterday, increment streak
            $this->current_streak += 1;
        } else {
            // Streak broken
            $this->current_streak = 1;
        }

        if ($this->current_streak > $this->longest_streak) {
            $this->longest_streak = $this->current_streak;
        }

        $this->last_streak_updated_at = now();
        $this->save();
    }

    /**
     * Get the flame color based on the current streak.
     */
    public function getStreakColorAttribute(): string
    {
        $streak = $this->current_streak;

        if ($streak >= 50) {
            return '#3B82F6';
        } // Blue
        if ($streak >= 30) {
            return '#8B5CF6';
        } // Violet
        if ($streak >= 20) {
            return '#EC4899';
        } // Pink
        if ($streak >= 10) {
            return '#F59E0B';
        } // Amber/Orange
        if ($streak >= 1) {
            return '#EF4444';
        } // Red

        return '#7C7167'; // Default Gray
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function flashcards(): HasManyThrough
    {
        return $this->hasManyThrough(Flashcard::class, Material::class);
    }
}
