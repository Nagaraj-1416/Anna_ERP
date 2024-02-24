<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(required={"name"}, type="object", @SWG\Xml(name="Rep"))
 */

class Rep
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Rep Id",
     *     description="Rep Id",
     * )
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Rep code",
     *     description="Rep code",
     * )
     * @var string
     */
    public $code;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Rep name",
     *     description="Rep name",
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
     *     title="Rep email",
     *     description="Rep email",
     * )
     * @var string
     */
    public $email;

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
     *     format="int64",
     *     title="Rep staff id",
     *     description="Rep staff id",
     * )
     * @var int
     */
    public $staff_id;
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Rep's vehicle id",
     *     description="Rep's vehicle id",
     * )
     * @var int
     */
    public $vehicle_id;

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