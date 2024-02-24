<?php

namespace App\Repositories\Purchase;

use App\Bill;
use App\BillPayment;
use App\Http\Requests\Purchase\SupplierCreditBillRequest;
use App\Repositories\BaseRepository;
use App\SupplierCredit;

class SupplierCreditBillRepository extends BaseRepository
{
    /**
     * SupplierCreditBillRepository constructor.
     * @param BillPayment|null $payment
     */
    public function __construct(BillPayment $payment = null)
    {
        $this->setModel($payment ?? new BillPayment());
    }

    /**
     * @param SupplierCredit $credit
     * @param SupplierCreditBillRequest $request
     * @return array
     */
    public function savePayment(SupplierCredit $credit, SupplierCreditBillRequest $request)
    {
        $billIds = $request->input('bill_id');
        $paymentDate = $request->input('payment_date');
        $paymentType = $request->input('payment_type');
        $paymentRequest = $request->input('payment');
        $account = $request->input('account');
        foreach ($billIds as $key => $value) {
            $bill = Bill::find($value);
            $payment = new BillPayment();
            $payment->setAttribute('payment', array_get($paymentRequest, $key));
            $payment->setAttribute('payment_date', array_get($paymentDate, $key));
            $payment->setAttribute('payment_type', array_get($paymentType, $key));
            $payment->setAttribute('prepared_by', auth()->id());
            $payment->setAttribute('bill_id', $bill->id);
            $payment->setAttribute('supplier_id', $bill->supplier_id);
            $payment->setAttribute('payment_from', 'Credit');
            $payment->setAttribute('credit_id', $credit->id);
            $payment->setAttribute('business_type_id', $bill->business_type_id);
            $payment->setAttribute('purchase_order_id', $bill->purchase_order_id);
            $payment->setAttribute('company_id', $bill->company_id);
            $payment->setAttribute('paid_through', array_get($account, $key));
            $payment->save();
        }
        return ['success' => true];
    }
}