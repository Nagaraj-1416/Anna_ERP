<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class HandoverUpdateRequest extends FormRequest
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
            'cash_sales' => 'numeric',
            'cheque_sales' => 'numeric',
            'deposit_sales' => 'numeric',
            'card_sales' => 'numeric',
            'credit_sales' => 'numeric',
            'old_cash_sales' => 'numeric',
            'old_cheque_sales' => 'numeric',
            'old_deposit_sales' => 'numeric',
            'old_card_sales' => 'numeric',
            'old_credit_sales' => 'numeric',
            'shortage' => 'numeric',
            'excess' => 'numeric',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        return $messages;
    }

}
