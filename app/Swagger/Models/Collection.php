<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="Collection"))
 */

class Collection
{
    /**
     * @SWG\Property(
     *     format="float",
     *     title="Cash collections",
     *     description="Cash collections",
     * )
     * @var float
     */
    public $cash;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Cheque collections",
     *     description="Cheque collections",
     * )
     * @var float
     */
    public $cheque;


    /**
     * @SWG\Property(
     *     format="float",
     *     title="Direct deposit collections",
     *     description="Direct deposit collections",
     * )
     * @var float
     */
    public $direct_deposit;

    /**
     * @SWG\Property(
     *     format="float",
     *     title="Credit card collections",
     *     description="Credit card collections",
     * )
     * @var float
     */
    public $credit_card;
}