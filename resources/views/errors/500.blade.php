@extends('layouts.app')

@section('title', ' - Server Error')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md text-center">
        <!-- Logo -->
        <div class="mb-8">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2" aria-label="{{ config('app.name', 'URL Shortener') }} Home">
                <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center mx-auto">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-gray-900">{{ config('app.name', 'URL Shortener') }}</span>
            </a>
        </div>

        <x-card variant="elevated" padding="lg">
            <div class="mx-auto w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900">500</h1>
            <p class="mt-2 text-xl font-semibold text-gray-700">Server Error</p>
            <p class="mt-4 text-gray-600">Something went wrong on our end. We've been notified and are working to fix it.</p>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto">
                    <x-button variant="primary" size="lg" fullWidth>
                        Try Again
                    </x-button>
                </a>
                <a href="{{ url()->previous() }}" class="w-full sm:w-auto">
                    <x-button variant="secondary" size="lg" fullWidth>
                        Go Back
                    </x-button>
                </a>
            </div>

            @if (app()->environment('local'))
                <div class="mt-6 p-4 bg-gray-100 rounded-lg text-left">
                    <p class="text-xs font-medium text-gray-500 mb-2">Debug Information (Local Only)</p>
                    <pre class="text-xs text-gray-600 overflow-auto max-h-40">{{ $exception ?? 'No exception details available' }}</pre>
                </div>
            @endif
        </x-card>

        <p class="mt-6 text-center text-xs text-gray-400">
            {{ config('app.name', 'URL Shortener') }} v1.0.0
        </p>
    </div>
</div>
@endsection