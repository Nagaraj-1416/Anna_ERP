<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class CustomerOpeningStoreRequest extends FormRequest
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
        $rule = [
            'opening' => 'required|numeric',
            'opening_at' => 'required',
            'balance_type' => 'required',
//            'references' => 'required',
        ];
        $ref = $this->input('references');
        if ($ref && is_array($ref) && count($ref)){
            $rule['references.*.invoice_no'] = 'required';
            $rule['references.*.invoice_date'] = 'required|before:today';
            $rule['references.*.invoice_amount'] = 'required|numeric';
            $rule['references.*.invoice_due'] = 'required|numeric';
            $rule['references.*.invoice_due_age'] = 'required';
        }
        return $rule;
    }

    public function messages()
    {
        $messages = [
            'references.*.reference_no.required' => 'The reference no field is required.',
            'references.*.invoice_no.required' => 'The invoice no field is required.',
            'references.*.invoice_date.required' => 'The invoice date field is required.',
            'references.*.invoice_date.before' => 'The invoice date must before today.',
            'references.*.invoice_amount.required' => 'The invoice amount field is required.',
            'references.*.invoice_amount.numeric' => 'The invoice amount must be a amount.',
            'references.*.invoice_due.required' => 'The invoice due field is required.',
            'references.*.invoice_due.numeric' => 'The invoice amount must be a amount.',
            'references.*.invoice_due_age.required' => 'The invoice due age field is required.',
            'references.*.invoice_due_age.numeric' => 'The invoice due age must be a number.',
        ];
        return $messages;
    }
}
