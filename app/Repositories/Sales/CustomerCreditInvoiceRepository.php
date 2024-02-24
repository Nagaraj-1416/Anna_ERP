<?php

namespace App\Repositories\Sales;

use App\Http\Requests\Sales\CustomerCreditInvoiceRequest;
use App\Invoice;
use App\InvoicePayment;
use App\Repositories\BaseRepository;
use App\CustomerCredit;

class CustomerCreditInvoiceRepository extends BaseRepository
{
    /**
     * CustomerCreditInvoiceRepository constructor.
     * @param InvoicePayment|null $payment
     */
    public function __construct(InvoicePayment $payment = null)
    {
        $this->setModel($payment ?? new InvoicePayment());
    }

    /**
     * @param CustomerCredit $credit
     * @param CustomerCreditInvoiceRequest $request
     * @return array
     */
    public function savePayment(CustomerCredit $credit, CustomerCreditInvoiceRequest $request)
    {
        $invoiceIds = $request->input('invoice_id');
        $paymentDate = $request->input('payment_date');
        $paymentType = $request->input('payment_type');
        $payment = $request->input('payment');
        $account = $request->input('account');
        foreach ($invoiceIds as $key => $value) {
            $invoice = Invoice::find($value);
            $newPayment = new InvoicePayment();
            $newPayment->setAttribute('payment', array_get($payment, $key));
            $newPayment->setAttribute('payment_date', array_get($paymentDate, $key));
            $newPayment->setAttribute('payment_type', array_get($paymentType, $key));
            $newPayment->setAttribute('prepared_by', auth()->id());
            $newPayment->setAttribute('invoice_id', $invoice->id);
            $newPayment->setAttribute('customer_id', $invoice->customer_id);
            $newPayment->setAttribute('payment_from', 'Credit');
            $newPayment->setAttribute('credit_id', $credit->id);
            $newPayment->setAttribute('business_type_id', $invoice->business_type_id);
            $newPayment->setAttribute('sales_order_id', $invoice->sales_order_id);
            $newPayment->setAttribute('company_id', $invoice->company_id);
            $newPayment->setAttribute('deposited_to', array_get($account, $key));
            $newPayment->save();
        }
        return ['success' => true];
    }
}