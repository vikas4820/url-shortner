@extends('layouts.app')

@section('title', ' - Invitation Details')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Invitation Details</h1>
            <p class="mt-1 text-gray-600">{{ $invitation->email }}</p>
        </div>

        <a href="{{ route('invitations.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            Back to Invitations
        </a>
    </div>

    <!-- Invitation Card -->
    <x-card variant="elevated" padding="lg">
        <div class="flex items-center justify-between mb-8 pb-8 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-900">{{ $invitation->email }}</p>
                    <p class="text-gray-500">Invitation #{{ $invitation->id }}</p>
                </div>
            </div>

            <x-badge
                variant="@switch(true)
                    [$invitation->isAccepted() => 'success', $invitation->isExpired() => 'danger', default => 'warning']
                @endswitch"
                size="lg"
                :dot="true"
            >
                @if($invitation->isAccepted()) Accepted @elseif($invitation->isExpired()) Expired @else Pending @endif
            </x-badge>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
            <div>
                <p class="text-sm font-medium text-gray-500">Role</p>
                <p class="mt-1 text-gray-900">
                    <x-badge
                        variant="@switch($invitation->role)
                            ['admin' => 'primary', 'manager' => 'success', 'sales' => 'warning', default => 'default']
                        @endswitch"
                        size="md"
                    >
                        {{ ucfirst($invitation->role) }}
                    </x-badge>
                </p>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">Company</p>
                <p class="mt-1 text-gray-900">{{ $invitation->company->name }}</p>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">Invited By</p>
                <p class="mt-1 text-gray-900">{{ $invitation->invitedBy->name ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">Created</p>
                <p class="mt-1 text-gray-900">{{ $invitation->created_at->format('F d, Y \a\t g:i A') }}</p>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">Expires</p>
                <p class="mt-1 text-gray-900">{{ $invitation->expires_at->format('F d, Y \a\t g:i A') }}</p>
                @if (!$invitation->isAccepted() && !$invitation->isExpired())
                    <p class="mt-1 text-sm text-gray-500">{{ $invitation->expires_at->diffForHumans() }}</p>
                @endif
            </div>

            @if ($invitation->accepted_at)
                <div>
                    <p class="text-sm font-medium text-gray-500">Accepted</p>
                    <p class="mt-1 text-gray-900">{{ $invitation->accepted_at->format('F d, Y \a\t g:i A') }}</p>
                </div>
            @endif
        </div>

        <!-- Invitation Link -->
        <div class="p-4 bg-gray-50 rounded-xl">
            <label class="block text-sm font-medium text-gray-700 mb-2">Invitation Link</label>
            <div class="flex gap-2">
                <input
                    type="text"
                    value="{{ route('invitations.accept', ['token' => $invitation->token]) }}"
                    readonly
                    class="flex-1 px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg font-mono"
                    onclick="this.select(); navigator.clipboard.writeText(this.value)"
                    title="Click to copy"
                >
                <x-button variant="secondary" size="sm" type="button" onclick="navigator.clipboard.writeText('{{ route('invitations.accept', ['token' => $invitation->token]) }}'); this.innerHTML='Copied!'; setTimeout(() => this.innerHTML='Copy', 2000)">
                    Copy
                </x-button>
            </div>
            <p class="mt-2 text-xs text-gray-500">Share this link with the invitee. It expires on {{ $invitation->expires_at->format('F d, Y') }}.</p>
        </div>
    </x-card>
</div>
@endsection