<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="PriceBookShow"))
 */

class PriceBookShow
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Price book Id",
     *     description="Price book Id",
     * )
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Price book code",
     *     description="Price book code",
     * )
     * @var string
     */
    public $code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Price book name",
     *     description="Price book name",
     * )
     * @var string
     */
    public $name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Rep notes",
     *     description="Rep notes",
     * )
     * @var string
     */
    public $notes;

    /**
     * @SWG\Property(
     *     format="string",
     *     enum={"Yes", "No"},
     *     title="Is active customer?",
     *     description="Is active customer?",
     * )
     * @var string
     */
    public $is_active;

    /**
     * @SWG\Property(
     *     format="string",
     *     enum={"Production To Store", "Store To Shop", "Shop Price", "Van Price"},
     *     title="Price book type",
     *     description="Price book type",
     * )
     * @var string
     */
    public $type;


    /**
     * @SWG\Property(
     *     format="array",
     *     title=" prices",
     *     description="prices",
     *     type="array",
     *     @SWG\Items(
     *         ref="#/definitions/Price"
     *      )
     * )
     * @var array
     */
    public $prices;

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