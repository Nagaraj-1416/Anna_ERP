<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="Date"))
 */

class Date
{
    /**
     * @SWG\Property(
     *     format="string",
     *     title="Date and time",
     *     description="Date and time",
     * )
     * @var string
     */
    public $date;

    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Timezone type",
     *     description="Timezone type",
     * )
     * @var int
     */
    public $timezone_type;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Timezone",
     *     description="Timezone",
     * )
     * @var string
     */
    public $timezone;
}