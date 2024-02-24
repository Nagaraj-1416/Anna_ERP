<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="Route"))
 */

class Route
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Route Id",
     *     description="Route Id",
     * )
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Route code",
     *     description="Route code",
     * )
     * @var string
     */
    public $code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Route name",
     *     description="Route name",
     * )
     * @var string
     */
    public $name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Route notes",
     *     description="Route notes",
     * )
     * @var string
     */
    public $notes;

    /**
     * @SWG\Property(
     *     format="string",
     *     enum={"Yes", "No"},
     *     title="Is active Route?",
     *     description="Is active Route?",
     * )
     * @var string
     */
    public $is_active;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Route staring point",
     *     description="Route staring point",
     *     ref="#/definitions/GPSPoint"
     * )
     * @var object
     */
    public $start_point;


    /**
     * @SWG\Property(
     *     format="object",
     *     title="Route ending point",
     *     description="Route ending point",
     *     ref="#/definitions/GPSPoint"
     * )
     * @var object
     */
    public $end_point;

    /**
     * @SWG\Property(
     *     format="array",
     *     title="Route way points",
     *     description="Route way points",
     *     type="array",
     *     @SWG\Items(
     *         ref="#/definitions/GPSPoint"
     *      )
     * )
     * @var array
     */
    public $way_points;
    
    /**
     * @SWG\Property(
     *     format="object",
     *     title="Route created at",
     *     description="Route created at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $created_at;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Route last updated at",
     *     description="Route last updated at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $updated_at;
}