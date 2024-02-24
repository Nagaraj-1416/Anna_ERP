<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(required={"name"}, type="object", @SWG\Xml(name="Search"))
 */

class Search
{
    /**
     * @SWG\Property(format="boolean")
     * @var boolean
     */
    public $success;

    /**
     * @SWG\Property(
     *     type="array",
     *     title="Search results",
     *     description="Search results",
     *     type="array",
     *     @SWG\Items(
     *         ref="#/definitions/SearchResults"
     *      )
     * )
     * @var array
     */
    public $results;
}