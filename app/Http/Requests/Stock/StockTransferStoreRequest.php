<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StockTransferStoreRequest extends FormRequest
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
            'transfer_to' => 'required',
            'vehicle_id' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'transfer_to.required' => 'The transfer to field is required.',
            'vehicle_id.required' => 'The vehicle field is required.'
        ];
        return $messages;
    }

}
