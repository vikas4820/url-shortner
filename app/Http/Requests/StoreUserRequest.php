<?php

namespace App\Http\Requests;

use App\Traits\HasInvitableRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    use HasInvitableRoles;

    public function authorize(): bool
    {
        return Auth::user()->can('manage_users');
    }

    public function rules(): array
    {
        $user = Auth::user();
        $availableRoles = $this->getInvitableRoles($user);

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'role.required' => 'The role is required.',
            'role.in' => 'The selected role is invalid.',
        ];
    }
}
