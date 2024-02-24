<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="Product"))
 */

class Product
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Product Id",
     *     description="Product Id",
     * )
     * @var int
     */
    public $id;


    /**
     * @SWG\Property(
     *     format="string",
     *     title="Product code",
     *     description="Product code",
     * )
     * @var string
     */
    public $code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Product name",
     *     description="Product name",
     * )
     * @var string
     */
    public $name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="product tamil name",
     *     description="product tamil name",
     * )
     * @var string
     */
    public $tamil_name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Product type",
     *     description="Product type",
     *     enum={"Raw Material", "Finished Good", "Third Party Product"},
     * )
     * @var string
     */
    public $type;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Product base buying price",
     *     description="Product base buying price",
     * )
     * @var float
     */
    public $base_buying_price;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Product base whole sales price",
     *     description="Product base whole sales price",
     * )
     * @var float
     */
    public $base_wholesale_price;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Product base retail price",
     *     description="Product base retail price",
     * )
     * @var float
     */
    public $base_retail_price;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Product base distribution price",
     *     description="Product base distribution price",
     * )
     * @var float
     */
    public $base_distribution_price;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Product measurement",
     *     description="Product measurement",
     * )
     * @var string
     */
    public $measurement;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Product min stock level",
     *     description="Product min stock level",
     * )
     * @var float
     */
    public $min_stock_level;


    /**
     * @SWG\Property(
     *     format="string",
     *     title="Product notes",
     *     description="Product notes",
     * )
     * @var float
     */
    public $notes;

    /**
     * @SWG\Property(
     *     format="string",
     *     enum={"Yes", "No"},
     *     title="Is active product?",
     *     description="Is active product?",
     * )
     * @var string
     */
    public $is_active;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Product created at",
     *     description="Product created at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $created_at;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Product last updated at",
     *     description="Product last updated at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $updated_at;
}