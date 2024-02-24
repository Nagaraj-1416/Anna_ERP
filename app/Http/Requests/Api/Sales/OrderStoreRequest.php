<?php

namespace App\Http\Requests\Api\Sales;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
        if (!$this->input('save_as')) {
            $this->merge(['save_as' => 'Save']);
        }

        $rules = [
            'business_type_id' => 'required|exists:business_types,id',
            'order_type' => 'required|in:Schedule,Direct',
            'save_as' => 'in:SaveAsDraft,Save',
//            'sales_type' => 'required|in:Retail,Wholesale,Distribution',
            'customer_id' => 'required|exists:customers,id',
//            'price_book_id' => 'exists:price_books,id',
            'order_date' => 'required|date',
            'delivery_date' => 'required|date',
//            'adjustment' => 'numeric',
//            'discount_rate' => 'numeric',
//            'discount_type' => 'in:Percentage,Amount',

            'order_items' => 'array|required',
            'order_items.*.product_id' => 'required|exists:products,id',
//            'order_items.*.store_id' => 'required|exists:stores,id',
            'order_items.*.unit_type_id' => 'required|exists:unit_types,id',
            'order_items.*.quantity' => 'required|numeric',
//            'order_items.*.rate' => 'required|numeric',
//            'order_items.*.discount_rate' => 'required|numeric',
//            'order_items.*.discount_type' => 'required|in:Percentage,Amount',
        ];

        if ($this->input('status')){
            $rules['status'] = 'in:"Scheduled", "Draft", "Awaiting Approval", "Open", "Closed", "Canceled"';
        }

        $user = auth()->user();
        $rep = $user->staffs->first() ? $user->staffs->first()->rep : null;
        if(!$rep){
            $rules['order_items.*.store_id'] = 'required|exists:stores,id';
            $rules['rep_id'] = 'required|exists:reps,id';
        }

        if ($this->input('order_type') == 'Schedule') {
            $rules['scheduled_date'] = 'required|date';
        }

        if ($this->input('price_book_id')) {
            $rules['price_book_id'] = 'exists:price_books,id';
        }

        if ($this->input('is_order_printed')) {
            $rules['is_order_printed'] = 'in:"Yes","No"';
        }

        if ($this->method() == 'POST'){
            $rules['ref'] = 'required';
            $rules['gps_lat'] = 'required';
            $rules['gps_long'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'customer_id.required' => 'The customer field is required.',
            'business_type_id.required' => 'The business type field is required.',
        ];
        return $messages;
    }
}
