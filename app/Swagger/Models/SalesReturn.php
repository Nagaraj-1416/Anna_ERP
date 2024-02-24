<?php

namespace App\Swagger\Models;

/**
 *
 * @SWG\Definition(type="object", @SWG\Xml(name="SalesReturn"))
 */

class SalesReturn
{
    /**
     * @SWG\Property(format="integer")
     * @var integer
     */
    public $id;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $date;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $notes;

    /**
     * @SWG\Property(format="string")
     * @var string
     */
    public $status;

    /**
     * @SWG\Property(format="integer")
     * @var integer
     */
    public $customer_id;

    /**
     * @SWG\Property(format="integer")
     * @var integer
     */
    public $prepared_by;

    /**
     * @SWG\Property(format="integer")
     * @var integer
     */
    public $company_id;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Sales return created at",
     *     description="Sales return created at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $created_at;

    /**
     * @SWG\Property(
     *     format="object",
     *     title="Sales return last updated at",
     *     description="Sales return last updated at",
     *     ref="#/definitions/Date"
     * )
     * @var object
     */
    public $updated_at;
}