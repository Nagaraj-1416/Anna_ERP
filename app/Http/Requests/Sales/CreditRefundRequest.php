<?php

namespace App\Http\Requests\Sales;

use App\Bill;
use App\BillPayment;
use App\CustomerCredit;
use App\CustomerCreditRefund;
use Illuminate\Foundation\Http\FormRequest;

class CreditRefundRequest extends FormRequest
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
        /** Default validation */
        $rules = [
            'amount' => 'required|numeric',
            'refunded_on' => 'required|date',
            'payment_mode' => 'required',
        ];

        /**
         * Check on the create mode
         * @var CustomerCredit $credit
         */
        $credit = $this->route('credit');
        if ($credit) {
            $rules['amount'] = 'required|numeric|max:' . $this->getCreditAmount($credit);
        }

        /**
         * Check on the edit mode
         * @var CustomerCreditRefund $refund
         */
        $refund = $this->route('refund');

        if ($refund) {
            $rules['amount'] = 'required|numeric|max:' . $this->getPendingCreditAmountForUpdate($refund);
        }
        /** Validate payment data */
        $credit = $credit ? $credit : $refund->credit;
        if ($credit) {
            $rules['refunded_on'] = 'required|date|after_or_equal:' . $credit->date;
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $amount = 0;
        /** @var CustomerCredit $credit */
        $credit = $this->route('bill');
        /** @var CustomerCreditRefund $refund */
        $refund = $this->route('payment');
        if ($credit) {
            $amount = $this->getCreditAmount($credit);
        }
        if ($refund) {
            $amount = $this->getPendingCreditAmountForUpdate($refund);
        }
        $amountMsg = 'The payment can\'t be more than available bill balance, you can record payment for ' . number_format($amount, 2) . '.';
        if ($amount == 0) {
            $amountMsg = 'This bill fully paid, please refer made payments for more information.';
        }
        $credit = $credit ? $credit : $refund->credit ?? '';
        if ($credit) {
            $messages = [
                'payment_date.after_or_equal' => 'The payment date must be a date after or equal to bill date ' . $credit->date ?? '',
                'payment.max' => $amountMsg,
            ];
        }

        return $messages;
    }


    /**
     * @param CustomerCreditRefund $refund
     * @return float
     */
    protected function getPendingCreditAmountForUpdate(CustomerCreditRefund $refund)
    {
        $paymentAmount = $refund->amount;
        $pendingBillPayment = $this->getCreditAmount($refund->credit);
        return (float)$paymentAmount + $pendingBillPayment;
    }

    /**
     * @param CustomerCredit $credit
     * @return float
     */
    public function getCreditAmount(CustomerCredit $credit)
    {
        $refundAmount = $credit->refunds->sum('amount');
        $usedCredit = $credit->payments->sum('payment');
        return (float)($credit->amount - ($refundAmount + $usedCredit));

    }
}
