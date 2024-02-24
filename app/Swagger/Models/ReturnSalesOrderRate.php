<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="ReturnSalesOrderRate"))
 */

class ReturnSalesOrderRate
{
    /**
     * @SWG\Property(format="int")
     * @var int
     */
    public $id;

    /**
     * @SWG\Property(format="int")
     * @var int
     */
    public $customer_id;

    /**
     * @SWG\Property(format="float")
     * @var float
     */
    public $rate;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $order_no;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $ref;
}