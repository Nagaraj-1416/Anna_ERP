<?php

namespace App\Http\Requests\Sales;

use App\Stock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator as ExtentValidator;

class DailyStockAllocationStoreRequest extends FormRequest
{
    /**
     * @var array
     */
    protected $errorMessages = [
    ];

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
            'store_id' => 'required',
            'route_id' => 'required',
            'rep_id' => 'required',
            'sales_location_id' => 'required'
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = [
            'company_id.required' => 'The company field is required.',
            'store_id.required' => 'The store field is required.',
            'route_id.required' => 'The route field is required.',
            'rep_id.required' => 'The rep field is required',
            'sales_location_id.required' => 'The sales van field is required.',
        ];

        return $messages;
    }

}
