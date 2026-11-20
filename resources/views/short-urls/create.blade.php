@extends('layouts.app')

@section('title', ' - Create Short URL')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Create Short URL</h1>
        <p class="mt-1 text-gray-600">Create a new shortened link for your company</p>
    </div>

    <!-- Form -->
    <x-card variant="elevated" padding="lg">
        <form method="POST" action="{{ route('short-urls.store') }}" class="space-y-6" novalidate>
            @csrf

            <!-- Original URL -->
            <x-input
                type="url"
                name="original_url"
                id="original_url"
                label="Original URL"
                placeholder="https://example.com/very/long/url/path"
                value="{{ old('original_url') }}"
                required
                autocomplete="url"
                :error="$errors->first('original_url')"
                autofocus
            />

            <!-- Expiration (Optional) -->
            <x-input
                type="datetime-local"
                name="expires_at"
                id="expires_at"
                label="Expiration Date (Optional)"
                value="{{ old('expires_at') }}"
                :error="$errors->first('expires_at')"
                hint="Leave empty for no expiration"
            />

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('short-urls.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <x-button type="submit" variant="primary" size="md">
                    Create Short URL
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection