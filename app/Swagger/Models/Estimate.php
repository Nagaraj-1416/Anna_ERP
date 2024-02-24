<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="Estimate"))
 */

class Estimate
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Estimate Id",
     *     description="Estimate Id",
     * )
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Estimate No",
     *     description="Estimate No",
     * )
     * @var string
     */
    public $estimate_no;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Estimate date",
     *     description="Estimate date",
     * )
     * @var string
     */
    public $estimate_date;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Estimate expiry date",
     *     description="Estimate expiry date",
     * )
     * @var string
     */
    public $expiry_date;

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
     *     title="order status of sales order",
     *     description="order status of sales order",
     * )
     * @var float
     */
    public $order_status;


    /**
     * @SWG\Property(
     *     format="integer",
     *     title="Estimate creator id",
     *     description="Estimate creator id",
     * )
     * @var float
     */
    public $prepared_by;


    /**
     * @SWG\Property(
     *     format="integer",
     *     title="Customer id",
     *     description="Customer id",
     * )
     * @var float
     */
    public $customer_id;

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
     *     title="Estimate created at",
     *     description="Estimate created at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $created_at;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Estimate last updated at",
     *     description="Estimate last updated at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $updated_at;
}