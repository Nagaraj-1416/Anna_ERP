<?php

namespace App\Http\Requests\Sales;

use App\BusinessType;
use App\Customer;
use App\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class CustomerCreditRequest extends FormRequest
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
        return [
            'business_type_id' => 'required',
            'customer_id' => 'required',
            'date' => 'required',
            'amount' => 'required',
            'notes' => 'required',
        ];
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
     * @return RedirectResponse | JsonResponse
     */
    protected function getResponse(Validator $validator)
    {
        $errors = $validator->getMessageBag();
        $response = $this->except([]);
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 422);
        }

        /** map business type name to old value */
        if ($this->get('business_type_id')) {
            $businessType = BusinessType::find($this->input('business_type_id'));
            $response['business_type_name'] = $businessType ? $businessType->name : '';
        }

        /** map customer name to old value*/
        if ($this->get('customer_id')) {
            $customer = Customer::find($this->input('customer_id'));
            $response['customer_name'] = $customer ? $customer->display_name : '';
        }

        /** map invoice code to old value*/
        if ($this->get('invoice_id')) {
            $invoice = Invoice::find($this->input('invoice_id'));
            $response['invoice_name'] = $invoice ? $invoice->invoice_no : '';
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }

}
