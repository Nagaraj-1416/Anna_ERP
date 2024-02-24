<?php

namespace App\Swagger\Controllers\Api\Sales;

use App\Http\Requests\Api\Sales\InvoiceStoreRequest;
use App\Invoice;
use App\SalesOrder;

/**
 * Class InvoiceController
 * @package App\Swagger\Controllers\Api\Common
 */
class InvoiceController
{
    /**
     * @SWG\Get(
     *     path="/sales/invoices",
     *     summary="Get sales invoices",
     *     tags={"Invoice"},
     *     description="List related sales invoices . This API call is available to authenticated users.",
     *     operationId="invoiceIndex",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Invoice")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function index()
    {

    }

    /**
     * @SWG\Get(
     *     path="/sales/invoices/for-today",
     *     summary="Get today sales invoices",
     *     tags={"Invoice"},
     *     description="List today related sales invoices . This API call is available to authenticated users.",
     *     operationId="invoiceTodayIndex",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/InvoiceShow")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function todayIndex()
    {

    }

    /**
     * @SWG\Get(
     *     path="/sales/invoices/{invoiceId}",
     *     summary="Find invoice by ID",
     *     description="Returns a single invoice",
     *     operationId="invoiceShow",
     *     tags={"Invoice"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of invoice to return",
     *         in="path",
     *         name="invoiceId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/InvoiceShow")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Invoice not found"
     *     )
     * )
     */
    public function show(Invoice $invoice)
    {

    }

    /**
     * @SWG\Post(
     *   path="/sales/invoices/{salesOrderId}",
     *   tags={"Invoice"},
     *   summary="Create a invoice to a sales order",
     *   description="Create a invoice to a sales order",
     *   operationId="createInvoice",
     *   produces={"application/json"},
     *    @SWG\Parameter(
     *         description="Invoicing sales order Id",
     *         in="path",
     *         name="salesOrderId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="invoice_date",
     *     in="body",
     *     description="Invoicing date",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="2018-01-15"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="due_date",
     *     in="body",
     *     description="Invoice due date",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="2018-01-15"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="amount",
     *     in="body",
     *     description="Invoice amount",
     *     required=true,
     *     @SWG\Schema(
     *       type="float",
     *       example="1000.50"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Invoice")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Invoice not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function store(InvoiceStoreRequest $request, SalesOrder $order)
    {

    }

    /**
     * @SWG\Patch(
     *   path="/sales/invoices/{invoiceId}",
     *   tags={"Invoice"},
     *   summary="Update invoice by Id",
     *   description="Update invoice by Id",
     *   operationId="updateInvoice",
     *   produces={"application/json"},
     *    @SWG\Parameter(
     *         description="Id of invoice to update",
     *         in="path",
     *         name="invoiceId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="invoice_date",
     *     in="body",
     *     description="Invoicing date",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="2018-01-15"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="due_date",
     *     in="body",
     *     description="Invoice due date",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="2018-01-15"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="amount",
     *     in="body",
     *     description="Invoice amount",
     *     required=false,
     *     @SWG\Schema(
     *       type="float",
     *       example="1000.50"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Invoice")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Invoice not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function update(InvoiceStoreRequest $request, Invoice $invoice)
    {

    }

    /**
     * @SWG\Post(
     *   path="/sales/invoices/{invoiceId}/cancel",
     *   tags={"Invoice"},
     *   summary="Cancel invoice by Id",
     *   description="Cancel a invoice",
     *   operationId="cancelInvoice",
     *   produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of invoice to cancel",
     *         in="path",
     *         name="invoiceId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="reason",
     *     in="body",
     *     description="reason of cancel invoice",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Invoice")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Invoice not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function cancel()
    {

    }


    /**
     * @SWG\Post(
     *   path="/sales/invoices/{invoiceId}/refund",
     *   tags={"Invoice"},
     *   summary="Refund invoice by Id",
     *   description="refund a invoice",
     *   operationId="refundInvoice",
     *   produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of invoice to refund",
     *         in="path",
     *         name="invoiceId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="reason",
     *     in="body",
     *     description="reason of refund invoice",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Invoice")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Invoice not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function refund()
    {

    }

    /**
     * @SWG\Delete(
     *    path="/sales/invoices/{invoiceId}",
     *     summary="Delete invoice by ID",
     *     tags={"Invoice"},
     *     description="Delete a invoice. This API call is available to authenticated users.",
     *     operationId="deleteInvoice",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="deleting invoice id",
     *         in="path",
     *         name="invoiceId",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Delete")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *  @SWG\Response(
     *         response="404",
     *         description="Invoice not found"
     *   ),
     *     deprecated=false
     * )
     */
    public function delete()
    {

    }
}