<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="InvoicePayment"))
 */

class InvoicePayment
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Payment Id",
     *     description="Payment Id",
     * )
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Payment no",
     *     description="Payment no",
     * )
     * @var float
     */
    public $payment;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Payment date",
     *     description="Payment date",
     * )
     * @var string
     */
    public $payment_date;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Payment type",
     *     description="Payment type",
     *     enum={"Advanced", "Partial Payment", "Final Payment"},
     * )
     * @var string
     */
    public $payment_type;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Payment mode",
     *     description="Payment mode",
     *     enum={"Cash", "Cheque", "Direct Deposit"},
     * )
     * @var string
     */
    public $payment_mode;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Payment type",
     *     description="Payment from",
     *     enum={"Direct", "Credit"},
     * )
     * @var string
     */
    public $payment_from;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Payment cheque no",
     *     description="Payment cheque no",
     * )
     * @var string
     */
    public $cheque_no;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Payment cheque date",
     *     description="Payment cheque date",
     * )
     * @var string
     */
    public $cheque_date;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Payment account no",
     *     description="Payment account no",
     * )
     * @var string
     */
    public $account_no;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Payment deposited date",
     *     description="Payment deposited date",
     * )
     * @var string
     */
    public $deposited_date;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Credit card holder name",
     *     description="Credit card holder name",
     * )
     * @var string
     */
    public $card_holder_name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Credit card number",
     *     description="Credit card number",
     * )
     * @var string
     */
    public $card_no;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Credit card expiry_date",
     *     description="Credit card expiry_date",
     * )
     * @var string
     */
    public $expiry_date;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Payment deposited bank id",
     *     description="Payment deposited bank id",
     * )
     * @var string
     */
    public $bank_id;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Payment type",
     *     description="Payment from",
     *     enum={"Paid", "Canceled", "Refunded", "Deleted"},
     * )
     * @var string
     */
    public $status;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Payment notes",
     *     description="Payment notes",
     * )
     * @var string
     */
    public $notes;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Customer",
     *     description="Customer details",
     *     ref="#/definitions/Customer",
     * )
     * @var string
     */
    public $customer;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Payment prepared by user id",
     *     description="Payment prepared by user id",
     * )
     * @var int
     */
    public $prepared_by;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Payment invoice id",
     *     description="Payment invoice id",
     * )
     * @var int
     */
    public $invoice_id;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Payment sales order id",
     *     description="Payment sales order id",
     * )
     * @var int
     */
    public $sales_order_id;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Payment customer id",
     *     description="Payment customer id",
     * )
     * @var int
     */
    public $customer_id;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Payment business type id",
     *     description="Payment business type id",
     * )
     * @var int
     */
    public $business_type_id;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Payment company id",
     *     description="Payment company id",
     * )
     * @var int
     */
    public $company_id;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Payment deposited to",
     *     description="Payment deposited to",
     * )
     * @var int
     */
    public $deposited_to;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Payment created at",
     *     description="Payment created at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $created_at;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Payment last updated at",
     *     description="Payment last updated at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $updated_at;
}