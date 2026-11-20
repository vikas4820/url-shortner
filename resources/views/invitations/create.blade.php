@extends('layouts.app')

@section('title', ' - Send Invitation')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Send Invitation</h1>
        <p class="mt-1 text-gray-600">Invite a new user to join your company</p>
    </div>

    <!-- Form -->
    <x-card variant="elevated" padding="lg">
        <form method="POST" action="{{ route('invitations.store') }}" class="space-y-6" novalidate>
            @csrf

            <!-- Email -->
            <x-input
                type="email"
                name="email"
                id="email"
                label="Email Address"
                placeholder="newuser@company.com"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                :error="$errors->first('email')"
                autofocus
            />

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Role <span class="text-red-500">*</span>
                </label>

                <x-select
                    name="role"
                    id="role"
                    :options="$availableRoles"
                    value="{{ old('role') }}"
                    required
                    :error="$errors->first('role')"
                    placeholder="Select a role"
                />

                @if ($errors->has('role'))
                    <p class="mt-1.5 text-sm text-red-600">{{ $errors->first('role') }}</p>
                @endif
            </div>

            <!-- Role Descriptions -->
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Role Permissions</p>
                <div class="space-y-2 text-xs text-gray-600">
                    <div class="flex items-start gap-2">
                        <x-badge variant="primary" size="xs">Admin</x-badge>
                        <span>Can manage users, invite Sales/Member, view cross-company URLs</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-badge variant="success" size="xs">Manager</x-badge>
                        <span>Can view all company URLs, manage team URLs</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-badge variant="warning" size="xs">Sales</x-badge>
                        <span>Can create short URLs, view other team members' URLs</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-badge variant="default" size="xs">Member</x-badge>
                        <span>Can view other team members' URLs</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('invitations.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <x-button type="submit" variant="primary" size="md">
                    Send Invitation
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection