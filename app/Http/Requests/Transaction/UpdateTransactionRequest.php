<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'date' => ['sometimes', 'date'],
            'type' => ['sometimes', 'in:income,expense'],
            'amount' => ['sometimes', 'numeric', 'gt:0'],
            'account_id' => [
                'sometimes',
                'integer',
                Rule::exists('accounts', 'id')->where('user_id', $userId),
            ],
            'category_id' => [
                'sometimes',
                'integer',
                Rule::exists('categories', 'id')->where('user_id', $userId),
            ],
            'note' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
