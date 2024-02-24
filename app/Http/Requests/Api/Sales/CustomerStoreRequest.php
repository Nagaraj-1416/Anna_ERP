<?php

namespace App\Http\Requests\Api\Sales;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
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
            'route_id' => 'required|exists:routes,id',
            'location_id' => 'required|exists:locations,id',
        ];

        $rules['street_one'] = 'required';
        $rules['city'] = 'required';
        $rules['province'] = 'required';
        $rules['postal_code'] = 'required';
        $rules['country_id'] = 'required|exists:countries,id';

        if ($this->input('contact_persons')){
            $rules['contact_persons'] = 'required|array';
            $rules['contact_persons.*.first_name'] = 'required';
            $rules['contact_persons.*.last_name'] = 'required';
            $rules['contact_persons.*.email'] = 'required|email';
            $rules['contact_persons.*.phone'] = 'required|regex:/^[0-9]*$/i|min:10';
            $rules['contact_persons.*.mobile'] = 'required|regex:/^[0-9]*$/i|min:10';
        }

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
