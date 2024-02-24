<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(required={"name"}, type="object", @SWG\Xml(name="Response"))
 */

class Response
{
    /**
     * @SWG\Property(format="boolean")
     * @var boolean
     */
    public $success;

    /**
     * @SWG\Property(format="string", example="deleted success")
     * @var string
     */
    public $message;
}