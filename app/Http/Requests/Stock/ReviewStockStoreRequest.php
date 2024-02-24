<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStockStoreRequest extends FormRequest
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
            'notes' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'staff_id.required' => 'The staff field is required.',
            'notes.required' => 'The review notes field is required.'
        ];
        return $messages;
    }

}
