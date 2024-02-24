<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="Collections"))
 */

class Collections
{
    /**
     * @SWG\Property(
     *     format="object",
     *     title="Today collections details",
     *     description="Today collections details",
     *     ref="#/definitions/Collection"
     * )
     * @var object
     */
    public $today_collections;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Old collections details",
     *     description="Old collections details",
     *     ref="#/definitions/Collection"
     * )
     * @var object
     */
    public $old_collections;

}