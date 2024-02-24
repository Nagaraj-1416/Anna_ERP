<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class CommissionStoreRequest extends FormRequest
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
            'sales_target' => 'required',
            'special_target' => 'required',
            'customer_visited_rate' => 'required',
            'product_sold_count' => 'required',
            'product_sold_rate' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'customer_visited_rate.required' => 'Rate is required.',
            'product_sold_count.required' => 'Count is required.',
            'product_sold_rate.required' => 'Rate is required.',
        ];
        return $messages;
    }

}
