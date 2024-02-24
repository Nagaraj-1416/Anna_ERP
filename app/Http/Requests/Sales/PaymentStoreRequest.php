<?php

namespace App\Http\Requests\Sales;

use App\Invoice;
use App\InvoicePayment;
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
        $rules = [
            'payment' => 'required|numeric',
            'deposited_to' => 'required|exists:accounts,id',
            'payment_date' => 'required|date',
            'payment_type' => 'required',
            'payment_mode' => 'required',
            'daily_sale_id' => 'required',
        ];

        if ($this->input('payment_mode') == 'Cheque'){
            $rules['cheque_no'] = 'required';
            $rules['cheque_date'] = 'required|date';
            $rules['cheque_bank_id'] = 'required|exists:banks,id';
            $rules['cheque_type'] = 'required';
        }

        if ($this->input('payment_mode') == 'Direct Deposit'){
            $rules['account_no'] = 'required';
            $rules['deposited_date'] = 'required|date';
            $rules['dd_bank_id'] = 'required|exists:banks,id';
        }

        if ($this->get('payment_mode') == 'Credit Card') {
            $rules['card_holder_name'] = 'required';
            $rules['card_no'] = 'required|min:16';
            $rules['expiry_date'] = 'required';
            $rules['cc_bank_id'] = 'required';
        }
        /**
         * Validate on create mode
         * @var Invoice $invoice
         */
        $invoice = $this->route('invoice');
        if($invoice) {
            $rules['payment'] = 'required|numeric|max:' . $this->getPendingInvoicePayment($invoice);
        }
        /**
         * Validate on update
         * @var InvoicePayment $payment
         */
        $payment = $this->route('payment');
        if ($payment){
            $rules['payment'] = 'required|numeric|max:' . $this->getPendingInvoicePaymentForUpdate($payment);
        }

        /** Validate payment data */
        $invoice = $invoice ? $invoice : $payment->invoice;
        if ($invoice){
            $rules['payment_date'] = 'required|date|after_or_equal:' . $invoice->invoice_date;
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $amount = 0;
        /** @var Invoice $invoice */
        $invoice = $this->route('invoice');
        /** @var InvoicePayment $payment */
        $payment = $this->route('payment');
        if ($invoice){
            $amount = $this->getPendingInvoicePayment($invoice);
        }
        if ($payment){
            $amount = $this->getPendingInvoicePaymentForUpdate($payment);
        }
        $amountMsg = 'The payment can\'t be more than available invoice balance, you can record payment for ' . number_format($amount, 2) . '.' ;
        if ($amount == 0){
            $amountMsg = 'This invoice fully paid, please refer recorded payments for more information.';
        }
        $invoice = $invoice ? $invoice : $payment->invoice ?? '';
        if ($invoice){
            $messages = [
                'payment_date.after_or_equal' => 'The payment date must be a date after or equal to invoice date ' . $invoice->invoice_date ?? '',
                'payment.max' => $amountMsg,
            ];
        }

        return $messages;
    }

    /**
     * @param Invoice $invoice
     * @return float
     */
    protected function getPendingInvoicePayment(Invoice $invoice)
    {
        $totalAmount = $invoice->amount;
        $paidAmounts = $invoice->payments->where('status', 'Paid')->sum('payment');
        return (float) ($totalAmount - $paidAmounts);
    }

    /**
     * @param InvoicePayment $payment
     * @return float
     */
    protected function getPendingInvoicePaymentForUpdate(InvoicePayment $payment)
    {
        $paymentAmount = $payment->payment;
        $pendingAmount = $this->getPendingInvoicePayment($payment->invoice);
        return (float) $pendingAmount + $paymentAmount;
    }
}
