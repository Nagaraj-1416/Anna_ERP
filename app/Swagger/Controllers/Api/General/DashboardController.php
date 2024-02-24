<?php

namespace App\Swagger\Controllers\Api\Sales;


/**
 * Class DashboardController
 * @package App\Swagger\Controllers\Api\Common
 */
class DashboardController
{
    /**
     * @SWG\Get(
     *     path="/dashboard?date={date}",
     *     summary="Get dashboard data",
     *     tags={"Dashboard"},
     *     description="Get data to dashboard . This API call is available to authenticated users.",
     *     operationId="index",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="date of data to return",
     *         in="query",
     *         name="date",
     *         required=false,
     *          type="string",
     *          @SWG\Schema(
     *              example="2018-01-30"
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Dashboard")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function index()
    {

    }
}