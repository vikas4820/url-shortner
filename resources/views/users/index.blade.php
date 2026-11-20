@extends('layouts.app')

@section('title', ' - Users')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users</h1>
            <p class="mt-1 text-gray-600">Manage users in your company</p>
        </div>

        <x-button variant="primary" icon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'></path></svg>" href="{{ route('users.create') }}">
            Add User
        </x-button>
    </div>

    <!-- Users Table -->
    <x-card variant="default" padding="none">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <x-avatar :name="$user->name" size="sm" />
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                        @if ($user->id === auth()->id())
                                            <span class="text-xs text-gray-500">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $user->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <x-badge
                                    variant="@switch($user->getRoleNames()->first())
                                        ['super_admin' => 'purple', 'admin' => 'primary', 'manager' => 'success', 'sales' => 'warning', 'member' => 'default', default => 'default']
                                    @endswitch"
                                    size="md"
                                >
                                    {{ ucfirst($user->getRoleNames()->first() ?? 'none') }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4">
                                <x-badge
                                    variant="@if($user->email_verified_at) success @else warning @endif"
                                    size="sm"
                                    :dot="true"
                                >
                                    @if($user->email_verified_at) Verified @else Unverified @endif
                                </x-badge>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.show', $user) }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">View</a>

                                    @can('update', $user)
                                        <a href="{{ route('users.edit', $user) }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Edit</a>
                                    @endcan

                                    @can('delete', $user)
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-500 font-medium">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-lg font-medium text-gray-900">No users found</p>
                                        <p class="text-gray-500">Get started by adding a new user</p>
                                    </div>
                                    <x-button variant="primary" icon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'></path></svg>" href="{{ route('users.create') }}">
                                        Add User
                                    </x-button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                <x-pagination :paginator="$users" />
            </div>
        @endif
    </x-card>
</div>
@endsection