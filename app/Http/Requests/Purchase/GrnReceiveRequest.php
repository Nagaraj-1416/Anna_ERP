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

class GrnReceiveRequest extends FormRequest
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
            'unloaded_by' => 'required'
        ];

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
