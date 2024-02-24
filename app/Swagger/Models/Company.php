<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="Company"))
 */

class Company
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
     *     title="Company code",
     *     description="Company code",
     * )
     * @var string
     */
    public $code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Company name",
     *     description="Company name",
     * )
     * @var string
     */
    public $name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Company phone number",
     *     description="Company phone number",
     * )
     * @var string
     */
    public $phone;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Company fax number",
     *     description="Company fax number",
     * )
     * @var string
     */
    public $fax;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Company fax mobile",
     *     description="Company fax mobile",
     * )
     * @var string
     */
    public $mobile;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Company email address",
     *     description="Company email address",
     * )
     * @var string
     */
    public $email;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Company website address",
     *     description="Company website address",
     * )
     * @var string
     */
    public $website;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Company location point",
     *     description="Company location point",
     *     ref="#/definitions/GPSPoint"
     * )
     * @var string
     */
    public $business_location;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Company base currency",
     *     description="Company base currency",
     * )
     * @var string
     */
    public $base_currency;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Company financial year start on",
     *     description="Company financial year start on",
     * )
     * @var string
     */
    public $fiscal_year_start;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Company financial year end on",
     *     description="Company financial year end on",
     * )
     * @var string
     */
    public $fiscal_year_end;


    /**
     * @SWG\Property(
     *     format="string",
     *     title="Company timezone",
     *     description="Company timezone",
     * )
     * @var string
     */
    public $timezone;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Company date time format",
     *     description="Company date time format",
     * )
     * @var string
     */
    public $date_time_format;

    /**
     * @SWG\Property(
     *     format="string",
     *     enum={"Yes", "No"},
     *     title="Is active Company?",
     *     description="Is active Company?",
     * )
     * @var string
     */
    public $is_active;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Country of the company",
     *     description="Country of the company",
     *     ref="#/definitions/Country"
     * )
     * @var object
     */
    public $country;

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