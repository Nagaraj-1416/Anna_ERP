<?php

namespace App\Swagger\Controllers\Api\Setting;

use App\Product;
use App\Route;

/**
 * Class ProductController
 * @package App\Swagger\Controllers\Api\Common
 */
class RouteController
{

    /**
     * @SWG\Get(
     *     path="/setting/route/{routeId}",
     *     summary="Find route by ID",
     *     description="Returns a single route",
     *     operationId="routeShow",
     *     tags={"Route"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of route to return",
     *         in="path",
     *         name="routeId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/RouteShow")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Route not found"
     *     )
     * )
     * @param Route $route
     */
    public function show(Route $route)
    {

    }

    /**
     * @SWG\Get(
     *     path="/setting/route/next-day-route",
     *     summary="Find next day route",
     *     description="Returns a single route",
     *     operationId="NextDayRoute",
     *     tags={"Route"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/Route")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Route not found"
     *     )
     * )
     * @param Route $route
     */
    public function nextDay(Route $route)
    {

    }

    /**
     * @SWG\Post(
     *   path="/setting/route/pick-next",
     *   tags={"Route"},
     *   summary="Pick next day route",
     *   description="Update the next day route",
     *   operationId="NextDayRoute",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="route_id",
     *     in="body",
     *     description="Next day route id",
     *     required=true,
     *      @SWG\Schema(
     *       type="int",
     *       example="1"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/NextRoute")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="resource not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function pickNext()
    {

    }
}
