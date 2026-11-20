@extends('layouts.app')

@section('title', ' - Short URLs')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Short URLs</h1>
            <p class="mt-1 text-gray-600">Manage your company's short URLs</p>
        </div>

        @can('create', App\Models\ShortUrl::class)
            <x-button variant="primary" icon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'></path></svg>" href="{{ route('short-urls.create') }}">
                Create Short URL
            </x-button>
        @endcan
    </div>

    <!-- Short URLs Table -->
    <x-card variant="default" padding="none">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Short Code</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Original URL</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Clicks</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Expires</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($shortUrls as $shortUrl)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <code class="px-2 py-1 bg-gray-100 rounded-lg text-sm font-mono text-gray-900">{{ $shortUrl->short_code }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="text-sm text-gray-900 truncate" title="{{ $shortUrl->original_url }}">{{ $shortUrl->original_url }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-500">{{ $shortUrl->user->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-500">{{ $shortUrl->company->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-medium text-gray-900">{{ number_format($shortUrl->clicks) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($shortUrl->expires_at)
                                    <div>
                                        <p class="text-sm text-gray-900">{{ $shortUrl->expires_at->format('M d, Y H:i') }}</p>
                                        @if (!$shortUrl->isExpired())
                                            <p class="text-xs text-gray-500">{{ $shortUrl->expires_at->diffForHumans() }}</p>
                                        @else
                                            <p class="text-xs text-red-500">Expired</p>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Never</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('short-urls.show', $shortUrl) }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">View</a>

                                    @can('redirect', $shortUrl)
                                        <a
                                            href="{{ route('short-urls.redirect', ['shortCode' => $shortUrl->short_code]) }}"
                                            target="_blank"
                                            class="px-3 py-1.5 text-sm font-medium text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors"
                                        >
                                            Open
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    <div>
                                        <p class="text-lg font-medium text-gray-900">No short URLs found.</p>
                                        <p class="text-gray-500">Create your first short URL to get started</p>
                                    </div>
                                    @can('create', App\Models\ShortUrl::class)
                                        <x-button variant="primary" icon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'></path></svg>" href="{{ route('short-urls.create') }}">
                                            Create Short URL
                                        </x-button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($shortUrls->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                <x-pagination :paginator="$shortUrls" />
            </div>
        @endif
    </x-card>
</div>
@endsection