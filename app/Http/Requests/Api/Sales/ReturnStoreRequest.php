<?php

namespace App\Http\Requests\Api\Sales;

use Illuminate\Foundation\Http\FormRequest;

class ReturnStoreRequest extends FormRequest
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
            'date' => 'required|date|after_or_equal:today',
            'is_printed' => 'in:"Yes","No"',
//            'notes' => 'required',
            'items' => 'array|required',
            'items.*.qty' => 'required|numeric',
            'items.*.type' => 'required|in:"Stock","Sales"',
            'items.*.sold_rate' => 'required|numeric',
            'items.*.returned_rate' => 'required|numeric',
            'items.*.returned_amount' => 'required|numeric',
            'items.*.reason' => 'required',
            'items.*.order_id' => 'required|exists:sales_orders,id',
            'items.*.product_id' => 'required|exists:products,id',
            'resolutions' => 'required|array',
            'resolutions.*.type' => 'required|distinct|in:"Refund","Credit","Replace"',
            'resolutions.*.amount' => 'required|numeric|sum_equal:items.*.returned_amount',
        ];

        if (is_array($this->input('items'))) {
            foreach ($this->input('items') as $key => $item) {
                $qty = array_get($item, 'qty');
                $returnedRate = array_get($item, 'returned_rate');
                $amount = $qty * $returnedRate;
                $rules['items.' . $key . '.returned_amount'] = 'required|numeric|equal:' . $amount;
            }
        }

        if (is_array($this->input('resolutions'))) {
            foreach ($this->input('resolutions') as $key => $item) {
                if (isset($item['type']) && $item['type'] == 'Replace') {
                    $amount = $item['amount'] ?? 0;
                    $rules['return_products'] = 'required|array';
                    $rules['return_products.*.qty'] = 'required|numeric';
                    $rules['return_products.*.rate'] = 'required|numeric';
                    $rules['return_products.*.amount'] = 'required|numeric|sum_equal:' . $amount;
                    $rules['return_products.*.product_id'] = 'required|exists:products,id';
                }
            }
        }
        if (is_array($this->input('return_products'))) {
            foreach ($this->input('return_products') as $key => $item) {
                $qty = array_get($item, 'qty');
                $rate = array_get($item, 'rate');
                $amount = $qty * $rate;
                $rules['return_products.' . $key . '.amount'] = 'required|numeric|equal:' . $amount;
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        if (is_array($this->get('resolutions'))) {
            foreach ($this->get('resolutions') as $key => $val) {
                $messages["resolutions.$key.amount.sum_equal"] = "Amount doesn't balance with returned amount, Please adjust the amount";
            }
        }

        if ($this->get('return_products')) {
            foreach ($this->get('return_products') as $key => $val) {
                $messages["return_products.$key.amount.sum_equal"] = "Amount doesn't balance with resolution amount, Please adjust the amount";
            }
        }
        return $messages;
    }
}
