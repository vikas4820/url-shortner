@extends('layouts.app')

@section('title', ' - Accept Invitation')

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

        <!-- Invitation Card -->
        <x-card variant="elevated" padding="lg">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-indigo-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Accept Invitation</h1>
                <p class="mt-2 text-gray-600">
                    You've been invited to join <strong>{{ $invitation->company->name }}</strong> as a <strong>{{ ucfirst($invitation->role) }}</strong>.
                </p>
            </div>

            <form method="POST" action="{{ route('invitations.confirm.accept', ['token' => $invitation->token]) }}" class="space-y-6" novalidate>
                @csrf

                <!-- Name -->
                <x-input
                    name="name"
                    id="name"
                    label="Your Name"
                    placeholder="John Doe"
                    required
                    autocomplete="name"
                    :error="$errors->first('name')"
                    autofocus
                />

                <!-- Email (readonly) -->
                <x-input
                    type="email"
                    name="email"
                    id="email"
                    label="Email Address"
                    value="{{ $invitation->email }}"
                    readonly
                    required
                    autocomplete="email"
                />

                <!-- Password -->
                <x-input
                    type="password"
                    name="password"
                    id="password"
                    label="Password"
                    placeholder="••••••••"
                    required
                    min="8"
                    autocomplete="new-password"
                    :error="$errors->first('password')"
                    hint="Must be at least 8 characters"
                />

                <!-- Confirm Password -->
                <x-input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    label="Confirm Password"
                    placeholder="••••••••"
                    required
                    autocomplete="new-password"
                    :error="$errors->first('password_confirmation')"
                />

                <!-- Submit -->
                <x-button type="submit" variant="primary" size="lg" fullWidth class="py-3">
                    Accept Invitation
                </x-button>
            </form>

            <!-- Expiry Info -->
            <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-amber-800">Invitation Expires</p>
                        <p class="text-sm text-amber-700">This invitation expires on {{ $invitation->expires_at->format('F d, Y \a\t g:i A') }}.</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="ml-1 text-indigo-600 hover:text-indigo-500 font-medium">Sign in</a>
                </p>
            </div>
        </x-card>

        <p class="mt-6 text-center text-xs text-gray-400">
            {{ config('app.name', 'URL Shortener') }} v1.0.0
        </p>
    </div>
</div>
@endsection