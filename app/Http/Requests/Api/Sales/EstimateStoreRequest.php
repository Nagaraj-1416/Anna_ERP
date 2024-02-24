<?php

namespace App\Http\Requests\Api\Sales;

use Illuminate\Foundation\Http\FormRequest;

class EstimateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'business_type_id' => 'required|exists:business_types,id',
            'rep_id' => 'required|exists:reps,id',
            'customer_id' => 'required|exists:customers,id',
            'estimate_date' => 'required|date',
            'expiry_date' => 'required|date',
            'adjustment' => 'numeric',
            'discount_rate' => 'numeric',
            'discount_type' => 'in:Percentage,Amount',

            'order_items' => 'array|required',
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.store_id' => 'required|exists:stores,id',
            'order_items.*.quantity' => 'required|numeric',
            'order_items.*.rate' => 'required|numeric',
            'order_items.*.discount_rate' => 'required|numeric',
            'order_items.*.discount_type' => 'required|in:Percentage,Amount',
        ];


        return $rules;
    }

    public function messages()
    {
        $messages = [
            'customer_id.required' => 'The customer field is required.',
            'business_type_id.required' => 'The business type field is required.',
            'rep_id.required' => 'The Sales rep field is required.',
        ];
        return $messages;
    }
}
