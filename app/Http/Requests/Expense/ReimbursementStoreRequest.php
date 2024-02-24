<?php

namespace App\Http\Requests\Expense;

use App\{
    Account, ExpenseReport
};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ReimbursementStoreRequest extends FormRequest
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
        /** @var ExpenseReport $reportItem */
        $reportItem = $this->route('report');
        $amount = reportReimbursementPendingAmount($reportItem);
        $fromdate = $reportItem->report_from ?? carbon();
        if ($this->method() == 'PATCH') {
            if ($reimburse = $this->route('reimburse')) {
                $amount += $reimburse->amount;
            }
        }
        return [
            'reimbursed_amount' => 'required|numeric|max:' . $amount,
            'reimbursed_on' => 'required|date|after_or_equal:'.$fromdate,
            'reimbursed_paid_through' => 'required|exists:accounts,id',
        ];
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

        /** map paid through account name to old value */
        if ($this->get('reimbursed_paid_through')) {
            $paidThroughAccount = Account::find($this->input('reimbursed_paid_through'));
            $response['reimbursed_paid_through_name'] = $paidThroughAccount ? $paidThroughAccount->name : '';
        }
        $response['reimburse_id'] = null;
        if ($this->method() == 'PATCH') {
            if ($reimburse = $this->route('reimburse')) {
                $response['reimburse_id'] = $reimburse->id;
            }
        }
        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($response)
            ->withErrors($errors, $this->errorBag);
    }
}
