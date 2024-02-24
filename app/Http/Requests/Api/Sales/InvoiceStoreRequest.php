<?php

namespace App\Http\Requests\Api\Sales;

use App\Invoice;
use App\SalesOrder;
use Illuminate\Foundation\Http\FormRequest;

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
        $rules = [
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric'
        ];
        if ($this->method() == 'POST'){
            $rules['ref'] = 'required';
        }
        /** @var SalesOrder $order */
        $order = $this->route('order');
       if ($this->method() == 'POST'){
           if($order){
               $rules['amount'] = 'required|numeric|max:' . $this->getPendingOrderAmount($order);
           }
       }
        /** @var Invoice $invoice */
        $invoice = $this->route('invoice');
        if ($this->method() == 'PATCH'){
            if ($invoice){
                $rules['amount'] = 'required|numeric|max:' . $this->getPendingOrderAmountForUpdate($invoice);
            }
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
        if ($order &&  $this->method() == 'POST'){
            $amount = $this->getPendingOrderAmount($order);
        }
        if ($invoice && $this->method() == 'PATCH'){
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
