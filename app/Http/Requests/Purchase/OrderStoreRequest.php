<?php

namespace App\Http\Requests\Purchase;

use App\{
    Brand, BusinessType, Product, Store, Supplier
};
use App\Company;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as ExtentValidator;

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
        $rules = [
            'company_id' => 'required|exists:companies,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'product.*' => 'required|exists:products,id',
            'quantity.*' => 'required|numeric|min:1'
        ];

        if ($this->input('po_for') == 'PUnit'){
            $rules['production_unit_id'] = 'required';
        }

        if ($this->input('po_for') == 'Store'){
            $rules['store_id'] = 'required';
        }

        if ($this->input('po_for') == 'Shop'){
            $rules['shop_id'] = 'required';
        }

        if ($this->file('files')){
            $rules['files'] = 'array';
            $rules['files.*'] = 'file';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'supplier_id.required' => 'The supplier field is required.',
            'store_id.required' => 'The store field is required.',
            'company_id.required' => 'The company field is required.',
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
     * @return \Illuminate\Http\RedirectResponse | JsonResponse
     */
    protected function getResponse(Validator $validator)
    {
        $errors = $validator->getMessageBag();
        $response = $this->except([]);
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }
        // Map the supplier name to old value
        if($this->get('supplier_id')) {
            $supplier = Supplier::find($this->input('supplier_id'));
            $response['supplier_name'] = $supplier ? $supplier->display_name : '';
        }
        // Map the supplier name to old value
        if($this->get('company_id')) {
            $company = Company::find($this->input('company_id'));
            $response['company_name'] = $company ? $company->name : '';
        }
        if($this->get('store_id')) {
            $store = Store::find($this->input('store_id'));
            $response['store_name'] = $store ? $store->name : '';
        }
        // Map the product name to old value
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
        // Map the store name to old value
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
        // return response
        return $this->redirector->to($this->getRedirectUrl())
        ->withInput($response)
        ->withErrors($errors, $this->errorBag);
    }
}
