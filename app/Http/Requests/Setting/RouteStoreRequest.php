<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class RouteStoreRequest extends FormRequest
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
            'notes' => 'required',
            'cl_amount' => 'required',
            'cl_notify_rate' => 'required',
            'is_active' => 'required',
        ];
        if($this->input('location')){
            $rules['location.name.*'] = 'required';
            $rules['location.notes.*'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'cl_amount.required' => 'The CL amount field is required.',
            'cl_notify_rate.required' => 'The CL notify rate field is required.'
        ];
        return $messages;
    }
}
