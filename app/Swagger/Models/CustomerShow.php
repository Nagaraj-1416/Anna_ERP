<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(required={"name"}, type="object", @SWG\Xml(name="CustomerShow"))
 */

class CustomerShow
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
     *     title="Customer code",
     *     description="Customer code",
     * )
     * @var string
     */
    public $code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Customer salutation",
     *     description="Customer salutation",
     * )
     * @var string
     */
    public $salutation;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Customer first name",
     *     description="Customer first name",
     * )
     * @var string
     */
    public $first_name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Customer last name",
     *     description="Customer last name",
     * )
     * @var string
     */
    public $last_name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Customer full name",
     *     description="Customer full name",
     * )
     * @var string
     */
    public $full_name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Customer display name",
     *     description="Customer display name",
     * )
     * @var string
     */
    public $display_name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Customer tamil name",
     *     description="Customer tamil name",
     * )
     * @var string
     */
    public $tamil_name;

    /**
     * @SWG\Property(
     *     format="msisdn",
     *     title="Customer phone number",
     *     description="Customer phone number",
     *     )
     * @var string
     */
    public $phone;

    /**
     * @SWG\Property(
     *     format="msisdn",
     *     title="Customer fax number",
     *     description="Customer fax number",
     * )
     * @var string
     */
    public $fax;

    /**
     * @SWG\Property(
     *     format="msisdn",
     *     title="Customer mobile number",
     *     description="Customer mobile number",
     * )
     * @var string
     */
    public $mobile;

    /**
     * @SWG\Property(
     *     format="email",
     *     title="Customer email address",
     *     description="Customer email address",
     * )
     * @var string
     */
    public $email;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Customer website address",
     *     description="Customer website address",
     * )
     * @var string
     */
    public $website;

    /**
     * @SWG\Property(
     *     format="string",
     *     enum={"Internal", "External"},
     *     title="Customer type",
     *     description="Customer type",
     * )
     * @var string
     */
    public $type;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Customer GPS latitude",
     *     description="Customer GPS latitude",
     * )
     * @var string
     */
    public $gps_lat;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Customer GPS longitude",
     *     description="Customer GPS longitude",
     * )
     * @var string
     */
    public $gps_long;

    /**
     * @SWG\Property(
     *     format="string",
     *     title=" Note about customer",
     *     description="Note about customer",
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
     *     format="object",
     *     title="Outstanding summary",
     *     description="Outstanding summary",
     *     ref="#/definitions/Outstanding"
     * )
     * @var object
     */
    public $outstanding;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Max credit limit amount",
     *     description="Max credit limit amount",
     * )
     * @var float
     */
    public $credit_limit_amount;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Max credit limit notification rate",
     *     description="Max credit limit notification rate",
     * )
     * @var float
     */
    public $credit_limit_notify_rate;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Route of the customer",
     *     description="Route of the customer",
     *     ref="#/definitions/Route"
     * )
     * @var object
     */
    public $route;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Route location of the customer",
     *     description="Route location of the customer",
     *     ref="#/definitions/RouteLocation"
     * )
     * @var object
     */
    public $location;

    /**
     * @SWG\Property(
     *     format="array",
     *     title="addresses of the customer",
     *     description="addresses of the customer",
     *     type="array",
     *     @SWG\Items(
     *         ref="#/definitions/Address"
     *      )
     * )
     * @var array
     */
    public $addresses;

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