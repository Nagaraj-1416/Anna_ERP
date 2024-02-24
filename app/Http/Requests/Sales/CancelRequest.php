<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;

class CancelRequest extends FormRequest
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
        if ($this->get('payment')) {
            $rules['cancel_notes_payment'] = 'required';
        }

        if ($this->get('invoice')) {
            $rules['cancel_notes_invoice'] = 'required';
        }

        if ($this->get('order')) {
            $rules['cancel_notes_order'] = 'required';
        }

        return $rules;
    }
}
