<?php

namespace App\Http\Requests\Purchase;

use App\{
    Brand, BusinessType, Product, Store, Supplier
};
use App\Company;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as ExtentValidator;

class GrnStoreRequest extends FormRequest
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
            'products.*' => 'required',
            'loaded_by' => 'required'
        ];

        if ($this->input('transfer_by') == 'OwnVehicle'){
            $rules['vehicle_id'] = 'required';
            $rules['odo_starts_at'] = 'required';
            $rules['driver'] = 'required';
            $rules['helper'] = 'required';
        }

        if ($this->input('transfer_by') == 'HiredVehicle'){
            $rules['vehicle_no'] = 'required';
            $rules['transport_name'] = 'required';
            $rules['driver_name'] = 'required';
            $rules['helper_name'] = 'required';
        }

        if ($this->file('files')){
            $rules['files'] = 'array';
            $rules['files.*'] = 'file';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'vehicle_id.required' => 'The vehicle field is required.',
        ];
        return $messages;
    }

}
