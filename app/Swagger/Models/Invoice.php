<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="Invoice"))
 */

class Invoice
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Invoice Id",
     *     description="Invoice Id",
     * )
     * @var int
     */
    public $id;


    /**
     * @SWG\Property(
     *     format="string",
     *     title="Invoice no",
     *     description="Invoice no",
     * )
     * @var string
     */
    public $invoice_no;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Invoice date",
     *     description="Invoice date",
     * )
     * @var string
     */
    public $invoice_date;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Invoice due date",
     *     description="Invoice  due date",
     * )
     * @var string
     */
    public $due_date;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Invoice type",
     *     description="Invoice type",
     *     enum={"Proforma Invoice", "Invoice"},
     * )
     * @var string
     */
    public $invoice_type;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Invoice amount",
     *     description="Invoice amount",
     * )
     * @var float
     */
    public $amount;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Invoice prepared by user id",
     *     description="Invoice prepared by user id",
     * )
     * @var int
     */
    public $prepared_by;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Invoice approval status",
     *     description="Invoice approval status",
     *     enum={"Pending", "Approved", "Rejected"},
     * )
     * @var string
     */
    public $approval_status;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Invoice approved by user id",
     *     description="Invoice approved by user id",
     * )
     * @var int
     */
    public $approved_by;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Invoice status",
     *     description="Invoice status",
     *     enum={"Draft", "Open", "Overdue", "Partially Paid", "Paid", "Canceled", "Refunded"},
     * )
     * @var string
     */
    public $status;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Invoice notes",
     *     description="Invoice notes",
     * )
     * @var string
     */
    public $notes;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Invoice sales order id",
     *     description="Invoice sales order id",
     * )
     * @var int
     */
    public $sales_order_id;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Invoice customer id",
     *     description="Invoice customer id",
     * )
     * @var int
     */
    public $customer_id;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Invoice business type id",
     *     description="Invoice business type id",
     * )
     * @var int
     */
    public $business_type_id;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Invoice company id",
     *     description="Invoice company id",
     * )
     * @var int
     */
    public $company_id;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Invoicing customer",
     *     description="Invoicing customer",
     *     ref="#/definitions/Customer"
     * )
     * @var object
     */
    public $customer;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Invoice created at",
     *     description="Invoice created at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $created_at;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Invoice last updated at",
     *     description="Invoice last updated at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $updated_at;
}