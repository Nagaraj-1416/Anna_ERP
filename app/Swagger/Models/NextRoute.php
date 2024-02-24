<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(required={"name"}, type="object", @SWG\Xml(name="Delete"))
 */

class NextRoute
{
    /**
     * @SWG\Property(format="boolean")
     * @var boolean
     */
    public $success;

    /**
     * @SWG\Property(format="string", example="Next day allocation updated")
     * @var string
     */
    public $message;
}
