<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="Outstanding"))
 */

class Outstanding
{
    /**
     * @SWG\Property(
     *     format="float",
     *     title="Ordered amount",
     *     description="Ordered amount",
     * )
     * @var float
     */
    public $ordered;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Invoiced amount",
     *     description="Invoiced amount",
     * )
     * @var float
     */
    public $invoiced;


    /**
     * @SWG\Property(
     *     format="float",
     *     title="Paid amount",
     *     description="Paid amount",
     * )
     * @var float
     */
    public $paid;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Balance amount",
     *     description="Balance amount",
     * )
     * @var float
     */
    public $balance;
}