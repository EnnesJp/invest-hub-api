<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'portfolio_id' => 'required|exists:portfolios,id',
            'name' => 'required|unique:assets|max:50',
            'value' => 'required|numeric|min:0.00',
            'acquisition_date' => 'required|date|date_format:Y-m-d',
            'quantity' => 'numeric|min:0.00',
            'liquidity_days' => 'numeric|min:0',
            'liquidity_date' => 'date|date_format:Y-m-d',
            'income_tax' => 'numeric|min:0.00',
        ];
    }
}
