<?php

namespace App\Swagger\Controllers\Api\Sales;

use App\Http\Requests\Api\Sales\OrderStoreRequest;
use App\SalesOrder;
use App\Swagger\Models\Customer;

/**
 * Class HandoverController
 * @package App\Swagger\Controllers\Api\Common
 */
class HandoverController
{
    /**
     * @SWG\Get(
     *     path="/sales/handover",
     *     summary="Get today sales handover details",
     *     tags={"Handover"},
     *     description="Details of today sales collection . This API call is available to authenticated users.",
     *     operationId="HandoverIndex",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Handover")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function index()
    {

    }


    /**
     * @SWG\Post(
     *   path="/sales/handover/",
     *   tags={"Handover"},
     *   summary="Submit daily sales",
     *   description="Submit daily sales (Handover the sales)",
     *   operationId="submitHandover",
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *    name="expenses",
     *    in="body",
     *    description="Today expenses records",
     *    required=true,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="type_id", type="integer"),
     *           @SWG\Property(
     *                  property="calculate_mileage_using",
     *                  type="string",
     *                  enum={"Odometer", "Distance"},
     *                  example="Odometer",
     *                  description="If type is Mileage, It is required"
     *           ),
     *           @SWG\Property(property="start_reading", type="integer", example=2000, description="If calculate_mileage_using is Odometer, It is required"),
     *           @SWG\Property(property="end_reading", type="integer", example=2500, description="If calculate_mileage_using is Odometer, It is required"),
     *           @SWG\Property(property="distance", type="integer", example=100,  description="If calculate_mileage_using is Distance, It is required"),
     *           @SWG\Property(property="gps_lat", type="integer", example="", description="If type is Fuel, It is required"),
     *           @SWG\Property(property="gps_long", type="integer", example="", description="If type is Fuel, It is required"),
     *           @SWG\Property(property="liter", type="integer", example=10, description="If type is Fuel, It is required"),
     *           @SWG\Property(property="odometer", type="integer", example=10, description="If type is Fuel, It is required"),
     *           @SWG\Property(property="amount", type="integer", example=100),
     *           @SWG\Property(property="notes", type="string", example=""),
     *         )
     *     )
     *   ),
     *  @SWG\Parameter(
     *    name="not_visit_customer_notes",
     *    in="body",
     *    description="Today Not visited customers notes (10 is a customer id)",
     *    required=true,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="10", type="string", example="sample notes here"),
     *         )
     *     )
     *   ),
     *  @SWG\Parameter(
     *    name="sold_qty",
     *    in="body",
     *    description="Today no of items sold in a product (5 is a product id)",
     *    required=true,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="5", type="string", example="20"),
     *         )
     *     )
     *   ),
     *   @SWG\Parameter(
     *    name="replaced_qty",
     *    in="body",
     *    description="Today no of items replaced in a product (5 is a product id)",
     *    required=false,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="5", type="string", example="10"),
     *         )
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="notes",
     *     in="body",
     *     description="notes of handover",
     *     required=true,
     *      @SWG\Schema(
     *       type="string",
     *       example="sample note here"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="allowance",
     *     in="body",
     *     description="allowance of handover",
     *     required=true,
     *      @SWG\Schema(
     *       type="float",
     *       example="1000"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/SalesOrderShow")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="sales order not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function store()
    {

    }
}