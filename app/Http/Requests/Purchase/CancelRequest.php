<?php

namespace App\Http\Requests\Purchase;

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

        if ($this->get('bill')) {
            $rules['cancel_notes_bill'] = 'required';
        }

        if ($this->get('order')) {
            $rules['cancel_notes_order'] = 'required';
        }

        return $rules;
    }
}
