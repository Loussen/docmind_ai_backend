<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AppleAuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identity_token' => ['required', 'string'],
            'authorization_code' => ['required', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'full_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'identity_token.required' => 'Apple identity token is required.',
            'authorization_code.required' => 'Apple authorization code is required.',
        ];
    }
}

