<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="Date"))
 */

class GPSPoint
{
    /**
     * @SWG\Property(
     *     format="float",
     *     title="latitude of point",
     *     description="latitude of point",
     * )
     * @var float
     */
    public $lat;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="longitude of point",
     *     description="longitude of point",
     * )
     * @var float
     */
    public $lng;
}