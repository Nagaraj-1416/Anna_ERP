<?php

namespace App\Http\Requests\Purchase;

use App\Bill;
use App\BillPayment;
use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
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
            'payment' => 'required|numeric',
            'payment_date' => 'required|date',
            'payment_type' => 'required',
            'payment_mode' => 'required',
            'paid_through' => 'required',
        ];
        
        if ($this->get('payment_mode') == 'Cheque') {
            $rules['cheque_no'] = 'required';
            $rules['cheque_date'] = 'required';
            $rules['cheque_bank_id'] = 'required';
            $rules['cheque_type'] = 'required';
        }
        if ($this->get('payment_mode') == 'Direct Deposit') {
            $rules['account_no'] = 'required';
            $rules['deposited_date'] = 'required';
            $rules['dd_bank_id'] = 'required';
        }

        if ($this->get('payment_mode') == 'Credit Card') {
            $rules['card_holder_name'] = 'required';
            $rules['card_no'] = 'required|min:16';
            $rules['expiry_date'] = 'required';
            $rules['cc_bank_id'] = 'required';
        }
        /**
         * Check on the create mode
         * @var Bill $bill
         */
        $bill = $this->route('bill');
        if ($bill) {
            $rules['payment'] = 'required|numeric|max:' . $this->getPendingBillPayment($bill);
        }

        /**
         * Check on the edit mode
         * @var BillPayment $payment
         */
        $payment = $this->route('payment');
        if ($payment) {
            $rules['payment'] = 'required|numeric|max:' . $this->getPendingBillPaymentForUpdate($payment);
        }

        /** Validate payment data */
        $bill = $bill ? $bill : $payment->bill;
        if ($bill) {
            $rules['payment_date'] = 'required|date|after_or_equal:' . $bill->bill_date;
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $amount = 0;
        /** @var Bill $bill */
        $bill = $this->route('bill');
        /** @var BillPayment $payment */
        $payment = $this->route('payment');
        if ($bill) {
            $amount = $this->getPendingBillPayment($bill);
        }
        if ($payment) {
            $amount = $this->getPendingBillPaymentForUpdate($payment);
        }
        $amountMsg = 'The payment can\'t be more than available bill balance, you can record payment for ' . number_format($amount, 2) . '.';
        if ($amount == 0) {
            $amountMsg = 'This bill fully paid, please refer made payments for more information.';
        }
        $bill = $bill ? $bill : $payment->bill ?? '';
        if ($bill) {
            $messages = [
                'payment_date.after_or_equal' => 'The payment date must be a date after or equal to bill date ' . $bill->bill_date ?? '',
                'payment.max' => $amountMsg,
            ];
        }

        return $messages;
    }

    /**
     * @param Bill $bill
     * @return float
     */
    protected function getPendingBillPayment(Bill $bill)
    {
        $totalAmount = $bill->amount;
        $paidAmounts = $bill->payments->where('status', 'Paid')->sum('payment');
        return (float)($totalAmount - $paidAmounts);
    }

    /**
     * @param BillPayment $payment
     * @return float
     */
    protected function getPendingBillPaymentForUpdate(BillPayment $payment)
    {
        $paymentAmount = $payment->payment;
        $pendingBillPayment = $this->getPendingBillPayment($payment->bill);
        return (float)$paymentAmount + $pendingBillPayment;
    }
}
