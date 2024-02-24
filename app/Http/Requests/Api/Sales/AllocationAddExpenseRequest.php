<?php

namespace App\Http\Requests\Api\Sales;

use Illuminate\Foundation\Http\FormRequest;

class AllocationAddExpenseRequest extends FormRequest
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
            'type_id' => 'required',
            'amount' => 'required|numeric',
        ];

        if($this->request->get('type_id') == 2){
            $rules['liter'] = 'required|numeric';
            $rules['odometer'] = 'required|numeric';
        }

        return $rules;
    }
}
