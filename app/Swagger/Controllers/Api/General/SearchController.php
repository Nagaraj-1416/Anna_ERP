<?php

namespace App\Swagger\Controllers\Api\Sales;

use App\Swagger\Models\Customer;

/**
 * Class SearchController
 * @package App\Swagger\Controllers\Api\Common
 */
class SearchController
{
    /**
     * @SWG\Get(
     *     path="/search/business-type/{query}",
     *     summary="Search the business type",
     *     tags={"Search"},
     *     description="Search the business type for dropdown . This API call is available to authenticated users.",
     *     operationId="searchBusinessType",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *          type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function businessType()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/supplier/{query}",
     *     summary="Search the supplier",
     *     tags={"Search"},
     *     description="Search the supplier for dropdown . This API call is available to authenticated users.",
     *     operationId="searchSupplier",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *          type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function supplier()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/product/{query}/{type}",
     *     summary="Search the product",
     *     tags={"Search"},
     *     description="Search the product for dropdown . This API call is available to authenticated users.",
     *     operationId="searchProduct",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         description="Product type to return",
     *         in="path",
     *         name="type",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function product()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/store/{query}",
     *     summary="Search the store",
     *     tags={"Search"},
     *     description="Search the store for dropdown . This API call is available to authenticated users.",
     *     operationId="searchStore",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *          type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function store()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/route/{query}",
     *     summary="Search the routes",
     *     tags={"Search"},
     *     description="Search the route for dropdown . This API call is available to authenticated users.",
     *     operationId="searchRoute",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *          type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function route()
    {

    }


    /**
     * @SWG\Get(
     *     path="/search/route-location/{routeId}/{query}",
     *     summary="Search the route locations",
     *     tags={"Search"},
     *     description="Search the route locations for dropdown . This API call is available to authenticated users.",
     *     operationId="searchRouteLocation",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Route id to return locations",
     *         in="path",
     *         name="routeId",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function routeLocation()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/country/{query}",
     *     summary="Search the country",
     *     tags={"Search"},
     *     description="Search the countries for dropdown . This API call is available to authenticated users.",
     *     operationId="searchCountry",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function country()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/customer/{query}",
     *     summary="Search the customer",
     *     tags={"Search"},
     *     description="Search the customer for dropdown . This API call is available to authenticated users.",
     *     operationId="searchCustomer",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function customer()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/salutation",
     *     summary="List the salutations for dropdown",
     *     tags={"Search"},
     *     description="List the salutations for dropdown . This API call is available to authenticated users.",
     *     operationId="searchSalutation",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function salutation()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/rep/{query}",
     *     summary="Search the rep",
     *     tags={"Search"},
     *     description="Search the rep for dropdown . This API call is available to authenticated users.",
     *     operationId="searchRep",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function rep()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/unit-type/{query}",
     *     summary="Search the unit type",
     *     tags={"Search"},
     *     description="Search the unit type for dropdown . This API call is available to authenticated users.",
     *     operationId="searchUnitType",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function unitType()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/price-book/{query}",
     *     summary="Search the price book",
     *     tags={"Search"},
     *     description="Search the price book for dropdown . This API call is available to authenticated users.",
     *     operationId="searchPriceBook",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function priceBook()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/bank/{query}",
     *     summary="Search the banks",
     *     tags={"Search"},
     *     description="Search the banks for dropdown . This API call is available to authenticated users.",
     *     operationId="searchBank",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function bank()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/deposited-to/{query}",
     *     summary="Search the deposit to account",
     *     tags={"Search"},
     *     description="Search the deposit to account for dropdown . This API call is available to authenticated users.",
     *     operationId="searchDepositTo",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function depositedTo()
    {

    }

    /**
     * @SWG\Get(
     *     path="/search/expense-type/{query}",
     *     summary="Search the expense types",
     *     tags={"Search"},
     *     description="Search the expense type for dropdown . This API call is available to authenticated users.",
     *     operationId="searchExpenseType",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="search key to return",
     *         in="path",
     *         name="query",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Search")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function expenseType()
    {

    }
}