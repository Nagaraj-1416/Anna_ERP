<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class WorkHourStoreRequest extends FormRequest
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
            'user_id' => 'required',
            'start' => 'required',
            'end' => 'required',
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'user_id.required' => 'The user field is required.'
        ];
        return $messages;
    }
}
