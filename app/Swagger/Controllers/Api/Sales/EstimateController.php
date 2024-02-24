<?php

namespace App\Swagger\Controllers\Api\Sales;
use App\Estimate;
use App\Http\Requests\Api\Sales\EstimateStoreRequest;


/**
 * Class EstimateController
 * @package App\Swagger\Controllers\Api\Common
 */
class EstimateController
{
    /**
     * @SWG\Get(
     *     path="/sales/estimates",
     *     summary="Get estimations",
     *     tags={"Estimate"},
     *     description="List related estimates . This API call is available to authenticated users.",
     *     operationId="estimatesIndex",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Estimate")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function index()
    {

    }

    /**
     * @SWG\Get(
     *     path="/sales/estimates/for-today",
     *     summary="Get today estimations",
     *     tags={"Estimate"},
     *     description="List today related estimates . This API call is available to authenticated users.",
     *     operationId="estimatesTodayIndex",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/EstimateShow")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function todayIndex()
    {

    }

    /**
     * @SWG\Get(
     *     path="/sales/estimates/{estimateId}",
     *     summary="Find estimate by ID",
     *     description="Returns a single estimate",
     *     operationId="estimatesShow",
     *     tags={"Estimate"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of estimate to return",
     *         in="path",
     *         name="estimateId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/EstimateShow")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Estimate not found"
     *     )
     * )
     */
    public function show(Estimate $estimate)
    {

    }

    /**
     * @SWG\Post(
     *   path="/sales/estimates",
     *   tags={"Estimate"},
     *   summary="Create a estimate",
     *   description="Create a estimate",
     *   operationId="createEstimate",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="business_type_id",
     *     in="body",
     *     description="Business type id for estimate",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *       example="1"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="rep_id",
     *     in="body",
     *     description="Rep id for estimate",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *       example="1"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="customer_id",
     *     in="body",
     *     description="Customer id of estimate",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *     example="2"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="estimate_date",
     *     in="body",
     *     description="estimate date of estimate",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *      example="2018-01-25"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="expiry_date",
     *     in="body",
     *     description="Expiry date of estimate",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="2018-01-30"
     *     )
     *   ),
     *  @SWG\Parameter(
     *    name="order_items",
     *    in="body",
     *    description="Order items for estimate",
     *    required=true,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="product_id", type="integer", example=1),
     *           @SWG\Property(property="store_id", type="integer", example=1),
     *           @SWG\Property(property="quantity", type="integer", example=10),
     *           @SWG\Property(property="rate", type="integer", example=20.50),
     *           @SWG\Property(property="discount_rate", type="integer", example=4),
     *           @SWG\Property(property="discount_type", type="string", enum={"Amount", "Percentage"}),
     *           @SWG\Property(property="notes", type="string", example=""),
     *         )
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="adjustment",
     *     in="body",
     *     description="adjustment in total amount",
     *     required=false,
     *      @SWG\Schema(
     *       type="float",
     *       example="-100.25"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="discount_rate",
     *     in="body",
     *     description="Discount rate in total amount",
     *     required=false,
     *      @SWG\Schema(
     *       type="float",
     *       example="10.00"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="discount_type",
     *     in="body",
     *     description="Discount type in total amount",
     *     required=false,
     *      @SWG\Schema(
     *       type="string",
     *       enum={"Percentage", "Amount"},
     *       example="Amount"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="notes",
     *     in="body",
     *     description="notes of estimate",
     *     required=false,
     *      @SWG\Schema(
     *       type="string",
     *       example="sample note here"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="terms",
     *     in="body",
     *     description="terms of estimate",
     *     required=false,
     *      @SWG\Schema(
     *       type="string",
     *       example="sample terms here"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="file",
     *     in="body",
     *     description="file of estimate",
     *     required=false,
     *      @SWG\Schema(
     *       type="file",
     *       example="File content"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Estimate")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="estimate not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function store(EstimateStoreRequest $request)
    {

    }


    /**
     * @SWG\Patch(
     *   path="/sales/estimates/{estimateId}",
     *   tags={"Estimate"},
     *   summary="Update estimate by Id",
     *   description="Update a estimate",
     *   operationId="Update Estimate",
     *   produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of estimate to update",
     *         in="path",
     *         name="estimateId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="business_type_id",
     *     in="body",
     *     description="Business type id for estimate",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *       example="1"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="rep_id",
     *     in="body",
     *     description="Rep id for estimate",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *       example="1"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="customer_id",
     *     in="body",
     *     description="Customer id of estimate",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *     example="2"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="estimate_date",
     *     in="body",
     *     description="estimate date of estimate",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *      example="2018-01-25"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="expiry_date",
     *     in="body",
     *     description="Expiry date of estimate",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="2018-01-30"
     *     )
     *   ),
     *  @SWG\Parameter(
     *    name="order_items",
     *    in="body",
     *    description="Order items for estimate",
     *    required=true,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="product_id", type="integer", example=1),
     *           @SWG\Property(property="store_id", type="integer", example=1),
     *           @SWG\Property(property="quantity", type="integer", example=10),
     *           @SWG\Property(property="rate", type="integer", example=20.50),
     *           @SWG\Property(property="discount_rate", type="integer", example=4),
     *           @SWG\Property(property="discount_type", type="string", enum={"Amount", "Percentage"}),
     *           @SWG\Property(property="notes", type="string", example=""),
     *         )
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="adjustment",
     *     in="body",
     *     description="adjustment in total amount",
     *     required=false,
     *      @SWG\Schema(
     *       type="float",
     *       example="-100.25"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="discount_rate",
     *     in="body",
     *     description="Discount rate in total amount",
     *     required=false,
     *      @SWG\Schema(
     *       type="float",
     *       example="10.00"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="discount_type",
     *     in="body",
     *     description="Discount type in total amount",
     *     required=false,
     *      @SWG\Schema(
     *       type="string",
     *       enum={"Percentage", "Amount"},
     *       example="Amount"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="notes",
     *     in="body",
     *     description="notes of estimate",
     *     required=false,
     *      @SWG\Schema(
     *       type="string",
     *       example="sample note here"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="terms",
     *     in="body",
     *     description="terms of estimate",
     *     required=false,
     *      @SWG\Schema(
     *       type="string",
     *       example="sample terms here"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="file",
     *     in="body",
     *     description="file of estimate",
     *     required=false,
     *      @SWG\Schema(
     *       type="file",
     *       example="File content"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Estimate")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="estimate not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function update(EstimateStoreRequest $request, Estimate $estimate)
    {

    }

    /**
     * @SWG\Delete(
     *    path="/sales/estimates/{estimateId}",
     *     summary="Delete estimate by ID",
     *     tags={"Estimate"},
     *     description="Delete a estimate. This API call is available to authenticated users.",
     *     operationId="deleteEstimate",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="deleting  estimate id",
     *         in="path",
     *         name="estimateId",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Delete")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *  @SWG\Response(
     *         response="404",
     *         description="estimate not found"
     *   ),
     *     deprecated=false
     * )
     */
    public function delete(Estimate $estimate)
    {

    }
}