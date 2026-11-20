<?php

namespace App\Http\Requests;

use App\Models\Invitation;
use App\Models\User;
use App\Traits\HasInvitableRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreInvitationRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'in:'.implode(',', $availableRoles)],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'The email address must not exceed 255 characters.',
            'role.required' => 'The role is required.',
            'role.in' => 'The selected role is invalid.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::user();
            $email = $this->input('email');

            if ($email) {
                $existingInvitation = Invitation::where('email', $email)
                    ->where('company_id', $user->company_id)
                    ->where('accepted_at', null)
                    ->where('expires_at', '>', now())
                    ->first();

                if ($existingInvitation) {
                    $validator->errors()->add('email', 'An active invitation already exists for this email.');
                }

                $existingUser = User::where('email', $email)->first();
                if ($existingUser) {
                    $validator->errors()->add('email', 'A user with this email already exists.');
                }
            }
        });
    }
}
