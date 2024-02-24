<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="Country"))
 */

class Country
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Customer Id",
     *     description="Customer Id",
     * )
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Capital of the country",
     *     description="Capital of the country",
     * )
     * @var string
     */
    public $capital;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Citizenship of the country",
     *     description="Citizenship of the country",
     * )
     * @var string
     */
    public $citizenship;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Country code of the country",
     *     description="Country code of the country",
     * )
     * @var string
     */
    public $country_code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Currency of country",
     *     description="Currency of country",
     * )
     * @var string
     */
    public $currency;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Currency code of country",
     *     description="Currency code of country",
     * )
     * @var string
     */
    public $currency_code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Currency sub unit of country",
     *     description="Currency sub unit of country",
     * )
     * @var string
     */
    public $currency_sub_unit;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Currency symbol of country",
     *     description="Currency symbol of country",
     * )
     * @var string
     */
    public $currency_symbol;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Full name of country",
     *     description="Full name of country",
     * )
     * @var string
     */
    public $full_name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="iso 3166 2 of country",
     *     description="iso 3166 2 of country",
     * )
     * @var string
     */
    public $iso_3166_2;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="iso 3166 3 of country",
     *     description="iso 3166 3 of country",
     * )
     * @var string
     */
    public $iso_3166_3;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="name of country",
     *     description="name of country",
     * )
     * @var string
     */
    public $name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Region code of country",
     *     description="Region code of country",
     * )
     * @var string
     */
    public $region_code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Sub region code of country",
     *     description="Sub region code of country",
     * )
     * @var string
     */
    public $sub_region_code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="eea of country",
     *     description="eea of country",
     * )
     * @var string
     */
    public $eea;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Calling code of country",
     *     description="Calling code of country",
     * )
     * @var string
     */
    public $calling_code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="flag of country",
     *     description="flag of country",
     * )
     * @var string
     */
    public $flag;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Customer created at",
     *     description="Customer created at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $created_at;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Customer last updated at",
     *     description="Customer last updated at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $updated_at;
}