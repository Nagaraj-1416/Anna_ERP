<?php

namespace App\Http\Requests\Sales;

use App\Stock;
use Illuminate\Support\Facades\Validator as ExtentValidator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AllocationAddProductRequest
 * @package App\Http\Requests\Sales
 * @property array $errorMessages
 */
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
        $fromDate = carbon()->parse($this->request->get('allocation_start'))->toDateString();
        $toDate = carbon()->parse($this->request->get('allocation_end'))->toDateString();

        $rules = [
            'type_id' => 'required',
            'expense_date' => 'required|date|after_or_equal:'.$fromDate.'|before_or_equal:'.$toDate,
            'amount' => 'required|numeric',
        ];

        if($this->request->get('type_id') == 2){
            $rules['liters'] = 'required|numeric';
            $rules['odometer'] = 'required|numeric';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'type_id.required' => 'The expense type field is required.'
        ];
        return $messages;
    }
}
