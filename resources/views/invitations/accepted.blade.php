@extends('layouts.app')

@section('title', ' - Invitation Already Accepted')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md text-center">
        <!-- Logo -->
        <div class="mb-8">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2" aria-label="{{ config('app.name', 'URL Shortener') }} Home">
                <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center mx-auto">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-gray-900">{{ config('app.name', 'URL Shortener') }}</span>
            </a>
        </div>

        <x-card variant="elevated" padding="lg">
            <div class="mx-auto w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900">Invitation Already Accepted</h1>
            <p class="mt-3 text-gray-600">This invitation has already been accepted and an account has been created.</p>

            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">
                    You can now sign in with your credentials.
                </p>
            </div>

            <a href="{{ route('login') }}" class="mt-6 block w-full">
                <x-button variant="primary" size="lg" fullWidth>
                    Go to Login
                </x-button>
            </a>
        </x-card>

        <p class="mt-6 text-center text-xs text-gray-400">
            {{ config('app.name', 'URL Shortener') }} v1.0.0
        </p>
    </div>
</div>
@endsection