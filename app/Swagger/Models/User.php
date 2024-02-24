<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(required={"name"}, type="object", @SWG\Xml(name="User"))
 */

class User
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="User Id",
     *     description="User Id",
     * )
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="User name",
     *     description="User name",
     * )
     * @var int
     */
    public $name;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="User email",
     *     description="User email",
     * )
     * @var int
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
     *     format="object",
     *     title="User created at",
     *     description="User created at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $created_at;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="User last updated at",
     *     description="User last updated at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $updated_at;
}