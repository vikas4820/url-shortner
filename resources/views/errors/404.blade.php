@extends('layouts.app')

@section('title', ' - Page Not Found')

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
            <div class="mx-auto w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center mb-6">
                <span class="text-3xl font-bold text-indigo-600">404</span>
            </div>

            <h1 class="text-2xl font-bold text-gray-900">Page Not Found</h1>
            <p class="mt-4 text-gray-600">Sorry, the page you're looking for doesn't exist or has been moved.</p>

            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto">
                    <x-button variant="primary" size="lg" fullWidth>
                        Go to Dashboard
                    </x-button>
                </a>
                <a href="{{ url()->previous() }}" class="w-full sm:w-auto">
                    <x-button variant="secondary" size="lg" fullWidth>
                        Go Back
                    </x-button>
                </a>
            </div>
        </x-card>

        <p class="mt-6 text-center text-xs text-gray-400">
            {{ config('app.name', 'URL Shortener') }} v1.0.0
        </p>
    </div>
</div>
@endsection