<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="SearchResults"))
 */

class SearchResults
{
    /**
     * @SWG\Property(
     *     format="int64",
     *     title="Item Value",
     *     description="Item Value",
     * )
     * @var int
     */
    public $value;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Item name",
     *     description="Item name",
     * )
     * @var string
     */
    public $name;
}