<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class SupplierStoreRequest extends FormRequest
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
            'salutation' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'display_name' => 'required',
            'phone' => 'required',
            'fax' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'website' => 'required',
        ];

        $rules['street_one'] = 'required';
        $rules['city'] = 'required';
        $rules['province'] = 'required';
        $rules['postal_code'] = 'required';
        $rules['country_id'] = 'required';

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'phone.required' => 'The phone no field is required.',
            'fax.required' => 'The fax no field is required.',
            'mobile.required' => 'The mobile no field is required.',
            'email.required' => 'The email address no field is required.',
            'country_id.required' => 'The country field is required.'
        ];
        return $messages;
    }
}
