<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'asset_id' => 'required|exists:assets,id',
            'description' => 'required|max:191',
            'date' => 'required|date|before:tomorrow|date_format:Y-m-d',
            'type' => 'required|in:debit,credit',
            'value' => 'required|numeric|min:0.00'
        ];
    }
}
