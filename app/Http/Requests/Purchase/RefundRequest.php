<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
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
        $rules = [];
        if ($this->get('refund_payment')) {
            $rules['refund_notes_payment'] = 'required';
        }

        if ($this->get('refund_bill')) {
            $rules['refund_notes_bill'] = 'required';
        }

        return $rules;
    }
}
