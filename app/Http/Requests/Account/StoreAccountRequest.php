<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'initial_balance' => ['nullable', 'numeric'],
            'is_archived' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'initial_balance' => $this->input('initial_balance', 0),
            'is_archived' => $this->boolean('is_archived', false),
        ]);
    }
}
