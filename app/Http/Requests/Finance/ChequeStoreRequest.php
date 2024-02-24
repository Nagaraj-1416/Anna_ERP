<?php

namespace App\Http\Requests\Finance;

use App\AccountGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ChequeStoreRequest extends FormRequest
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
            'registered_date' => 'required',
            'company_id' => 'required',
            'customer_id' => 'required',
            'cheque_date' => 'required|date',
            'cheque_no' => 'required',
            'amount' => 'required',
            'cheque_type' => 'required',
            'bank_id' => 'required',
            'deposited_to' => 'required'
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'company_id.required' => 'The company field is required.',
            'customer_id.required' => 'The customer field is required.',
            'cheque_type.required' => 'The cheque type field is required.',
            'bank_id.required' => 'The bank field is required.',
            'deposited_to.required' => 'The deposited to field is required.'
        ];
        return $messages;
    }

}
