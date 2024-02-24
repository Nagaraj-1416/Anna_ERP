<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class VehicleStoreRequest extends FormRequest
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
            'vehicle_no' => 'required',
            'engine_no' => 'required',
            'chassis_no' => 'required',
            'reg_date' => 'required|date',
            'year' => 'required',
            'color' => 'required',
            'fuel_type' => 'required',
            'type_id' => 'required',
            'make_id' => 'required',
            'model_id' => 'required',
            'company_id' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'company_id.required' => 'The company field is required.',
            'type_id.required' => 'The vehicle type field is required.',
            'make_id.required' => 'The vehicle make field is required.',
            'model_id.required' => 'The vehicle model field is required.',
            'reg_date.required' => 'The registration date field is required.',
        ];
        return $messages;
    }
}
