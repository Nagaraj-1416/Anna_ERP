<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class VehicleModelStoreRequest extends FormRequest
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
            'name' => 'required',
            'is_active' => 'required',
            'make_id' => 'required'
        ];
    }

    public function messages()
    {
        $messages = [
            'make_id.required' => 'The vehicle make field is required.',
        ];
        return $messages;
    }
}
