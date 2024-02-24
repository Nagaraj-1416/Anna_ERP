<?php

namespace App\Swagger\Controllers\Api\Sales;

use App\Http\Requests\Api\Sales\CustomerStoreRequest;
use App\Swagger\Models\Customer;

/**
 * Class CustomerController
 * @package App\Swagger\Controllers\Api\Common
 */
class CustomerController
{
    /**
     * @SWG\Get(
     *     path="/customers",
     *     summary="Get customers",
     *     tags={"Customer"},
     *     description="List all customers . This API call is available to authenticated users.",
     *     operationId="customerIndex",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Customer")
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
     *     path="/customers/for-today",
     *     summary="Get today customers",
     *     tags={"Customer"},
     *     description="List all today rep related customers . This API call is available to authenticated users.",
     *     operationId="customerTodayIndex",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/CustomerShow")
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
     *     path="/customers/{customerId}",
     *     summary="Find customer by ID",
     *     description="Returns a single customers",
     *     operationId="customerShow",
     *     tags={"Customer"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of customer to return",
     *         in="path",
     *         name="customerId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/CustomerShow")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Customer not found"
     *     )
     * )
     */
    public function show(Customer $customer)
    {

    }

    /**
     * @SWG\Get(
     *     path="/customers/{customerId}/orders",
     *     summary="Get orders by customer ID",
     *     description="Returns orders",
     *     operationId="customerOrders",
     *     tags={"Customer"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of customer to return",
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
     *             @SWG\Items(ref="#/definitions/SalesOrder")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Customer not found"
     *     )
     * )
     */
    public function orders()
    {

    }

    /**
     * @SWG\Get(
     *     path="/customers/{customerId}/invoices",
     *     summary="Get invoices by customer ID",
     *     description="Returns invoices",
     *     operationId="customerInvoices",
     *     tags={"Customer"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of customer to return",
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
     *             @SWG\Items(ref="#/definitions/Invoice")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Customer not found"
     *     )
     * )
     */
    public function invoices()
    {

    }

    /**
     * @SWG\Get(
     *     path="/customers/{customerId}/payments",
     *     summary="Get payments by customer ID",
     *     description="Returns payments",
     *     operationId="customerPayments",
     *     tags={"Customer"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of customer to return",
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
     *             @SWG\Items(ref="#/definitions/InvoicePayment")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Customer not found"
     *     )
     * )
     */
    public function payments()
    {

    }

    /**
     * @SWG\Post(
     *   path="/customers",
     *   tags={"Customer"},
     *   summary="Create a customer",
     *   description="",
     *   operationId="createCustomer",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="salutation",
     *     in="body",
     *     description="Salutation of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="Mr."
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="first_name",
     *     in="body",
     *     description="first name of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="Raja"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="last_name",
     *     in="body",
     *     description="last name of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="Vemal"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="display_name",
     *     in="body",
     *     description="display name of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="Raj"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="phone",
     *     in="body",
     *     description="phone number of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="+94777123456"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="fax",
     *     in="body",
     *     description="fax number of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="+94110123456"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="mobile",
     *     in="body",
     *     description="mobile number of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="+94110123457"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="email",
     *     in="body",
     *     description="email of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="mail@gmail.com"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="website",
     *     in="body",
     *     description="website of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="http://exampleweb.com"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="street_one",
     *     in="body",
     *     description="street one address of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="Lane 1"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="street_two",
     *     in="body",
     *     description="street two address of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="Lane 2"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="city",
     *     in="body",
     *     description="city of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="Jaffna"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="province",
     *     in="body",
     *     description="province of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="Northern Province"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="postal_code",
     *     in="body",
     *     description="postal code of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *       example="41100"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="country_id",
     *     in="body",
     *     description="country id of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *       example="39"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="route_id",
     *     in="body",
     *     description="route id of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *       example="2"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="location_id",
     *     in="body",
     *     description="route location id of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="integer",
     *       example="1"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="notes",
     *     in="body",
     *     description="notes for the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="Best customer"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="is_active",
     *     in="body",
     *     description="Is active customer?",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *      enum={"Yes", "No"},
     *      example="Yes"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="logo_file",
     *     in="body",
     *     description="image of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="file",
     *      example="File content"
     *     )
     *   ),
     *  @SWG\Parameter(
     *    name="contact_persons",
     *    in="body",
     *    description="contact persons",
     *    required=false,
     *    @SWG\Schema(
     *       type="array",
     *       @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="first_name", type="string", example="Raja"),
     *           @SWG\Property(property="last_name", type="string", example="vemal"),
     *           @SWG\Property(property="email", type="string", example="mail@anna.com"),
     *           @SWG\Property(property="phone", type="string", example=0211234567),
     *           @SWG\Property(property="mobile", type="string", example=0771234567),
     *         )
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Customer")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Customer not found"
     *   ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function store(CustomerStoreRequest $request)
    {

    }

    /**
     * @SWG\Patch(
     *   path="/customers/{customerId}",
     *   tags={"Customer"},
     *   summary="update customer by ID",
     *   description="update a customer data",
     *   operationId="updateCustomer",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="salutation",
     *     in="body",
     *     description="Salutation of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="Mr."
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="first_name",
     *     in="body",
     *     description="first name of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="Raja"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="last_name",
     *     in="body",
     *     description="last name of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="Vemal"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="display_name",
     *     in="body",
     *     description="display name of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="Raj"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="phone",
     *     in="body",
     *     description="phone number of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="+94777123456"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="fax",
     *     in="body",
     *     description="fax number of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="+94110123456"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="mobile",
     *     in="body",
     *     description="mobile number of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="+94110123457"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="email",
     *     in="body",
     *     description="email of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="mail@gmail.com"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="website",
     *     in="body",
     *     description="website of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="http://exampleweb.com"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="street_one",
     *     in="body",
     *     description="street one address of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="Lane 1"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="street_two",
     *     in="body",
     *     description="street two address of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="Lane 2"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="city",
     *     in="body",
     *     description="city of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="Jaffna"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="province",
     *     in="body",
     *     description="province of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="Northern Province"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="postal_code",
     *     in="body",
     *     description="postal code of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="integer",
     *       example="41100"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="country_id",
     *     in="body",
     *     description="country id of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="integer",
     *       example="39"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="route_id",
     *     in="body",
     *     description="route id of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="integer",
     *       example="2"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="location_id",
     *     in="body",
     *     description="route location id of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="integer",
     *       example="1"
     *     )
     *   ),
     *  @SWG\Parameter(
     *     name="notes",
     *     in="body",
     *     description="notes for the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *       example="Best customer"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="is_active",
     *     in="body",
     *     description="Is active customer?",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *      enum={"Yes", "No"},
     *      example="Yes"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="logo_file",
     *     in="body",
     *     description="image of the customer",
     *     required=false,
     *     @SWG\Schema(
     *       type="file",
     *      example="File content"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Customer")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Customer not found"
     *   ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function update(Customer $customer, CustomerStoreRequest $request)
    {

    }/**
     * @SWG\Patch(
     *   path="/customers/{customerId}/location",
     *   tags={"Customer"},
     *   summary="update customer location by ID",
     *   description="update a customer location",
     *   operationId="updateCustomerLocation",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="gps_lat",
     *     in="body",
     *     description="GPS latitude of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="40.741895"
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="gps_long",
     *     in="body",
     *     description="GPS longitude of the customer",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example="-73.989308"
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Customer")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Customer not found"
     *   ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function updateLocation()
    {

    }

    /**
     * @SWG\Delete(
     *    path="/customers/{customerId}",
     *     summary="Delete  customer by ID",
     *     tags={"Customer"},
     *     description="Delete a customer. This API call is available to authenticated users.",
     *     operationId="deleteCustomer",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="deleting customer id",
     *         in="path",
     *         name="customerId",
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
     *         description="Customer not found"
     *   ),
     *     deprecated=false
     * )
     */
    public function delete()
    {

    }

    /**
     * @SWG\Post(
     *   path="/customers/{customerId}/not-visited",
     *   tags={"Customer"},
     *   summary="Update as a not visited customer",
     *   description="Update as a not visited customer",
     *   operationId="notVisitedCustomer",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="reason",
     *     in="body",
     *     description="Reason for not visiting",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example=" "
     *     )
     *   ),
     *     *   @SWG\Parameter(
     *     name="gps_lat",
     *     in="body",
     *     description="User GPS latitude",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example=""
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="gps_long",
     *     in="body",
     *     description="User GPS longitude",
     *     required=true,
     *     @SWG\Schema(
     *       type="string",
     *       example=""
     *     )
     *   ),
     *   @SWG\Parameter(
     *     name="is_visited",
     *     in="body",
     *     description="Is visited customer? (for update purpose)",
     *     required=false,
     *     @SWG\Schema(
     *       type="string",
     *      enum={"Yes", "No"},
     *      example="Yes"
     *     )
     *  ),
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(ref="#/definitions/Response")
     *   ),
     *  @SWG\Response(
     *         response="404",
     *         description="Customer not found"
     *   ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *  @SWG\Response(
     *         response="422",
     *         description="Invalid data supplied"
     *   )
     *)
     */
    public function notVisit()
    {

    }
}