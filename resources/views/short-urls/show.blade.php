@extends('layouts.app')

@section('title', ' - {{ $shortUrl->short_code }}')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Short URL Details</h1>
            <p class="mt-1 text-gray-600">{{ $shortUrl->short_code }}</p>
        </div>

        <div class="flex flex-wrap gap-2">
            @can('redirect', $shortUrl)
                <a
                    href="{{ route('short-urls.redirect', ['shortCode' => $shortUrl->short_code]) }}"
                    target="_blank"
                    class="px-4 py-2.5 text-sm font-medium text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    Open Link
                </a>
            @endcan

            <a href="{{ route('short-urls.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Back to Short URLs
            </a>
        </div>
    </div>

    <!-- Short URL Card -->
    <x-card variant="elevated" padding="lg">
        <div class="flex items-start justify-between mb-8 pb-8 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <div>
                    <code class="text-2xl font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded-lg font-mono">{{ $shortUrl->short_code }}</code>
                    <p class="mt-1 text-gray-500">Created {{ $shortUrl->created_at->diffForHumans() }}</p>
                </div>
            </div>

            @if ($shortUrl->isExpired())
                <x-badge variant="danger" size="lg" dot>Expired</x-badge>
            @else
                <x-badge variant="success" size="lg" dot>Active</x-badge>
            @endif
        </div>

        <div class="space-y-6">
            <!-- Short URL Link -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">Short URL</label>
                <div class="flex gap-2">
                    <input
                        type="text"
                        value="{{ route('short-urls.redirect', ['shortCode' => $shortUrl->short_code]) }}"
                        readonly
                        class="flex-1 px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg font-mono"
                        onclick="this.select(); navigator.clipboard.writeText(this.value)"
                        title="Click to copy"
                    >
                    <x-button variant="secondary" size="sm" type="button" onclick="navigator.clipboard.writeText('{{ route('short-urls.redirect', ['shortCode' => $shortUrl->short_code]) }}'); this.innerHTML='Copied!'; setTimeout(() => this.innerHTML='Copy', 2000)">
                        Copy
                    </x-button>
                </div>
            </div>

            <!-- Original URL -->
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">Original URL</label>
                <a href="{{ $shortUrl->original_url }}" target="_blank" class="block p-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                    <p class="text-sm text-gray-900 truncate" title="{{ $shortUrl->original_url }}">{{ $shortUrl->original_url }}</p>
                    <p class="text-xs text-gray-500 mt-1">Click to open in new tab</p>
                </a>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Created By</p>
                    <p class="mt-1 text-gray-900">{{ $shortUrl->user->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Company</p>
                    <p class="mt-1 text-gray-900">{{ $shortUrl->company->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Total Clicks</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($shortUrl->clicks) }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Expires</p>
                    @if ($shortUrl->expires_at)
                        <p class="mt-1 text-gray-900">{{ $shortUrl->expires_at->format('F d, Y \a\t g:i A') }}</p>
                        @if (!$shortUrl->isExpired())
                            <p class="mt-1 text-sm text-gray-500">{{ $shortUrl->expires_at->diffForHumans() }}</p>
                        @else
                            <p class="mt-1 text-sm text-red-500">Expired</p>
                        @endif
                    @else
                        <p class="mt-1 text-gray-500">Never</p>
                    @endif
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Created</p>
                    <p class="mt-1 text-gray-900">{{ $shortUrl->created_at->format('F d, Y \a\t g:i A') }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Short URL ID</p>
                    <p class="mt-1 text-gray-900 font-mono text-sm">#{{ $shortUrl->id }}</p>
                </div>
            </div>
        </div>
    </x-card>
</div>
@endsection