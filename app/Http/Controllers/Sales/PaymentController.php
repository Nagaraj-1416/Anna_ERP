<?php

namespace App\Http\Controllers\Sales;

use App\Http\Requests\Sales\CancelRequest;
use App\Http\Requests\Sales\InvoiceCreditUpdateRequest;
use App\Http\Requests\Sales\RefundRequest;
use App\Invoice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\PaymentStoreRequest;
use App\InvoicePayment;
use App\Repositories\Sales\PaymentRepository;
use Illuminate\Http\RedirectResponse;
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
     * @param Invoice $invoice
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Invoice $invoice)
    {
        $this->authorize('create', $this->payment->getModel());
        $breadcrumb = $this->payment->breadcrumbs('create');
        return view('sales.invoice.create', compact('breadcrumb', 'invoice'));
    }

    /**
     * @param PaymentStoreRequest $request
     * @param Invoice $invoice
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(PaymentStoreRequest $request, Invoice $invoice)
    {
        $this->authorize('store', $this->payment->getModel());
        $payment = $this->payment->save($request, $invoice);
        alert()->success('Payment created successfully', 'Success')->persistent();
        return redirect()->route('sales.invoice.show', [$invoice]);
    }

    /**
     * @param InvoicePayment $payment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(InvoicePayment $payment)
    {
        $this->authorize('edit', $this->payment->getModel());
        $payment->load(['bank' => function ($q) {
            $q->select(['name', 'id']);
        }, 'depositedTo' => function ($q) {
            $q->select(['name', 'id']);
        }]);
        return response()->json($payment->toArray());
    }

    /**
     * @param PaymentStoreRequest $request
     * @param InvoicePayment $payment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(PaymentStoreRequest $request, InvoicePayment $payment)
    {
        $this->authorize('update', $this->payment->getModel());
        $this->payment->update($request, $payment);
        alert()->success('Payment updated successfully', 'Success')->persistent();
        return response()->json(['success' => true]);
    }

    /**
     * @param InvoicePayment $payment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(InvoicePayment $payment)
    {
        $this->authorize('delete', $this->payment->getModel());
        $response = $this->payment->delete($payment);
        return response()->json($response);
    }

    /**
     * @param Invoice $invoice
     * @param InvoicePayment $payment
     * @param string $type
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(Invoice $invoice, InvoicePayment $payment, $type = 'PDF')
    {
        $this->authorize('export', $this->payment->getModel());
        if ($type == 'PDF') {
            $this->pdfExport($invoice, $payment);
        }
    }

    /**
     * @param Invoice $invoice
     * @param InvoicePayment $payment
     * @return mixed
     */
    public function pdfExport(Invoice $invoice, InvoicePayment $payment)
    {
        $company = $invoice->company;
        $companyAddress = $company->addresses()->first();
        $customer = $invoice->customer;
        $address = $customer->addresses()->first();
        $data = [];
        $data['invoice'] = $invoice;
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['customer'] = $customer;
        $data['address'] = $address;
        $data['payment'] = $payment;
        $pdf = PDF::loadView('sales.general.payment.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Payment Receipt' . '.pdf');
    }

    /**
     * @param Invoice $invoice
     * @param InvoicePayment $payment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printView(Invoice $invoice, InvoicePayment $payment)
    {
        $this->authorize('print', $this->payment->getModel());
        $company = $invoice->company;
        $companyAddress = $company->addresses()->first();
        $customer = $invoice->customer;
        $address = $customer->addresses()->first();
        $breadcrumb = $this->payment->breadcrumbs('print', $payment);
        return view('sales.general.payment.print', compact(
            'breadcrumb', 'invoice', 'company', 'companyAddress',
            'customer', 'address', 'payment'));
    }

    /**
     * @param InvoiceCreditUpdateRequest $request
     * @param InvoicePayment $payment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateFromCredit(InvoiceCreditUpdateRequest $request, InvoicePayment $payment)
    {
        $this->authorize('edit', $this->payment->getModel());
        $this->payment->updateFromCredit($request, $payment);
        return response()->json(['success' => true]);
    }

    /**
     * @param InvoicePayment $payment
     * @param CancelRequest $request
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function cancelPayment(InvoicePayment $payment, CancelRequest $request)
    {
        $this->authorize('cancel', $this->payment->getModel());
        $this->payment->cancelPayment($payment, $request);
        alert()->success('Invoice payment canceled successfully', 'Success')->persistent();
        return redirect()->route('sales.invoice.show', [$payment->invoice]);
    }

    /**
     * @param InvoicePayment $payment
     * @param RefundRequest $request
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function refundPayment(InvoicePayment $payment, RefundRequest $request)
    {
        $this->authorize('refund', $this->payment->getModel());
        $this->payment->refundPayment($payment, $request);
        alert()->success('Invoice Payment refunded successfully', 'Success')->persistent();
        return redirect()->route('sales.invoice.show', [$payment->invoice]);
    }
}
