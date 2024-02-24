<?php

namespace App\Http\Requests\Stock;

use App\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class StockStoreRequest extends FormRequest
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
//            'rate.*' => 'required',
            'available_stock.*' => 'required',
            'product_id.*' => 'required',
            'store_id' => 'required',
            'company_id' => 'required',
//            'production_unit' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'rate.*.required' => 'The rate field is required.',
            'available_stock.*.required' => 'The stock IN qty field is required.',
            'product_id.*.required' => 'The product field is required.',
            'store_id.required' => 'The store field is required.',
            'vehicle_id.required' => 'The vehicle field is required.',
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
        /** map product name to old value */
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


        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }
}
