<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="Handover"))
 */

class Handover
{
    /**
     * @SWG\Property(
     *     format="object",
     *     title="Collections details",
     *     description="Collections details",
     *     ref="#/definitions/Collections"
     * )
     * @var object
     */
    public $collections;

    /**
     * @SWG\Property(
     *     format="double",
     *     title="Today allowance amount",
     *     description="Today allowance amount",
     * )
     * @var double
     */
    public $allowance;

    /**
     * @SWG\Property(
     *     format="double",
     *     title="Mileage rate amount",
     *     description="Mileage rate amount",
     * )
     * @var double
     */
    public $mileage_rate;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Today allocated customers id",
     *     description="Today allocated customers id",
     * )
     * @var object
     */
    public $today_allocated_customers;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Today visited allocated customers id",
     *     description="Today visited allocated customers id",
     * )
     * @var object
     */
    public $today_visited_customers;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Today not visited allocated customers id",
     *     description="Today not visited allocated customers id",
     * )
     * @var object
     */
    public $today_not_visited_customers;
}