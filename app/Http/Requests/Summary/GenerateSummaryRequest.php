<?php

namespace App\Http\Requests\Summary;

use Illuminate\Foundation\Http\FormRequest;

class GenerateSummaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'summary_type' => ['nullable', 'string', 'in:standard,contract,academic,business'],
            'language' => ['nullable', 'string', 'size:2'],
        ];
    }

    public function messages(): array
    {
        return [
            'summary_type.in' => 'Invalid summary type. Choose from: standard, contract, academic, business.',
            'language.size' => 'Language code must be 2 characters (e.g., en, es, fr).',
        ];
    }
}

