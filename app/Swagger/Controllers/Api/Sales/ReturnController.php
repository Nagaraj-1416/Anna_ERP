<?php

namespace App\Swagger\Controllers\Api\Sales;

/**
 * Class ReturnController
 * @package App\Swagger\Controllers\Api\Common
 */
class ReturnController
{
    /**
     * @SWG\Get(
     *     path="/sales/return/{customerId}",
     *     summary="Get product rates for sales returns",
     *     tags={"Sales Return"},
     *     description="List products rates for sales return . This API call is available to authenticated users.",
     *     operationId="salesReturnIndex",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of customer to return the products rate",
     *         in="path",
     *         name="customerId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/ReturnProductRate")
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
     *   path="/sales/return/{customerId}",
     *   tags={"Sales Return"},
     *   summary="Submit sales return",
     *   description="Submit sales return",
     *   operationId="submitSalesReturn",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *         description="ID of customer to return the products rate",
     *         in="path",
     *         name="customerId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *  @SWG\Parameter(
     *    name="items",
     *    in="body",
     *    description="Sales return product items",
     *    required=true,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="qty", type="integer", example=12, description="No items return"),
     *           @SWG\Property(property="type", type="string", enum={"Stock", "Sales"}, example="Stock", description="return type"),
     *           @SWG\Property(property="sold_rate", type="integer", example=90,  description="product sold rate"),
     *           @SWG\Property(property="returned_rate", type="integer", example=90,  description="product return rate"),
     *           @SWG\Property(property="returned_amount", type="integer", example=1080,  description="product return amount"),
     *           @SWG\Property(property="reason", type="string", example="expired",  description="product return rate"),
     *           @SWG\Property(property="product_id", type="integer", example="2",  description="return product id"),
     *          @SWG\Property(property="order_id", type="integer", example="2",  description="related order id"),
     *         )
     *     )
     *   ),
     *  @SWG\Parameter(
     *    name="resolutions",
     *    in="body",
     *    description="Sales return resolutions",
     *    required=true,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="type", type="string", enum={"Refund", "Credit", "Replace"}, example="Refund", description="resolutions type"),
     *           @SWG\Property(property="amount", type="integer", example=1080,  description="product return amount"),
     *         )
     *     )
     *   ),
     *  @SWG\Parameter(
     *    name="return_products",
     *    in="body",
     *    description="Return replace products, If resolution type id Replace, It is required",
     *    required=true,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="qty", type="integer", example=12, description="No of items"),
     *           @SWG\Property(property="rate", type="integer", example=90,  description="replace product rate"),
     *           @SWG\Property(property="amount", type="integer", example=1080,  description="product return amount"),
     *           @SWG\Property(property="product_id", type="integer", example="2",  description="product id"),
     *         )
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="notes",
     *     in="body",
     *     description="notes of  return",
     *     required=false,
     *      @SWG\Schema(
     *       type="string",
     *       example="sample note here"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="date",
     *     in="body",
     *     description="date of return",
     *     required=true,
     *      @SWG\Schema(
     *       type="string",
     *       example="2018-06-10"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/SalesReturn")
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

    /**
     * @SWG\Patch(
     *   path="/sales/return/is-printed/{returnId}",
     *   tags={"Sales Return"},
     *   summary="Update sales return print status by Id",
     *   description="Update a sales return print status",
     *   operationId="updateSalesReturnPrintStatus",
     *   produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of sales return to update",
     *         in="path",
     *         name="orderId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="is_printed",
     *     in="body",
     *     description="Print status",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       enum={"Yes", "No"},
     *       example="Yes"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/SalesReturn")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="sales return not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function isPrinted()
    {

    }
}