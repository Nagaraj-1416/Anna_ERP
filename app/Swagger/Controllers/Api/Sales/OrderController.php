<?php

namespace App\Swagger\Controllers\Api\Sales;

use App\Http\Requests\Api\Sales\OrderStoreRequest;
use App\SalesOrder;
use App\Swagger\Models\Customer;

/**
 * Class OrderController
 * @package App\Swagger\Controllers\Api\Common
 */
class OrderController
{
    /**
     * @SWG\Get(
     *     path="/sales/orders",
     *     summary="Get sales orders",
     *     tags={"Sales Order"},
     *     description="List related sales orders . This API call is available to authenticated users.",
     *     operationId="salesOrderIndex",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/SalesOrder")
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
     *     path="/sales/orders/for-today",
     *     summary="Get today sales orders",
     *     tags={"Sales Order"},
     *     description="List related today sales orders . This API call is available to authenticated users.",
     *     operationId="salesOrderTodayIndex",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/SalesOrderShow")
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
     *     path="/sales/orders/{orderId}",
     *     summary="Find sales order by ID",
     *     description="Returns a single sales order",
     *     operationId="salesOrderShow",
     *     tags={"Sales Order"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of sales order to return",
     *         in="path",
     *         name="orderId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/SalesOrderShow")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Sales order not found"
     *     )
     * )
     */
    public function show(SalesOrder $order)
    {

    }

    /**
     * @SWG\Post(
     *   path="/sales/orders/",
     *   tags={"Sales Order"},
     *   summary="Create a sales order",
     *   description="Create a sales order",
     *   operationId="createSalesOrder",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="business_type_id",
     *     in="body",
     *     description="Business type id for sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *       example="1"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="order_type",
     *     in="body",
     *     description="Order type of sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       enum={"Direct", "Schedule"},
     *       example="Direct"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="ref",
     *     in="body",
     *     description="Reference of sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="JF/AD/OR/000001"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="customer_id",
     *     in="body",
     *     description="Customer id of sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *     example="2"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="order_date",
     *     in="body",
     *     description="Order date of sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *      example="2018-01-25"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="delivery_date",
     *     in="body",
     *     description="Delivery date of sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="2018-01-30"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="gps_lat",
     *     in="body",
     *     description="Order GPS latitude",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example=""
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="gps_long",
     *     in="body",
     *     description="Order GPS longitude",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example=""
     *     )
     *   ),
     *  @SWG\Parameter(
     *    name="order_items",
     *    in="body",
     *    description="Order items for sales order",
     *    required=true,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="product_id", type="integer", example=1),
     *           @SWG\Property(property="unit_type_id", type="integer", example=1),
     *           @SWG\Property(property="quantity", type="integer", example=10),
     *           @SWG\Property(property="notes", type="string", example=""),
     *         )
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="notes",
     *     in="body",
     *     description="notes of sales order",
     *     required=false,
     *      @SWG\Schema(
     *       type="string",
     *       example="sample note here"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="terms",
     *     in="body",
     *     description="terms of sales order",
     *     required=false,
     *      @SWG\Schema(
     *       type="string",
     *       example="sample terms here"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="file",
     *     in="body",
     *     description="file of sales order",
     *     required=false,
     *      @SWG\Schema(
     *       type="file",
     *       example="File content"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="is_order_printed",
     *     in="body",
     *     description="is order bill printed?",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       enum={"Yes", "No"},
     *       example="Yes"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="save_as",
     *     in="body",
     *     description="save type of sales order",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       enum={"SaveAsDraft", "Save"},
     *       example="Save"
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
    public function store(OrderStoreRequest $request)
    {

    }


    /**
     * @SWG\Patch(
     *   path="/sales/orders/{orderId}",
     *   tags={"Sales Order"},
     *   summary="Update sales order by Id",
     *   description="Update a sales order",
     *   operationId="updateSalesOrder",
     *   produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of sales order to update",
     *         in="path",
     *         name="orderId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="business_type_id",
     *     in="body",
     *     description="Business type id for sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *       example="1"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="order_type",
     *     in="body",
     *     description="Order type of sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       enum={"Direct", "Schedule"},
     *       example="Direct"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="customer_id",
     *     in="body",
     *     description="Customer id of sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *     example="2"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="order_date",
     *     in="body",
     *     description="Order date of sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *      example="2018-01-25"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="delivery_date",
     *     in="body",
     *     description="Delivery date of sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="2018-01-30"
     *     )
     *   ),
     *  @SWG\Parameter(
     *    name="order_items",
     *    in="body",
     *    description="Order items for sales order",
     *    required=true,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="product_id", type="integer", example=1),
     *           @SWG\Property(property="unit_type_id", type="integer", example=1),
     *           @SWG\Property(property="quantity", type="integer", example=10),
     *           @SWG\Property(property="notes", type="string", example=""),
     *         )
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="notes",
     *     in="body",
     *     description="notes of sales order",
     *     required=false,
     *      @SWG\Schema(
     *       type="string",
     *       example="sample note here"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="terms",
     *     in="body",
     *     description="terms of sales order",
     *     required=false,
     *      @SWG\Schema(
     *       type="string",
     *       example="sample terms here"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="file",
     *     in="body",
     *     description="file of sales order",
     *     required=false,
     *      @SWG\Schema(
     *       type="file",
     *       example="File content"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="save_as",
     *     in="body",
     *     description="save type of sales order",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       enum={"SaveAsDraft", "Save"},
     *       example="Save"
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
    public function update(SalesOrder $order, OrderStoreRequest $request)
    {

    }


    /**
     * @SWG\Patch(
     *   path="/sales/orders/{orderId}/update-status",
     *   tags={"Sales Order"},
     *   summary="Update sales order status by Id",
     *   description="Update a sales order status",
     *   operationId="updateSalesOrderStatus",
     *   produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of sales order to update",
     *         in="path",
     *         name="orderId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="status",
     *     in="body",
     *     description="Order status",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       enum={"Scheduled", "Draft", "Awaiting Approval", "Open", "Closed", "Canceled"},
     *       example="Open"
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
    public function updateStatus()
    {

    }

    /**
     * @SWG\Delete(
     *    path="/sales/orders/{orderId}",
     *     summary="Delete sales order by ID",
     *     tags={"Sales Order"},
     *     description="Delete a sales order. This API call is available to authenticated users.",
     *     operationId="deleteSalesOrder",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="deleting  sales order id",
     *         in="path",
     *         name="orderId",
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
     *         description="sales order not found"
     *   ),
     *     deprecated=false
     * )
     */
    public function delete()
    {

    }

    /**
     * @SWG\Post(
     *   path="/sales/orders/{orderId}/cancel",
     *   tags={"Sales Order"},
     *   summary="Cancel sales order by Id",
     *   description="Cancel a sales order",
     *   operationId="cancelSalesOrder",
     *   produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of sales order to cancel",
     *         in="path",
     *         name="orderId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="reason",
     *     in="body",
     *     description="reason of cancel sales order",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
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
    public function cancel()
    {

    }

    /**
     * @SWG\Patch(
     *   path="/sales/orders/{orderId}/is-printed",
     *   tags={"Sales Order"},
     *   summary="Update sales order print status by Id",
     *   description="Update a sales order print status",
     *   operationId="updateSalesOrderPrintStatus",
     *   produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of sales order to update",
     *         in="path",
     *         name="orderId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *   @SWG\Parameter(
     *     name="is_order_printed",
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
     *     @SWG\Schema(ref="#/definitions/SalesOrder")
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
    public function isPrinted()
    {

    }
}