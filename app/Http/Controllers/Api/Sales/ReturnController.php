<?php

namespace App\Http\Controllers\Api\Sales;

use App\Customer;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Sales\ReturnStoreRequest;
use App\Http\Resources\SalesReturnResource;
use App\Repositories\Sales\ReturnRepository;
use App\SalesReturn;
use Illuminate\Http\Request;

class ReturnController extends ApiController
{
    /**
     * @var ReturnRepository
     */
    protected $salesReturn;

    /**
     * ReturnController constructor.
     * @param ReturnRepository $salesReturn
     */
    public function __construct(ReturnRepository $salesReturn)
    {
        $this->salesReturn = $salesReturn;
    }

    /**
     * @param Customer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Customer $customer = null)
    {
        return response()->json([
            'data' => $this->salesReturn->productsAverageRates($customer)
        ]);
    }

    public function store(ReturnStoreRequest $request, Customer $customer)
    {
        return new SalesReturnResource($this->salesReturn->store($request, $customer));
    }

    public function isPrinted(SalesReturn $return, Request $request)
    {
        $request->validate(['is_printed' => 'required|in:"Yes","No"']);
        return new SalesReturnResource($this->salesReturn->isPrinted($return, $request));
    }

}
