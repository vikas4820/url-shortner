@extends('layouts.app')

@section('title', ' - Sign In')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-10">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2" aria-label="{{ config('app.name', 'URL Shortener') }} Home">
                <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center mx-auto">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-gray-900">{{ config('app.name', 'URL Shortener') }}</span>
            </a>
        </div>

        <!-- Card -->
        <x-card variant="elevated" padding="lg" class="w-full">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Welcome back</h1>
                <p class="mt-2 text-gray-600">Sign in to your account to continue</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6" novalidate>
                @csrf

                <!-- Email -->
                <x-input
                    type="email"
                    name="email"
                    id="email"
                    label="Email address"
                    placeholder="you@example.com"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    :error="$errors->first('email')"
                    autofocus
                />

                <!-- Password -->
                <div class="relative">
                    <x-input
                        type="password"
                        name="password"
                        id="password"
                        label="Password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                        :error="$errors->first('password')"
                    />

                    <button
                        type="button"
                        x-data="{ show: false }"
                        @click="show = !show"
                        class="absolute right-3 top-[38px] text-gray-400 hover:text-gray-600 transition-colors"
                        aria-label="Toggle password visibility"
                    >
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span class="text-sm text-gray-700">Remember me</span>
                    </label>

                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">Forgot password?</a>
                </div>

                <!-- Submit -->
                <x-button type="submit" variant="primary" size="lg" fullWidth class="py-3">
                    Sign in
                </x-button>
            </form>

            <!-- Error message -->
            @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
                <x-alert variant="error" dismissible class="mt-4" title="Sign in failed">
                    {{ $errors->first() }}
                </x-alert>
            @endif

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="#" class="ml-1 text-indigo-600 hover:text-indigo-500 font-medium">Contact your administrator</a>
                </p>
            </div>
        </x-card>

        <!-- Version info -->
        <p class="mt-6 text-center text-xs text-gray-400">
            {{ config('app.name', 'URL Shortener') }} v1.0.0
        </p>
    </div>
</div>
@endsection