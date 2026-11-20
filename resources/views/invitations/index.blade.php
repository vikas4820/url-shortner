@extends('layouts.app')

@section('title', ' - Invitations')

@section('content')
<div class="space-y-6">
    <!-- Success Message with Invitation Link -->
    @if (session('success'))
        <x-alert variant="success" dismissible class="mb-0" title="Invitation Created">
            {{ session('success') }}
            @if (session('invitation_url'))
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invitation Link (click to copy):</label>
                    <div class="flex gap-2">
                        <input
                            type="text"
                            value="{{ session('invitation_url') }}"
                            readonly
                            class="flex-1 px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg font-mono"
                            onclick="this.select(); navigator.clipboard.writeText(this.value)"
                            title="Click to copy"
                        >
                        <x-button variant="secondary" size="sm" type="button" onclick="navigator.clipboard.writeText('{{ session('invitation_url') }}'); this.innerHTML='Copied!'; setTimeout(() => this.innerHTML='Copy', 2000)">
                            Copy
                        </x-button>
                    </div>
                </div>
            @endif
        </x-alert>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Invitations</h1>
            <p class="mt-1 text-gray-600">Manage invitations for your company</p>
        </div>

        <x-button variant="primary" icon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'></path></svg>" href="{{ route('invitations.create') }}">
            Send Invitation
        </x-button>
    </div>

<!-- Invitations Table -->
    <x-card variant="default" padding="none">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap hidden sm:table-cell">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap hidden md:table-cell">Invited By</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap hidden lg:table-cell">Expires</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach ($invitations as $invitation)
                        @php
                            $roleVariants = ['admin' => 'primary', 'manager' => 'success', 'sales' => 'warning', 'member' => 'default'];
                            $roleVariant = $roleVariants[$invitation->role] ?? 'default';
                            $status = $invitation->isAccepted() ? 'accepted' : ($invitation->isExpired() ? 'expired' : 'pending');
                            $statusVariants = ['accepted' => 'success', 'expired' => 'danger', 'pending' => 'warning'];
                            $statusLabels = ['accepted' => 'Accepted', 'expired' => 'Expired', 'pending' => 'Pending'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900 truncate max-w-xs" title="{{ $invitation->email }}">{{ $invitation->email }}</p>
                            </td>
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <x-badge
                                    variant="{{ $roleVariant }}"
                                    size="sm"
                                >
                                    {{ ucfirst($invitation->role) }}
                                </x-badge>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                <p class="text-sm text-gray-500 truncate max-w-xs">{{ $invitation->invitedBy->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <x-badge
                                    variant="{{ $statusVariants[$status] }}"
                                    size="sm"
                                    :dot="true"
                                >
                                    {{ $statusLabels[$status] }}
                                </x-badge>
                            </td>
                            <td class="px-4 py-3 hidden lg:table-cell">
                                <div>
                                    <p class="text-sm text-gray-500">{{ $invitation->expires_at->format('M d, Y H:i') }}</p>
                                    @if (!$invitation->isAccepted() && !$invitation->isExpired())
                                        <p class="text-xs text-gray-400">{{ $invitation->expires_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('invitations.show', $invitation) }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium hidden sm:inline-block">View</a>
                                    <a href="{{ route('invitations.show', $invitation) }}" class="text-indigo-600 hover:text-indigo-500 p-2 sm:hidden" title="View" aria-label="View invitation">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    @if (!$invitation->isAccepted() && !$invitation->isExpired())
                                        <div class="relative">
                                            <button
                                                x-data="{ open: false }"
                                                @click="open = !open"
                                                @click.outside="open = false"
                                                class="text-sm text-gray-600 hover:text-gray-900 font-medium hidden sm:inline-block"
                                            >
                                                Copy Link
                                            </button>
                                            <button
                                                x-data="{ open: false }"
                                                @click="open = !open"
                                                @click.outside="open = false"
                                                class="text-gray-600 hover:text-gray-900 p-2 sm:hidden"
                                                aria-label="Copy link"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2h-2M8 5a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2h-2"></path></svg>
                                            </button>
                                            <div
                                                x-show="open"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95"
                                                class="absolute right-0 top-full mt-1 w-72 bg-white rounded-lg shadow-lg border border-gray-200 divide-y divide-gray-100 z-50"
                                            >
                                                <div class="p-3">
                                                    <input
                                                        type="text"
                                                        value="{{ route('invitations.accept', ['token' => $invitation->token]) }}"
                                                        readonly
                                                        class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-lg font-mono"
                                                        onclick="this.select(); navigator.clipboard.writeText(this.value)"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if($invitations->isEmpty())
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-lg font-medium text-gray-900">No invitations found</p>
                                        <p class="text-gray-500">Send your first invitation to get started</p>
                                    </div>
                                    <x-button variant="primary" icon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2v10a2 2 0 002 2z'></path></svg>" href="{{ route('invitations.create') }}">
                                        Send Invitation
                                    </x-button>
                                </div>
                            </td>
                        </tr>
@endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($invitations->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                <x-pagination :paginator="$invitations" />
            </div>
        @endif
    </x-card>
</div>
@endsection