<?php

namespace App\Http\Requests;

use App\Traits\HasInvitableRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    use HasInvitableRoles;

    public function authorize(): bool
    {
        $user = $this->route('user');
        $currentUser = Auth::user();

        if (! $currentUser->can('manage_users')) {
            return false;
        }

        if ($user->company_id !== $currentUser->company_id && ! $currentUser->hasRole('super_admin')) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $currentUser = Auth::user();
        $availableRoles = $this->getInvitableRoles($currentUser);

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:'.implode(',', $availableRoles)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name is required.',
            'name.max' => 'The name must not exceed 255 characters.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'The email address must not exceed 255 characters.',
            'email.unique' => 'A user with this email already exists.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'role.required' => 'The role is required.',
            'role.in' => 'The selected role is invalid.',
        ];
    }
}
