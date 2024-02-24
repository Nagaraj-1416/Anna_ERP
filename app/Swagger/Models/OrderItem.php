<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="OrderItem"))
 */

class OrderItem
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Product Id",
     *     description="Product Id",
     * )
     * @var int
     */
    public $product_id;


    /**
     * @SWG\Property(
     *     format="string",
     *     title="Product name",
     *     description="Product name",
     * )
     * @var string
     */
    public $product_name;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Price Book  Id",
     *     description="Price Book  Id",
     * )
     * @var int
     */
    public $price_book_id;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Store Id",
     *     description="Store Id",
     * )
     * @var int
     */
    public $store_id;


    /**
     * @SWG\Property(
     *     format="string",
     *     title="Store name",
     *     description="Store name",
     * )
     * @var string
     */
    public $store_name;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Item quantity",
     *     description="Item quantity",
     * )
     * @var float
     */
    public $quantity;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Item rate",
     *     description="Item rate",
     * )
     * @var float
     */
    public $rate;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Item discount type",
     *     description="Item discount type",
     *     enum={"Amount", "percentage"}
     * )
     * @var string
     */
    public $discount_type;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Item discount rate",
     *     description="Item discount rate",
     * )
     * @var float
     */
    public $discount_rate;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Item discount",
     *     description="Item discount",
     * )
     * @var float
     */
    public $discount;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Item amount",
     *     description="Item amount",
     * )
     * @var float
     */
    public $amount;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Item status",
     *     description="Item status",
     *     enum={"Pending", "Partially Delivered", "Delivered", "Canceled"}
     * )
     * @var string
     */
    public $status;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Item notes",
     *     description="Item notes",
     * )
     * @var string
     */
    public $notes;
}