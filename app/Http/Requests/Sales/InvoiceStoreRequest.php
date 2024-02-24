<?php

namespace App\Http\Requests\Sales;

use App\BusinessType;
use App\Invoice;
use App\InvoicePayment;
use App\Product;
use App\SalesOrder;
use App\Store;
use App\Customer;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class InvoiceStoreRequest extends FormRequest
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
        /**
         * Default validation rules
         */
        $rules = [
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'amount' => 'required|numeric'
        ];
        /**
         * Validate on create mode
         * @var SalesOrder $order
         */
        $order = $this->route('order');
        if($order){
            $rules['amount'] = 'required|numeric|max:' . $this->getPendingOrderAmount($order);
        }

        /**
         * Validate on update mode
         * @var Invoice $invoice
         */
        $invoice = $this->route('invoice');
        if ($invoice){
            $rules['amount'] = 'required|numeric|max:' . $this->getPendingOrderAmountForUpdate($invoice);
        }

        /** Validate payment data */
        $order = $order ? $order : $invoice->order;
        if ($order){
            $rules['invoice_date'] = 'required|date|after_or_equal:' . $order->order_date;
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $amount = 0;
        /** @var SalesOrder $order */
        $order = $this->route('order');
        /** @var Invoice  $invoice */
        $invoice = $this->route('invoice');
        if ($order){
            $amount = $this->getPendingOrderAmount($order);
        }
        if ($invoice){
            $amount = $this->getPendingOrderAmountForUpdate($invoice);
        }
        $amountMsg = 'The invoice amount can\'t be more than available order balance, you can create an invoice for ' . number_format($amount, 2) . '.' ;
        if ($amount == 0){
            $amountMsg = 'This order fully invoiced, please refer associated invoices for more information.';
        }
        $order = $order ? $order : $invoice->order ?? '';
        if ($order){
            $messages = [
                'invoice_date.after_or_equal' => 'The invoice date must be a date after or equal to order date ' . $order->order_date ?? '',
                'amount.max' => $amountMsg,
            ];
        }

        return $messages;
    }

    /**
     * @param SalesOrder $order
     * @return float
     */
    protected function getPendingOrderAmount(SalesOrder $order): float
    {
        $orderAmount = $order->total;
        $invoicedAmount = $order->invoices->sum('amount');
        return (float) ($orderAmount - $invoicedAmount);
    }

    /**
     * @param Invoice $invoice
     * @return float
     */
    public function getPendingOrderAmountForUpdate(Invoice $invoice)
    {
        $invoiceAmount = $invoice->amount;
        $pendingAmount = $this->getPendingOrderAmount($invoice->order);
        return  (float) $invoiceAmount + $pendingAmount;
    }
}
