@extends('layouts.app')

@section('title', ' - Edit User')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
            <p class="mt-1 text-gray-600">{{ $user->name }}</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('users.show', $user) }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Back
            </a>
        </div>
    </div>

    <!-- Form -->
    <x-card variant="elevated" padding="lg">
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6" novalidate>
            @csrf @method('PUT')

            <!-- Name -->
            <x-input
                name="name"
                id="name"
                label="Full Name"
                placeholder="John Doe"
                value="{{ old('name', $user->name) }}"
                required
                autocomplete="name"
                :error="$errors->first('name')"
                autofocus
            />

            <!-- Email -->
            <x-input
                type="email"
                name="email"
                id="email"
                label="Email Address"
                placeholder="john@company.com"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="email"
                :error="$errors->first('email')"
            />

            <!-- Password (optional) -->
            <x-input
                type="password"
                name="password"
                id="password"
                label="New Password"
                placeholder="Leave blank to keep current password"
                autocomplete="new-password"
                :error="$errors->first('password')"
                hint="Must be at least 8 characters. Leave blank to keep current password."
            />

            <!-- Confirm Password -->
            <x-input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                label="Confirm New Password"
                placeholder="••••••••"
                autocomplete="new-password"
                :error="$errors->first('password_confirmation')"
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
                    value="{{ old('role', $userRoles[0] ?? '') }}"
                    required
                    :error="$errors->first('role')"
                    placeholder="Select a role"
                />

                @if ($errors->has('role'))
                    <p class="mt-1.5 text-sm text-red-600">{{ $errors->first('role') }}</p>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('users.show', $user) }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <x-button type="submit" variant="primary" size="md">
                    Update User
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection