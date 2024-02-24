<?php

namespace App\Http\Requests\Api\Sales;

use App\Invoice;
use App\InvoicePayment;
use Illuminate\Foundation\Http\FormRequest;

class ChequePaymentStoreRequest extends FormRequest
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
            'payment_type' => 'required|in:"Advanced","Partial Payment", "Final Payment"',
            'payment_mode' => 'required|in:"Cash","Cheque","Direct Deposit","Credit Card"',
        ];

        if ($this->input('payment_mode') == 'Cheque'){
            $rules['cheque_no'] = 'required';
            $rules['cheque_date'] = 'required|date';
            $rules['cheque_type'] = 'required|in:"Own","Third Party"';
            $rules['bank_id'] = 'required|exists:banks,id';
         }

        if ($this->input('payment_mode') == 'Direct Deposit'){
            $rules['account_no'] = 'required';
            $rules['deposited_date'] = 'required|date';
            $rules['bank_id'] = 'required|exists:banks,id';
         }

        if ($this->input('payment_mode') == 'Credit Card'){
            $rules['card_holder_name'] = 'required';
            $rules['card_no'] = 'required|min:13|max:19|string|regex:/^[0-9\s]+$/';
            $rules['expiry_date'] = 'required|date|after:today';
            $rules['bank_id'] = 'required|exists:banks,id';
         }
        return $rules;
    }

    public function messages()
    {
        $messages = [];

        return $messages;
    }

}
