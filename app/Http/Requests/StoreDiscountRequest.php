<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreDiscountRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|string|in:item,category',
            'discounted_id' => [
                'required',
                'integer',
                Rule::when($this->type === 'item', ['exists:items,id']),
                Rule::when($this->type === 'category', ['exists:categories,id']),
            ],
            'value_type' => 'required|string|in:percentage,fixed',
            'min_quantity' => 'nullable|integer|min:1|required_without:min_price',
            'min_price' => 'nullable|numeric|min:1|required_without:min_quantity',
            'value' => [
                'required',
                'numeric',
                'min:0',
                Rule::when($this->value_type === 'percentage', ['max:100']),
            ],
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
        ];
    }
}
