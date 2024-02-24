<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="ReturnProductRate"))
 */

class ReturnProductRate
{
    /**
     * @SWG\Property(format="int")
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $name;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $code;

    /**
     * @SWG\Property(
     *     format="array",
     *     title="sales order",
     *     description="sales order",
     *     type="array",
     *     @SWG\Items(
     *         ref="#/definitions/ReturnSalesOrderRate"
     *      )
     * )
     * @var array
     */
    public $orders;

}