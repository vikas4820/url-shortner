@extends('layouts.app')

@section('title', ' - Access Denied')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900">403</h1>
            <p class="mt-2 text-xl font-semibold text-gray-700">Access Denied</p>
            <p class="mt-4 text-gray-600">You don't have permission to access this resource.</p>

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