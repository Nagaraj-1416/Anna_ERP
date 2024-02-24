<?php

namespace App\Http\Requests\Setting;

use App\{
    ProductionUnit, SalesLocation, Product, Store
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as ExtentValidator;


class PriceBookStoreRequest extends FormRequest
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
        $this->registerExtendValidator();
        $rules = [
            'name' => 'required',
//            'type' => 'required',
            'category' => 'required',
            'company_id' => 'required',
            'related_to' => 'required',
            'products.*' => 'required',
            'amount.*' => 'required',
            'range_start_from.*' => 'required|integer|check_from_range',
            'range_end_to.*' => 'required|integer',
        ];

        return $rules;
    }

    protected function registerExtendValidator()
    {
        ExtentValidator::extend('check_from_range', function ($attribute, $value, $parameters, $validator) {
            $attribute = explode('.', $attribute);
            $index = $attribute[1] ?? null;
            if ($index == null) return false;
            $from = $this->input('range_start_from');
            $to = $this->input('range_end_to');
            $products = $this->input('products');
            $currentFrom = $from[$index] ?? null;
            $currentTo = $to[$index] ?? null;
            $currentProduct = $products[$index] ?? null;
            foreach ($products as $key => $product){
                if ($product == $currentProduct && $key != $index){
                    $itemFrom = $from[$key] ?? null;
                    $itemTo = $to[$key] ?? null;
                    if (inRange($currentFrom, $itemFrom, $itemTo) || inRange($currentTo, $itemFrom, $itemTo)){
                        return false;
                    }
                }
            }
            return $currentFrom < $currentTo;
        });
    }

    public function messages()
    {
        $messages = [
            'company_id.required' => 'The company field is required.'
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
        // Map the product name to old value
        if ($this->get('products')) {
            $products = $this->get('products');
            foreach ($products as $key => $id) {
                if (!isset($response['product_name'])) {
                    $response['product_name'] = [];
                }
                $product = Product::find($id);
                $response['product_name'][$key] = $product ? $product->name : '';
            }
        }
        if ($this->get('related_to')) {
            $response['related_to_name'] = $this->getRelatedData($this->input('related_to'));
        }
        // return response
        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }


    public function getRelatedData($id)
    {
        $related = $this->input('category');
        $relatedModel = null;
        if ($related == 'Production To Store') {
            $relatedModel = ProductionUnit::find($id);
        } else if ($related == 'Store To Shop') {
            $relatedModel = Store::find($id);
        } else if ($related == 'Shop Selling Price') {
            $relatedModel = SalesLocation::find($id);
        } else if ($related == 'Van Selling Price') {
            $relatedModel = SalesLocation::find($id);
        }

        if ($relatedModel) {
            return $relatedModel->name;
        }
        return '';
    }
}
