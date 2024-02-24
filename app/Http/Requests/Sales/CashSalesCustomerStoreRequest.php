<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class CashSalesCustomerStoreRequest extends FormRequest
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
            'display_name' => 'required',
            'mobile' => 'required',
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'mobile.required' => 'The mobile no field is required.'
        ];
        return $messages;
    }
}
