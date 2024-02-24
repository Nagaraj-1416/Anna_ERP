<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class ReturnedChequePaymentStoreRequest extends FormRequest
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
            'payment' => 'required|numeric',
            'deposited_to' => 'required|exists:accounts,id',
            'payment_date' => 'required|date',
            'payment_type' => 'required',
            'payment_mode' => 'required',
        ];

        if ($this->input('payment_mode') == 'Cheque'){
            $rules['cheque_no'] = 'required';
            $rules['cheque_date'] = 'required|date';
            $rules['cheque_bank_id'] = 'required|exists:banks,id';
            $rules['cheque_type'] = 'required';
        }

        if ($this->input('payment_mode') == 'Direct Deposit'){
            $rules['account_no'] = 'required';
            $rules['deposited_date'] = 'required|date';
            $rules['dd_bank_id'] = 'required|exists:banks,id';
        }

        if ($this->get('payment_mode') == 'Credit Card') {
            $rules['card_holder_name'] = 'required';
            $rules['card_no'] = 'required|min:16';
            $rules['expiry_date'] = 'required';
            $rules['cc_bank_id'] = 'required';
        }

        return $rules;
    }
}
