<?php

namespace App\Http\Requests\Sales;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class InvoiceCreditUpdateRequest extends FormRequest
{
    protected $credit;

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

        Validator::extend('checkTotalAmount', function ($attribute, $value, $parameters, $validator) {
            $creditAmount = $this->getCreditAmount();
            if ($creditAmount < (int)$value) {
                return false;
            }
            return true;
        });
        Validator::extend('checkAmount', function ($attribute, $value, $parameters, $validator) {
            $invoiceAmount = $this->getInvoiceAmount();
            if ($invoiceAmount < (int)$value) {
                return false;
            }
            return true;
        });
        $rules = [
            'payment' => 'required|checkAmount',
            'payment_date' => 'required',
            'payment_type' => 'required',
            'total' => 'checkTotalAmount',
            'deposited_to' => 'required'
        ];
        return $rules;
    }

    public function getCreditAmount()
    {
        $payment = $this->route('payment');
        $credit = $payment->credit;
        if (!$credit) return 0;
        $amount = $credit->amount;
        $this->credit = $credit;
        $paid = getCustomerCreditUsed($credit, [$payment->id]);
        return $amount - $paid;
    }

    public function getInvoiceAmount()
    {
        $payment = $this->route('payment');
        $invoice = $payment->invoice;
        if (!$invoice) return 0;
        $paid = $invoice->payments->whereNotIn('id', [$payment->id])->sum('payment');
        return $invoice->amount - $paid;
    }

    public function messages()
    {
        return [
            'total.check_total_amount' => 'Applied total invoice amount: ' . number_format($this->input('payment'), 2) . ', The credit remaining is not enough to proceed this credit use request.',
            'payment.check_amount' => 'The given payment may not be greater than ' . number_format($this->getInvoiceAmount(), 2),
            'account.required' => 'The deposited to field is required.'
        ];
    }
}
