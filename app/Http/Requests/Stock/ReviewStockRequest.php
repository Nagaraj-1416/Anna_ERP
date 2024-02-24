<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStockRequest extends FormRequest
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
            'staff_id' => 'required',
            'actual_qty' => 'required|numeric|min:0'
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        return $messages;
    }

}
