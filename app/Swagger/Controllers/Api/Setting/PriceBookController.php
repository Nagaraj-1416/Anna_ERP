<?php

namespace App\Swagger\Controllers\Api\Setting;

use App\PriceBook;
use App\Product;

/**
 * Class ProductController
 * @package App\Swagger\Controllers\Api\Common
 */
class PriceBookController
{

    /**
     * @SWG\Get(
     *     path="/setting/price-books/{priceBookId}",
     *     summary="Find price book by ID",
     *     description="Returns a single price book",
     *     operationId="priceBookShow",
     *     tags={"Price Book"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of product to return",
     *         in="path",
     *         name="priceBookId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/PriceBookShow")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="price book not found"
     *     )
     * )
     */
    public function show(PriceBook $priceBook)
    {

    }
}
