<?php

namespace App\Http\Requests\Sales;

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

        if ($this->get('refund_invoice')) {
            $rules['refund_notes_invoice'] = 'required';
        }

        return $rules;
    }
}
