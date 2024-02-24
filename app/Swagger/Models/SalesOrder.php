<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(required={"name"}, type="object", @SWG\Xml(name="SalesOrder"))
 */

class SalesOrder
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Sales order Id",
     *     description="Sales order Id",
     * )
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Sales order No",
     *     description="Sales order No",
     * )
     * @var string
     */
    public $order_no;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Sales order date",
     *     description="Sales order date",
     * )
     * @var string
     */
    public $order_date;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Sales order delivery date",
     *     description="Sales order delivery date",
     * )
     * @var string
     */
    public $delivery_date;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Sales order type",
     *     description="Sales order type",
     * )
     * @var string
     */
    public $order_type;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Sales order scheduled date",
     *     description="Sales order scheduled date",
     * )
     * @var string
     */
    public $scheduled_date;

    /**
     * @SWG\Property(
     *     format="string",
     *     enum={"Yes", "No"},
     *     title="Is PO received for sales order?",
     *     description="Is PO received for sales order?",
     * )
     * @var string
     */
    public $is_po_received;

    /**
     * @SWG\Property(
     *     format="string",
     *     enum={"Yes", "No"},
     *     title="Is order printed?",
     *     description="Is order printed?",
     * )
     * @var string
     */
    public $is_order_printed;

    /**
     * @SWG\Property(
     *     format="string",
     *     enum={"Yes", "No"},
     *     title="Is credit sales?",
     *     description="Is credit sales?",
     * )
     * @var string
     */
    public $is_credit_sales;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="PO number",
     *     description="PO number",
     *     )
     * @var string
     */
    public $po_no;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="PO date",
     *     description="PO date",
     * )
     * @var string
     */
    public $po_date;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="terms of sales order",
     *     description="terms for sales order",
     * )
     * @var string
     */
    public $terms;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="notes of sales order",
     *     description="notes for sales order",
     * )
     * @var string
     */
    public $notes;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="sub total of sales order",
     *     description="sub total of sales order",
     * )
     * @var float
     */
    public $sub_total;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="discount amount for sales order",
     *     description="discount amount for sales order",
     * )
     * @var float
     */
    public $discount;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="discount  rate for sales order",
     *     description="discount rate for sales order",
     * )
     * @var float
     */
    public $discount_rate;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="discount type of the sales order",
     *     description="discount type of the sales order",
     * )
     * @var string
     */
    public $discount_type;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="adjustment for sales order",
     *     description="adjustment for sales order",
     * )
     * @var float
     */
    public $adjustment;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="total amount of sales order",
     *     description="total amount of sales order",
     * )
     * @var float
     */
    public $total;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="status of sales order",
     *     description="status of sales order",
     * )
     * @var float
     */
    public $status;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="delivery status of sales order",
     *     description="delivery status of sales order",
     * )
     * @var float
     */
    public $delivery_status;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="invoice status of sales order",
     *     description="invoice status of sales order",
     * )
     * @var float
     */
    public $invoice_status;

    /**
     * @SWG\Property(
     *     format="string",
     *     enum={"Yes", "No"},
     *     title="Is invoice created for sales order?",
     *     description="Is invoice created for sales order?",
     * )
     * @var float
     */
    public $is_invoiced;

    /**
     * @SWG\Property(
     *     format="integer",
     *     title="Sales order creator id",
     *     description="Sales order creator id",
     * )
     * @var float
     */
    public $prepared_by;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Sales order approval status",
     *     description="Sales order approval status",
     * )
     * @var float
     */
    public $approval_status;

    /**
     * @SWG\Property(
     *     format="integer",
     *     title="Sales order approver id",
     *     description="Sales order approver id",
     * )
     * @var int
     */
    public $approved_by;

    /**
     * @SWG\Property(
     *     format="integer",
     *     title="Customer id",
     *     description="Customer id",
     * )
     * @var int
     */
    public $customer_id;

    /**
     * @SWG\Property(
     *     format="integer",
     *     title="Price book id",
     *     description="Price book id",
     * )
     * @var int
     */
    public $price_book_id;

    /**
     * @SWG\Property(
     *     format="integer",
     *     title="Sales rep id",
     *     description="Sales rep id",
     * )
     * @var int
     */
    public $rep_id;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Sales type",
     *     description="Sales type",
     *     enum={"Retail", "Wholesale", "Distribution"}
     * )
     * @var string
     */
    public $sales_type;

    /**
     * @SWG\Property(
     *     format="integer",
     *     title="Business type id",
     *     description="Business type id",
     * )
     * @var float
     */
    public $business_type_id;

    /**
     * @SWG\Property(
     *     format="integer",
     *     title="company id",
     *     description="company id",
     * )
     * @var float
     */
    public $company_id;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="sales order products",
     *     description="sales order products",
     * )
     * @var object
     */
    public $products;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="sales order items",
     *     description="sales order items",
     * )
     * @var object
     */
    public $order_items;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Customer",
     *     description="Customer",
     *     ref="#/definitions/Customer"
     * )
     * @var object
     */
    public $customer;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Order GPS latitude",
     *     description="Order GPS latitude",
     * )
     * @var string
     */
    public $gps_lat;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Order GPS longitude",
     *     description="Order GPS longitude",
     * )
     * @var string
     */
    public $gps_long;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Sales order created at",
     *     description="Sales order created at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $created_at;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Sales order last updated at",
     *     description="Sales order last updated at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $updated_at;
}