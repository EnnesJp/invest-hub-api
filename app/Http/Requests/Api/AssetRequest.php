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
        $rules = [
            'user_id' => 'required|exists:users,id',
            'portfolio_id' => 'required|exists:portfolios,id',
            'saving_plan_id' => 'exists:saving_plans,id|nullable',
            'name' => 'required|unique:assets|max:50',
            'value' => 'required|numeric|min:0.00',
            'acquisition_date' => 'required|date|date_format:Y-m-d',
            'quantity' => 'numeric|min:0.00|nullable',
            'liquidity_days' => 'numeric|min:0|nullable',
            'liquidity_date' => 'date|date_format:Y-m-d|nullable',
            'income_tax' => 'numeric|min:0.00',
        ];

        if ($this->isMethod('PUT')) {
            $rules['user_id'] = 'exists:users,id';
            $rules['portfolio_id'] = 'exists:portfolios,id';
            $rules['name'] = 'max:50|unique:assets,name,' . $this->route('asset')->id;
            $rules['value'] = 'numeric|min:0.00';
            $rules['acquisition_date'] = 'date|date_format:Y-m-d';
        }

        return $rules;
    }
}
