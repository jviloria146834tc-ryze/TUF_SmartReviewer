@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 animate-glide-up">
    <!-- Header -->
    <div>
        <a href="{{ route('dashboard') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
            &larr; Back to Dashboard
        </a>
        <h1 class="text-3xl font-bold font-['Inter'] text-[#1A1714]">Profile Settings</h1>
        <p class="text-[#7C7167] mt-1">Manage your account details and security settings.</p>
    </div>

    <!-- Account Information -->
    <div class="bg-white border border-[#E2DDD8] rounded-[24px] overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-[#E2DDD8] bg-gray-50/50">
            <h2 class="text-lg font-bold text-[#1A1714]">Account Information</h2>
            <p class="text-[#7C7167] text-sm mt-0.5">Update your account's profile information and email address.</p>
        </div>
        
        <div class="p-6 md:p-8">
            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                @if (session('status') === 'profile-updated')
                    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)" class="bg-emerald-50 text-emerald-800 border border-emerald-200 p-4 rounded-2xl text-sm font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Profile information updated successfully.
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div class="space-y-2">
                        <label for="first_name" class="block text-sm font-semibold text-[#1A1714]">First Name</label>
                        <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}" required autofocus autocomplete="given-name" class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3 text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 transition-all shadow-sm">
                        @error('first_name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="space-y-2">
                        <label for="last_name" class="block text-sm font-semibold text-[#1A1714]">Last Name</label>
                        <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}" required autocomplete="family-name" class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3 text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 transition-all shadow-sm">
                        @error('last_name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-[#1A1714]">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3 text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 transition-all shadow-sm">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3">
                            <div class="bg-amber-500 text-white p-1 rounded-full flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-amber-800 font-bold text-[14px]">Email Unverified</h4>
                                <p class="text-amber-700 text-[13px] mt-0.5">Your email address is unverified. <button form="send-verification" class="font-bold underline hover:text-amber-900 focus:outline-none">Click here to re-send the verification email.</button></p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-[#E2DDD8]">
                    <button type="submit" class="bg-[#6646E5] hover:bg-[#5538D4] text-white px-6 py-2.5 rounded-[12px] font-semibold text-[15px] transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">Save Changes</button>
                </div>
            </form>

            <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
                @csrf
            </form>
        </div>
    </div>

    <!-- Update Password -->
    <div class="bg-white border border-[#E2DDD8] rounded-[24px] overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-[#E2DDD8] bg-gray-50/50">
            <h2 class="text-lg font-bold text-[#1A1714]">Update Password</h2>
            <p class="text-[#7C7167] text-sm mt-0.5">Ensure your account is using a long, random password to stay secure.</p>
        </div>
        
        <div class="p-6 md:p-8">
            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                @if (session('status') === 'password-updated')
                    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)" class="bg-emerald-50 text-emerald-800 border border-emerald-200 p-4 rounded-2xl text-sm font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Password updated successfully.
                    </div>
                @endif

                <!-- Current Password -->
                <div class="space-y-2 w-full" x-data="{ show: false }">
                    <label for="update_password_current_password" class="block text-sm font-semibold text-[#1A1714]">Current Password</label>
                    <div class="relative w-full">
                        <input id="update_password_current_password" name="current_password" :type="show ? 'text' : 'password'" autocomplete="current-password" class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3 pr-12 text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 transition-all shadow-sm">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#6646E5] transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="space-y-2 w-full" x-data="{ show: false }">
                    <label for="update_password_password" class="block text-sm font-semibold text-[#1A1714]">New Password</label>
                    <div class="relative w-full">
                        <input id="update_password_password" name="password" :type="show ? 'text' : 'password'" autocomplete="new-password" class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3 pr-12 text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 transition-all shadow-sm">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#6646E5] transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2 w-full" x-data="{ show: false }">
                    <label for="update_password_password_confirmation" class="block text-sm font-semibold text-[#1A1714]">Confirm Password</label>
                    <div class="relative w-full">
                        <input id="update_password_password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" autocomplete="new-password" class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3 pr-12 text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 transition-all shadow-sm">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#6646E5] transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-[#E2DDD8]">
                    <button type="submit" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white px-6 py-2.5 rounded-[12px] font-semibold text-[15px] transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection