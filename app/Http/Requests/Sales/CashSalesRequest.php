<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator as ExtentValidator;

class CashSalesRequest extends FormRequest
{
    public $newMessages = [
        'sales_items.*.qty.required' => 'The item quantity is required.'
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
        ExtentValidator::extend('check_balance', function ($attribute, $value, $parameters, $validator) {
            $attr = explode('.', $attribute);
            $productId = $attr[1];
            return $this->checkBalance($productId);
        });

        $salesItems = $this->get('sales_items');
        if (!$salesItems) $salesItems = [];
        foreach ($salesItems as $key => $salesItem) {
            $this->checkBalance($key);
        }
        $rules = [
            'sales_items.*.id' => 'required',
            'sales_items.*.qty' => 'required|check_balance',
            'sales_items.*.retail_price' => 'required',
            'sales_items' => 'required',
            'order_mode' => 'required',
            'payment_mode' => 'required',
        ];

        if ($this->input('order_mode') == 'Customer') {
            $rules['customer'] = 'required';
        }

        if ($this->input('payment_mode') == 'Cheque') {
            $rules['cheque_no'] = 'required';
            $rules['cheque_date'] = 'required|date';
            $rules['cc_bank_id'] = 'required|exists:banks,id';
        }

        if ($this->input('payment_mode') == 'Direct Deposit') {
            $rules['account_no'] = 'required';
            $rules['deposited_date'] = 'required|date';
            $rules['cc_bank_id'] = 'required|exists:banks,id';
        }

        if ($this->input('payment_mode') == 'Credit Card') {
            $rules['card_holder_name'] = 'required';
            $rules['card_no'] = 'required';
            $rules['expiry_date'] = 'required|date';
            $rules['cc_bank_id'] = 'required|exists:banks,id';
        }

        return $rules;
    }

    public function messages()
    {
        return $this->newMessages;
    }

    public function checkBalance($productId)
    {
        $allocation = getShopAllocation(carbon()->toDateString(), carbon()->toDateString(), auth()->user());
        $this->newMessages['sales_items.' . $productId . '.qty.check_balance'] = 'There are no allocation allocated to this date.';
        if (!$allocation) return false;
        $value = array_get(array_get($this->get('sales_items'), $productId, []), 'qty', 0);
        $item = $allocation->items->where('product_id', $productId)->first();
        if (!$item) return false;
        $qty = ($item->quantity + $item->cf_qty) - ($item->sold_qty + $item->restored_qty);
        $this->newMessages['sales_items.' . $productId . '.qty.check_balance'] = 'The quantity can\'t be more than the allocated stock ( ' . $qty . ' ) in the allocation.';
        if ($qty < $value) return false;
        return true;
    }
}