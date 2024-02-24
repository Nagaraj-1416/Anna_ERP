<?php

namespace App\Swagger\Controllers\Api\Sales;

/**
 * Class PaymentController
 * @package App\Swagger\Controllers\Api\Common
 */
class PaymentController
{
    /**
     * @SWG\Get(
     *     path="/sales/payments",
     *     summary="Get invoice payments",
     *     tags={"Invoice Payment"},
     *     description="List related invoice payments . This API call is available to authenticated users.",
     *     operationId="invoicePaymentIndex",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/InvoicePayment")
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
     *     path="/sales/payments/for-today",
     *     summary="Get today invoice payments",
     *     tags={"Invoice Payment"},
     *     description="List today related invoice payments . This API call is available to authenticated users.",
     *     operationId="invoicePaymentTodayIndex",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/InvoicePaymentShow")
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
     *     path="/sales/payments/{paymentId}",
     *     summary="Find invoice payment by ID",
     *     description="Returns a single invoice payment",
     *     operationId="invoicePaymentShow",
     *     tags={"Invoice Payment"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of payment to return",
     *         in="path",
     *         name="paymentId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/InvoicePaymentShow")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Invoice payment not found"
     *     )
     * )
     */
    public function show()
    {

    }

    /**
     * @SWG\Post(
     *   path="/sales/payments/{invoiceId}",
     *   tags={"Invoice Payment"},
     *   summary="Create a payment to a invoice",
     *   description="Create a payment for a invoice",
     *   operationId="createInvoicePayment",
     *   produces={"application/json"},
     *    @SWG\Parameter(
     *         description="Invoicing  Id",
     *         in="path",
     *         name="invoiceId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="payment",
     *     in="body",
     *     description="payment amount",
     *     required=true,
     *     @SWG\Schema(
     *       type="float",
     *       example="100.50"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="payment_date",
     *     in="body",
     *     description="Payment date",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="2018-01-15"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="deposited_to",
     *     in="body",
     *     description="Payment deposit account id",
     *     required=true,
     *     @SWG\Schema(
     *       type="int",
     *       example="1"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="payment_type",
     *     in="body",
     *     description="Payment type",
     *     required=true,
     *     @SWG\Schema(
     *       type="enum={'Advanced','Partial Payment', 'Final Payment'}",
     *       example="Advanced"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="payment_mode",
     *     in="body",
     *     description="Payment mode",
     *     required=true,
     *     @SWG\Schema(
     *       type="enum={'Cash','Cheque','Direct Deposit', 'Credit Card'}",
     *       example="Cash"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="cheque_no",
     *     in="body",
     *     description="Cheque number (It is required, if payment mode is Cheque. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="0978787767"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="cheque_date",
     *     in="body",
     *     description="Cheque date (It is required, if payment mode is Cheque. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="date",
     *       example="2018-05-10"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="cheque_type",
     *     in="body",
     *     description="Cheque Type",
     *     required=true,
     *     @SWG\Schema(
     *       type="enum={'Own','Third Party'}",
     *       example="Own"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="bank_id",
     *     in="body",
     *     description="Cheque number (It is required, if payment mode is Cheque or Direct Deposit. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="int",
     *       example="1"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="account_no",
     *     in="body",
     *     description="Account number (It is required, if payment mode is Direct Deposit. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="0978787767"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="deposited_date",
     *     in="body",
     *     description="Deposited date (It is required, if payment mode is Direct Deposit. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="date",
     *       example="2018-05-10"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="card_holder_name",
     *     in="body",
     *     description="Credit Card holder name (It is required, if payment mode is Credit Card. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example=""
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="card_no",
     *     in="body",
     *     description="Credit Card number (It is required, if payment mode is Credit Card. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example=""
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="expiry_date",
     *     in="body",
     *     description="Credit Card expiry date (It is required, if payment mode is Credit Card. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="date",
     *       example="2021-10-05"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="notes",
     *     in="body",
     *     description="Notes",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="sample note here"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/InvoicePayment")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Invoice payment not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid payment data supplied"
     *   )
     *)
     */
    public function store()
    {

    }



    /**
     * @SWG\Patch(
     *   path="/sales/payments/{paymentId}",
     *   tags={"Invoice Payment"},
     *   summary="Update invoice payment by Id",
     *   description="Update invoice payment by Id",
     *   operationId="updateInvoicePayment",
     *   produces={"application/json"},
     *    @SWG\Parameter(
     *         description="Id of Payment to update",
     *         in="path",
     *         name="paymentId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="payment",
     *     in="body",
     *     description="payment amount",
     *     required=true,
     *     @SWG\Schema(
     *       type="float",
     *       example="100.50"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="payment_date",
     *     in="body",
     *     description="Payment date",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="2018-01-15"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="deposited_to",
     *     in="body",
     *     description="Payment deposit account id",
     *     required=true,
     *     @SWG\Schema(
     *       type="int",
     *       example="1"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="payment_type",
     *     in="body",
     *     description="Payment type",
     *     required=true,
     *     @SWG\Schema(
     *       type="enum={'Advanced','Partial Payment', 'Final Payment'}",
     *       example="Advanced"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="payment_mode",
     *     in="body",
     *     description="Payment mode",
     *     required=true,
     *     @SWG\Schema(
     *       type="enum={'Cash','Cheque','Direct Deposit', 'Credit Card'}",
     *       example="Cash"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="cheque_no",
     *     in="body",
     *     description="Cheque number (It is required, if payment mode is Cheque. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="0978787767"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="cheque_date",
     *     in="body",
     *     description="Cheque date (It is required, if payment mode is Cheque. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="date",
     *       example="2018-05-10"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="cheque_type",
     *     in="body",
     *     description="Cheque Type",
     *     required=true,
     *     @SWG\Schema(
     *       type="enum={'Own','Third Party'}",
     *       example="Own"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="bank_id",
     *     in="body",
     *     description="Cheque number (It is required, if payment mode is Cheque or Direct Deposit. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="int",
     *       example="1"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="account_no",
     *     in="body",
     *     description="Account number (It is required, if payment mode is Direct Deposit. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="0978787767"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="deposited_date",
     *     in="body",
     *     description="Deposited date (It is required, if payment mode is Direct Deposit. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="date",
     *       example="2018-05-10"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="card_holder_name",
     *     in="body",
     *     description="Credit Card holder name (It is required, if payment mode is Credit Card. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example=""
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="card_no",
     *     in="body",
     *     description="Credit Card number (It is required, if payment mode is Credit Card. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example=""
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="expiry_date",
     *     in="body",
     *     description="Credit Card expiry date (It is required, if payment mode is Credit Card. )",
     *     required=true,
     *     @SWG\Schema(
     *       type="date",
     *       example="2021-10-05"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="notes",
     *     in="body",
     *     description="Notes",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="sample note here"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/InvoicePayment")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Invoice payment not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid payment data supplied"
     *   )
     *)
     */
    public function update()
    {

    }


    /**
     * @SWG\Post(
     *   path="/sales/payments/{paymentId}/cancel",
     *   tags={"Invoice Payment"},
     *   summary="Cancel invoice payment by Id",
     *   description="Cancel a invoice payment",
     *   operationId="cancelInvoicePayment",
     *   produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of invoice payment to cancel",
     *         in="path",
     *         name="paymentId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="reason",
     *     in="body",
     *     description="reason of cancel invoice payment",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/InvoicePayment")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Invoice payment not found"
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
     *   path="/sales/payments/{paymentId}/refund",
     *   tags={"Invoice Payment"},
     *   summary="Cancel invoice payment by Id",
     *   description="refund a invoice payment",
     *   operationId="refundInvoicePayment",
     *   produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of invoice payment to refund",
     *         in="path",
     *         name="paymentId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="reason",
     *     in="body",
     *     description="reason of refund invoice payment",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/InvoicePayment")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Invoice payment not found"
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
     *    path="/sales/payments/{paymentId}",
     *     summary="Delete invoice payment by ID",
     *     tags={"Invoice Payment"},
     *     description="Delete a invoice payment. This API call is available to authenticated users.",
     *     operationId="deleteInvoicePayment",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="deleting invoice payment id",
     *         in="path",
     *         name="paymentId",
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
     *         description="Invoice payment not found"
     *   ),
     *     deprecated=false
     * )
     */
    public function delete()
    {

    }
}