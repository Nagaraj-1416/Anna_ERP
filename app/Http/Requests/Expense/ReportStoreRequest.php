<?php

namespace App\Http\Requests\Expense;

use App\{
    BusinessType, Expense, User
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ReportStoreRequest extends FormRequest
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
        $rule =  [
            'business_type_id' => 'required|exists:business_types,id',
            'report_from' => 'required|date|before_or_equal:report_to',
            'report_to' => 'required|date|after_or_equal:report_from',
            'title' => 'required',
            'expenses_id' => 'required'
        ];
        if ($this->input('approved_by')){
            $rule['approved_by'] = 'exists:users,id';
        }
        return $rule;
    }


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
        /** map company name to old value */
        if ($this->get('approved_by')) {
            $user = User::find($this->input('approved_by'));
            $response['approved_by_name'] = $user ? $user->name : '';
        }

        /** map business type to old value */
        if ($this->get('business_type_id')) {
            $businessType = BusinessType::find($this->input('business_type_id'));
            $response['business_type_name'] = $businessType ? $businessType->name : '';
        }

        if ($this->get('expenses_id')){
            $ids = explode(',', $this->get('expenses_id'));
            $response['expenses'] = Expense::whereIn('id', $ids)->get(['id','expense_no', 'expense_date', 'amount']);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }

}
