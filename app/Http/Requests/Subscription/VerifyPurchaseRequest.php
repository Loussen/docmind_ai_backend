<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'string'],
            'transaction_id' => ['required', 'string'],
            'receipt_data' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product ID is required.',
            'transaction_id.required' => 'Transaction ID is required.',
            'receipt_data.required' => 'Receipt data is required.',
        ];
    }
}

