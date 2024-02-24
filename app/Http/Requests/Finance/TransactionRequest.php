<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
        $rules =  [
            'type' => 'required|in:Deposit,Withdrawal',
            'date' => 'required|date',
            'tx_type_id' => 'required|exists:transaction_types,id',

            'account_id' => 'required|array',
            'account_id.*' => 'required|exists:accounts,id',
            'debit' => 'required|array',
            'debit.*' => 'required|numeric',
            'credit' => 'required|array|sum_equal:debit',
            'credit.*' => 'required|numeric',
        ];
        if ($this->input('customer_id')){
            $rules['customer_id'] = 'exists:customers,id';
        }
        if ($this->input('supplier_id')){
            $rules['supplier_id'] = 'exists:suppliers,id';
        }
        return $rules;
    }
}
