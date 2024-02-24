<?php

namespace App\Http\Requests\Sales;

use App\{
    BusinessType, Customer, Product
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\{
    JsonResponse, RedirectResponse
};
use Illuminate\Validation\ValidationException;

class InquiryStoreRequest extends FormRequest
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
            'inquiry_date' => 'required|date',
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|numeric|min:1',
            'delivery_date.*' => 'required|date|after_or_equal:inquiry_date',
        ];
        if ($this->input('customer_id')){
            $rules['customer_id'] = 'exists:customers,id';
        }
        return $rules;
    }

    /**
     * overwrite validation message
     * @return array
     */
    public function messages()
    {
        $messages = [
            'customer_id.required' => 'The customer field is required.',
            'business_type_id.required' => 'The business type field is required.',
        ];
        return $messages;
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator, $this->getResponse($validator)))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * @param Validator $validator
     * @return RedirectResponse|JsonResponse
     */
    protected function getResponse(Validator $validator)
    {
        $errors = $validator->getMessageBag();
        $response = $this->except([]);
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }
        // Map the business name to old value
        if ($this->get('business_type_id')) {
            $businessType = BusinessType::find($this->input('business_type_id'));
            $response['business_type_name'] = $businessType ? $businessType->name : '';
        }
        // Map the customer name to old value
        if ($this->get('customer_id')) {
            $customer = Customer::find($this->input('customer_id'));
            $response['customer_name'] = $customer ? $customer->display_name : '';
        }
        // Map the product name to old value
        if ($this->get('product_id')) {
            $products = $this->get('product_id');
            foreach ($products as $key => $id) {
                if (!isset($response['product_name'])) {
                    $response['product_name'] = [];
                }
                $product = Product::find($id);
                $response['product_name'][$key] = $product ? $product->name : '';
            }
        }
        // return response
        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }
}
