<?php

namespace App\Http\Requests\Sales;

use App\Stock;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator as ExtentValidator;

class DailyStockUpdateRequest extends FormRequest
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
        $rules = [];
        if(count($this->request->get('stock_items')['issued_qty'])){
            foreach ($this->request->get('stock_items')['issued_qty'] as $key => $value) {
                $rules['stock_items.issued_qty.'.$key] = 'lte:'.(int)array_get($this->request->get('stock_items')['available_qty_in_store'], $key);
            }
        }
        return $rules;

    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = [];
        if(count($this->request->get('stock_items')['issued_qty'])){
            foreach ($this->request->get('stock_items')['issued_qty'] as $key => $value) {
                $messages['stock_items.issued_qty.'.$key.'.lte'] = 'Issued qty can not be more than available in store.';
            }
        }
        return $messages;
    }

}
