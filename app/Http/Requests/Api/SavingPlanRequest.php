<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SavingPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|unique:portfolios|max:50',
            'description' => 'required|max:191',
            'target_value' => 'required|numeric|min:0.01',
            'target_date' => 'required|date'
        ];
    }
}
