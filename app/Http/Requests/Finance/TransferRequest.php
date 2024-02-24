<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'sender' => 'required',
            'receiver' => 'required',
        ];

        if ($this->input('transfer_mode') == 'ByHand'){
            $rules['handed_order_to'] = 'required';
        }

        if ($this->input('transfer_mode') == 'DepositedToBank'){
            $rules['deposited_to'] = 'required';
        }

        return $rules;
    }
}
