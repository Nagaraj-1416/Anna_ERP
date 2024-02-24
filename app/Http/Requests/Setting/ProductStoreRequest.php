<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'type' => 'required',
            'measurement' => 'required',
            'min_stock_level' => 'required',
            'notes' => 'required',
            'category' => 'required',
            'tamil_name' => 'required',
            'is_expirable' => 'required',
            'opening_cost' => 'numeric',
            'opening_qty' => 'numeric|integer'
        ];

        if ($this->request->get('type') == 'Raw Material') {
            $rules['buying_price'] = 'required';
        } else if ($this->request->get('type') == 'Finished Good') {
            $rules['wholesale_price'] = 'required';
            $rules['retail_price'] = 'required';
            $rules['distribution_price'] = 'required';
            $rules['packet_price'] = 'required';
        } else if ($this->request->get('type') == 'Third Party Product') {
            $rules['buying_price'] = 'required';

            $rules['wholesale_price'] = 'required';
            $rules['retail_price'] = 'required';
            $rules['distribution_price'] = 'required';
            $rules['packet_price'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required' => 'The product name field is required.',
            'type.required' => 'The product type field is required.',
        ];
        return $messages;
    }
}
