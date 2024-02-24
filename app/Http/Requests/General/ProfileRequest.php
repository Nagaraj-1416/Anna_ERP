<?php

namespace App\Http\Requests\General;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'short_name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'mobile' => 'required',
            'street_one' => 'required',
            'street_two' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
            'country_id' => 'required',
            'is_active' => 'required',
        ];
    }
}
