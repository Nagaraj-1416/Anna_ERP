<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="RouteLocation"))
 */

class RouteLocation
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Route location Id",
     *     description="Route location Id",
     * )
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Route location code",
     *     description="Route location code",
     * )
     * @var string
     */
    public $code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Route location name",
     *     description="Route location name",
     * )
     * @var string
     */
    public $name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Route location notes",
     *     description="Route location notes",
     * )
     * @var string
     */
    public $notes;

    /**
     * @SWG\Property(
     *     format="string",
     *     enum={"Yes", "No"},
     *     title="Is active Route location?",
     *     description="Is active Route location?",
     * )
     * @var string
     */
    public $is_active;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Route location created at",
     *     description="Route location created at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $created_at;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Route location last updated at",
     *     description="Route location last updated at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $updated_at;
}