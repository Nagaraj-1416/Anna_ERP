<?php

namespace App\Http\Requests\Sales;

use App\BusinessType;
use App\Product;
use App\Rep;
use App\Store;
use App\Customer;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class EstimateStoreRequest extends FormRequest
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
            'business_type_id' => 'required|exists:business_types,id',
            'rep_id' => 'required|exists:reps,id',
            'customer_id' => 'required|exists:customers,id',
            'estimate_date' => 'required|date',
            'expiry_date' => 'required|date',
            'adjustment' => 'numeric',
            'discount_rate' => 'numeric',
            'discount_type' => 'in:Percentage,Amount',
            'product.*' => 'required|exists:products,id',
//            'store.*' => 'required|exists:stores,id',
            'qty.*' => 'required|numeric',
            'rate.*' => 'required|numeric',
            'item_discount_rate.*' => 'required|numeric',
            'item_discount_type.*' => 'required|in:Percentage,Amount',
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'customer_id.required' => 'The customer field is required.',
            'rep_id.required' => 'The sales rep field is required.',
            'business_type_id.required' => 'The business type field is required.',
        ];
        return $messages;
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator, $this->getResponse($validator)))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * @param Validator $validator
     * @return \Illuminate\Http\RedirectResponse | JsonResponse
     */
    protected function getResponse(Validator $validator)
    {
        $errors = $validator->getMessageBag();
        $response = $this->except([]);
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }

        /** map business type name to old value */
        if($this->get('business_type_id')) {
            $businessType = BusinessType::find($this->input('business_type_id'));
            $response['business_type_name'] = $businessType ? $businessType->name : '';
        }

        /** map sales rep name to old value */
        if($this->get('rep_id')) {
            $rep = Rep::find($this->input('rep_id'));
            $response['rep_name'] = $rep ? $rep->name : '';
        }

        /** map customer name to old value */
        if($this->get('customer_id')) {
            $customer = Customer::find($this->input('customer_id'));
            $response['customer_name'] = $customer ? $customer->display_name : '';
        }

        /** map product name to old value */
        if($this->get('product')) {
            $products = $this->get('product');
            foreach ($products as $key => $id){
                if (!isset($response['product_name'])){
                    $response['product_name'] = [];
                }
                $product = Product::find($id);
                $response['product_name'][$key] = $product ? $product->name : '';
            }
        }

        /** map store name to old value */
        if($this->get('store')) {
            $stores = $this->get('store');
            foreach ($stores as $key => $id){
                if (!isset($response['store_name'])){
                    $response['store_name'] = [];
                }
                $store = Store::find($id);
                $response['store_name'][$key] = $store ? $store->name : '';
            }
        }
        return $this->redirector->to($this->getRedirectUrl())
        ->withInput($response)
        ->withErrors($errors, $this->errorBag);
    }

}
