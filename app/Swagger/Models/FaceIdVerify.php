<?php

namespace App\Swagger\Models;

/**
 * @SWG\Definition(required={"success"}, type="object", @SWG\Xml(name="FaceIdVerify"))
 */

class FaceIdVerify
{

    /**
     * @SWG\Property(format="boolean")
     * @var boolean
     */
    public $success;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Message",
     *     description="",
     * )
     * @var int
     */
    public $message;
}