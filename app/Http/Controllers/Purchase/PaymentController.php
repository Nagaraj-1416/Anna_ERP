<?php

namespace App\Http\Controllers\Purchase;

use App\Bill;
use App\BillPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Purchase\BillCreditUpdateRequest;
use App\Http\Requests\Purchase\CancelRequest;
use App\Http\Requests\Purchase\PaymentStoreRequest;
use App\Http\Requests\Purchase\RefundRequest;
use App\Repositories\Purchase\PaymentRepository;
use PDF;

class PaymentController extends Controller
{
    /**
     * @var PaymentRepository
     */
    protected $payment;

    /**
     * PaymentController constructor.
     * @param PaymentRepository $payment
     */
    public function __construct(PaymentRepository $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @param Bill $bill
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Bill $bill)
    {
        $this->authorize('create', $this->payment->getModel());
        $breadcrumb = $this->payment->breadcrumbs('create');
        return view('purchases.bill.create', compact('breadcrumb', 'bill'));
    }

    /**
     * @param PaymentStoreRequest $request
     * @param Bill $bill
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(PaymentStoreRequest $request, Bill $bill)
    {
        $this->authorize('store', $this->payment->getModel());
        $this->payment->save($request, $bill);
        alert()->success('Payment created successfully', 'Success')->persistent();
        return redirect()->route('purchase.bill.show', [$bill]);
    }

    /**
     * @param BillPayment $payment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(BillPayment $payment)
    {
        $this->authorize('edit', $this->payment->getModel());
        $payment->load(['bank' => function ($q) {
            $q->select(['name', 'id']);
        }, 'paidThrough' => function ($q) {
            $q->select(['name', 'id']);
        }]);
        return response()->json($payment->toArray());
    }

    /**
     * @param PaymentStoreRequest $request
     * @param BillPayment $payment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(PaymentStoreRequest $request, BillPayment $payment)
    {
        $this->authorize('update', $this->payment->getModel());
        $this->payment->update($request, $payment);
        alert()->success('Payment updated successfully', 'Success')->persistent();
        return response()->json(['success' => true]);
    }

    /**
     * @param BillPayment $payment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(BillPayment $payment)
    {
        $this->authorize('delete', $this->payment->getModel());
        $response = $this->payment->delete($payment);
        return response()->json($response);
    }

    /**
     * @param Bill $bill
     * @param BillPayment $payment
     * @param string $type
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(Bill $bill, BillPayment $payment, $type = 'PDF')
    {
        $this->authorize('export', $this->payment->getModel());
        if ($type == 'PDF') {
            $this->pdfExport($bill, $payment);
        }
    }

    public function pdfExport(Bill $bill, BillPayment $payment)
    {
        $company = $bill->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $bill->supplier;
        $address = $supplier->addresses()->first();
        $data = [];
        $data['bill'] = $bill;
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['supplier'] = $supplier;
        $data['address'] = $address;
        $data['payment'] = $payment;
        $pdf = PDF::loadView('purchases.general.payment.export', $data);
        return $pdf->download($bill->bill_no . '.pdf');
    }

    /**
     * @param Bill $bill
     * @param BillPayment $payment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printView(Bill $bill, BillPayment $payment)
    {
        $this->authorize('printView', $this->payment->getModel());
        $company = $bill->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $bill->supplier;
        $address = $supplier->addresses()->first();
        $breadcrumb = $this->payment->breadcrumbs('print', $payment);
        return view('purchases.general.payment.print', compact(
            'breadcrumb', 'bill', 'company', 'companyAddress',
            'supplier', 'address', 'payment'));
    }

    /**
     * @param BillCreditUpdateRequest $request
     * @param BillPayment $payment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateFromCredit(BillCreditUpdateRequest $request, BillPayment $payment)
    {
        $this->authorize('update', $this->payment->getModel());
        $this->payment->updateFromCredit($request, $payment);
        return response()->json(['success' => true]);
    }

    /**
     * @param BillPayment $payment
     * @param CancelRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function cancelPayment(BillPayment $payment, CancelRequest $request)
    {
        $this->authorize('cancel', $this->payment->getModel());
        $this->payment->cancelPayment($payment, $request);
        alert()->success('Bill Payment canceled successfully', 'Success')->persistent();
        return redirect()->route('purchase.bill.show', [$payment->bill]);
    }

    /**
     * @param BillPayment $payment
     * @param RefundRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function refundPayment(BillPayment $payment, RefundRequest $request)
    {
        $this->authorize('refund', $this->payment->getModel());
        $this->payment->refundPayment($payment, $request);
        alert()->success('Bill Payment canceled successfully', 'Success')->persistent();
        return redirect()->route('purchase.bill.show', [$payment->bill]);
    }
}
