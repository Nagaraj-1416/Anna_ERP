<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Sales\PaymentStoreRequest;
use App\Http\Resources\InvoicePaymentResource;
use App\{
    Http\Requests\Api\Sales\ReasonRequest, Invoice, InvoicePayment
};
use App\Repositories\Sales\PaymentRepository;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Api\Sales
 */
class PaymentController extends ApiController
{
    /** @var PaymentRepository */
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $payments = $this->payment->apiIndex();
        return InvoicePaymentResource::collection($payments);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function todayIndex()
    {
        $payments = $this->payment->todayIndex();
        return InvoicePaymentResource::collection($payments);
    }

    /**
     * @param PaymentStoreRequest $request
     * @param Invoice $invoice
     * @return InvoicePaymentResource
     */
    public function store(PaymentStoreRequest $request, Invoice $invoice)
    {
        $payment = $this->payment->save($request, $invoice, true);
        return new InvoicePaymentResource($payment);
    }

    /**
     * @param InvoicePayment $payment
     * @return InvoicePaymentResource
     */
    public function show(InvoicePayment $payment)
    {
        $payment->load('invoice', 'customer', 'order');
        return new InvoicePaymentResource($payment);
    }

    /**
     * @param PaymentStoreRequest $request
     * @param InvoicePayment $payment
     * @return InvoicePaymentResource
     */
    public function update(PaymentStoreRequest $request, InvoicePayment $payment)
    {
        $payment = $this->payment->update($request, $payment, true);
        return new InvoicePaymentResource($payment);
    }

    /**
     * @param InvoicePayment $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(InvoicePayment $payment)
    {
        $response = $this->payment->delete($payment);
        return response()->json($response);
    }


    /**
     * @param ReasonRequest $request
     * @param InvoicePayment $payment
     * @return InvoicePaymentResource
     */
    public function cancel(ReasonRequest $request,  InvoicePayment $payment)
    {
        $request->merge(['cancel_notes_payment' => $request->input('reason')]);
        $payment = $this->payment->cancelPayment($payment, $request);
        return new InvoicePaymentResource($payment);
    }

    /**
     * @param ReasonRequest $request
     * @param InvoicePayment $payment
     * @return InvoicePaymentResource
     */
    public function refund(ReasonRequest $request,  InvoicePayment $payment)
    {
        $request->merge(['refund_notes_payment' => $request->input('reason')]);
        $payment = $this->payment->refundPayment($payment, $request);
        return new InvoicePaymentResource($payment);
    }
}
