<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class SupplierOpeningStoreRequest extends FormRequest
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
            'references' => 'required',
//            'references.*.reference_no' => 'required',
            'references.*.bill_no' => 'required',
            'references.*.bill_date' => 'required|before:today',
            'references.*.bill_amount' => 'required|numeric',
            'references.*.bill_due' => 'required|numeric',
            'references.*.bill_due_age' => 'required|numeric',
        ];
    }

    public function messages()
    {
        $messages = [
            'references.*.reference_no.required' => 'The reference no field is required.',
            'references.*.bill_no.required' => 'The bill no field is required.',
            'references.*.bill_date.required' => 'The bill date field is required.',
            'references.*.bill_date.before' => 'The bill date must before today.',
            'references.*.bill_amount.required' => 'The bill amount field is required.',
            'references.*.bill_amount.numeric' => 'The bill amount must be a amount.',
            'references.*.bill_due.required' => 'The bill due field is required.',
            'references.*.bill_due.numeric' => 'The bill due must be a amount.',
            'references.*.bill_due_age.required' => 'The bill due age field is required.',
            'references.*.bill_due_age.numeric' => 'The bill due age must be a number.',
        ];
        return $messages;
    }
}
