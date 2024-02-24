<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentStoreRequest extends FormRequest
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
            'company_id' => 'required',
            'name' => 'required',
            'phone' => 'required|regex:/^[0-9]*$/i|min:10',
            'fax' => 'required|regex:/^[0-9]*$/i|min:10',
            'mobile' => 'required|regex:/^[0-9]*$/i|min:10',
            'email' => 'required|email',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'company_id.required' => 'The company field is required.',
            'name.required' => 'The department name field is required.',
            'phone.required' => 'The phone no field is required.',
            'fax.required' => 'The fax no field is required.',
            'mobile.required' => 'The mobile no field is required.',
            'email.required' => 'The email address no field is required.'
        ];
        return $messages;
    }
}
