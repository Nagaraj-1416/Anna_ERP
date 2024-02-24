<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Sales\InvoiceStoreRequest;
use App\Http\Requests\Api\Sales\ReasonRequest;
use App\Http\Resources\InvoiceResource;
use App\Invoice;
use App\Repositories\Sales\InvoiceRepository;
use App\SalesOrder;

class InvoiceController extends ApiController
{
    /**
     * @var InvoiceRepository
     */
    protected $invoice;

    /**
     * InvoiceController constructor.
     * @param InvoiceRepository $invoice
     */
    public function __construct(InvoiceRepository $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $invoices = $this->invoice->apiIndex();
        return InvoiceResource::collection($invoices);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function todayIndex()
    {
        $invoices = $this->invoice->todayIndex();
        return InvoiceResource::collection($invoices);
    }

    /**
     * @param InvoiceStoreRequest $request
     * @param SalesOrder $order
     * @return InvoiceResource
     */
    public function store(InvoiceStoreRequest $request, SalesOrder $order)
    {
        $invoice = $this->invoice->save($request, $order, true);
        return new InvoiceResource($invoice);
    }

    /**
     * @param Invoice $invoice
     * @return InvoiceResource
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('company', 'customer');
        $payments = $invoice->payments()->where('status', 'Paid')->get();
        $invoice->setRelation('payments', $payments);
        return new InvoiceResource($invoice);
    }

    /**
     * @param InvoiceStoreRequest $request
     * @param Invoice $invoice
     * @return InvoiceResource
     */
    public function update(InvoiceStoreRequest $request, Invoice $invoice)
    {
        $this->invoice->update($request, $invoice);
        return new InvoiceResource($invoice);
    }

    /**
     * @param Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Invoice $invoice)
    {
        $response = $this->invoice->delete($invoice);
        return response()->json($response);
    }

    /**
     * @param ReasonRequest $request
     * @param Invoice $invoice
     * @return InvoiceResource
     */
    public function cancel(ReasonRequest $request,  Invoice $invoice)
    {
        $request->merge(['cancel_notes_invoice' => $request->input('reason')]);
        $invoice = $this->invoice->cancelInvoice($invoice, $request);
        return new InvoiceResource($invoice);
    }

    /**
     * @param ReasonRequest $request
     * @param Invoice $invoice
     * @return InvoiceResource
     */
    public function refund(ReasonRequest $request,  Invoice $invoice)
    {
        $request->merge(['refund_notes_invoice' => $request->input('reason')]);
        $invoice = $this->invoice->refundInvoice($invoice, $request);
        return new InvoiceResource($invoice);
    }
}
