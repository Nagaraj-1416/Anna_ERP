<?php

namespace App\Http\Controllers\API\Sales;

use App\Customer;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Sales\CustomerStoreRequest;
use App\Http\Requests\Api\Sales\CustomerUpdateRequest;
use App\Http\Requests\Api\Sales\LocationStoreRequest;
use App\Http\Resources\{
    CustomerResource, InvoicePaymentResource, InvoiceResource, SalesOrderResource
};
use App\Repositories\Sales\CustomerRepository;
use Illuminate\Http\Request;

/**
 * Class CustomerController
 * @package App\Http\Controllers\API\Sales
 */
class CustomerController extends ApiController
{
    protected  $customer;

    public function __construct(CustomerRepository $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Get auth customers
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $customers = $this->customer->index();
        return CustomerResource::collection($customers);
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function todayIndex(){
        $customers = $this->customer->todayIndex();
        return CustomerResource::collection($customers);
    }

    /**
     * Customer show
     * @param Customer $customer
     * @return CustomerResource
     */
    public function show(Customer $customer)
    {
        $customer->load(['route', 'location', 'company', 'contactPersons', 'addresses.country']);
        $customer->outstanding = cusOutstanding($customer);
        return new CustomerResource($customer);
    }

    public function orders(Customer $customer)
    {
        return SalesOrderResource::collection($customer->orders);
    }

    public function invoices(Customer $customer){
        return InvoiceResource::collection($customer->invoices);
    }

    public function payments(Customer $customer){
        return InvoicePaymentResource::collection($customer->payments);
    }

    /**
     * Store new customer
     * @param CustomerStoreRequest $request
     * @return CustomerResource
     */
    public function store(CustomerStoreRequest $request)
    {
        $customer = $this->customer->save($request);
        return new CustomerResource($customer);
    }

    /**
     * update the existing customer
     * @param Customer $customer
     * @param CustomerUpdateRequest $request
     * @return CustomerResource
     */
    public function update(Customer $customer, CustomerUpdateRequest $request)
    {
        $customer = $this->customer->update($request, $customer);
        return new CustomerResource($customer);
    }

    /**
     * delete a customer
     * @param Customer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Customer $customer)
    {
        $response = $this->customer->delete($customer);
        return response()->json($response);
    }

    /**
     * @param Request $request
     * @param Customer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function notVisit(Request $request, Customer $customer){
        $request->validate([
            'reason' => 'required',
            'gps_lat' => 'required',
            'gps_long' => 'required',
            'is_visited' => 'in:"Yes","No"'
        ]);
        return response()->json($this->customer->notVisit($request, $customer));
    }

    public function updateLocation(LocationStoreRequest $request, Customer $customer)
    {
        $customer = $this->customer->updateLocation($request, $customer);
        return new CustomerResource($customer);
    }
}
