<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="Address"))
 */

class Address
{
    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $street_one;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $street_two;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $city;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $province;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $postal_code;

    /**
     * @SWG\Property(format="int")
     * @var int
     */
    public $country_id;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Country",
     *     description="Country",
     *     ref="#/definitions/Country"
     * )
     * @var object
     */
    public $country;
}