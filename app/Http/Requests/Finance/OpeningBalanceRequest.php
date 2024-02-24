<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class OpeningBalanceRequest extends FormRequest
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
        return [
            'opening' => 'required',
            'opening_at' => 'required',
            'balance_type' => 'required',
            'references.*.reference_no' => 'required',
            'references.*.amount' => 'required',
        ];
    }


    public function messages()
    {
        $messages = [
            'references.*.reference_no.required' => 'The reference no field is required.',
            'references.*.amount.required' => 'The amount field is required.',
        ];
        return $messages;
    }
}
