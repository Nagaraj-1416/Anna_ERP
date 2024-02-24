<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="Mata"))
 */

class Mata
{
    /**
     * @SWG\Property(
     *     format="string",
     *     title="Last order reference",
     *     description="Last order reference",
     *     example="JF/AD/OR/000001"
     * )
     * @var string
     */
    public $last_order_ref;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Last invoice reference",
     *     description="Last invoice reference",
     *     example="JF/AD/INV/000001"
     * )
     * @var string
     */
    public $last_invoice_ref;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Next order reference",
     *     description="Next order reference",
     *     example="JF/AD/OR/000002"
     * )
     * @var string
     */
    public $next_order_ref;

    /**
     * @SWG\Property(
     *     format="string",
     *     title="Next invoice reference",
     *     description="Next invoice reference",
     *     example="JF/AD/INV/000001"
     * )
     * @var string
     */
    public $next_invoice_ref;

}