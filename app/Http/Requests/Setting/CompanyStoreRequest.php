<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
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
            'name' => 'required',
            'display_name' => 'required',
            'phone' => 'required|regex:/^[0-9]*$/i|min:10',
            'fax' => 'required|regex:/^[0-9]*$/i|min:10',
            'mobile' => 'required|regex:/^[0-9]*$/i|min:10',
            'email' => 'required|email',
            'website' => 'required|url',
            'business_location' => 'required',
            'base_currency' => 'required',
            'fy_starts_month' => 'required',
            'fy_starts_from' => 'required',
            'timezone' => 'required',
            'date_time_format' => 'required',
            'business_starts_at' => 'required',
            'business_end_at' => 'required',
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
            'name.required' => 'The company name field is required.',
            'phone.required' => 'The phone no field is required.',
            'fax.required' => 'The fax no field is required.',
            'mobile.required' => 'The mobile no field is required.',
            'email.required' => 'The email address no field is required.',
            'business_end_at.required' => 'The business ends at field is required.',
            'country_id.required' => 'The country field is required.'
        ];
        return $messages;
    }
}
