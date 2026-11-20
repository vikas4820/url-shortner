<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreShortUrlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->can('create_short_url');
    }

    public function rules(): array
    {
        return [
            'original_url' => ['required', 'url', 'max:2048', 'regex:/^https?:\/\//i'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ];
    }

    public function messages(): array
    {
        return [
            'original_url.required' => 'The original URL is required.',
            'original_url.url' => 'Please provide a valid URL.',
            'original_url.max' => 'The URL must not exceed 2048 characters.',
            'original_url.regex' => 'The URL must use HTTP or HTTPS scheme.',
            'expires_at.date' => 'The expiration date must be a valid date.',
            'expires_at.after' => 'The expiration date must be in the future.',
        ];
    }
}
