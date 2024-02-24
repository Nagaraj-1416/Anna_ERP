<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="Price"))
 */

class Price
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Price Id",
     *     description="Price  Id",
     * )
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Price book code",
     *     description="Price book code",
     *     enum={"Wholesale", "Retail", "Distribution"},
     * )
     * @var string
     */
    public $type;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="price",
     *     description="price",
     * )
     * @var float
     */
    public $price;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Price unit type id",
     *     description="Price unit type id",
     * )
     * @var int
     */
    public $unit_type_id;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Price product type id",
     *     description="Price product type id",
     * )
     * @var int
     */
    public $product_id;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Price book id",
     *     description="Price book id",
     * )
     * @var int
     */
    public $price_book_id;


    /**
     * @SWG\Property(
     *     format="object",
     *     title="Rep created at",
     *     description="Rep created at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $created_at;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Rep last updated at",
     *     description="Rep last updated at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $updated_at;
}