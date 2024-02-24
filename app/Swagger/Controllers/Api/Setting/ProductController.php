<?php

namespace App\Swagger\Controllers\Api\Setting;

use App\Http\Requests\Api\Sales\ProductStoreRequest;
use App\Product;

/**
 * Class ProductController
 * @package App\Swagger\Controllers\Api\Common
 */
class ProductController
{

    /**
     * @SWG\Get(
     *     path="/setting/products/{productId}",
     *     summary="Find product by ID",
     *     description="Returns a single product",
     *     operationId="productShow",
     *     tags={"Product"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of product to return",
     *         in="path",
     *         name="productId",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(ref="#/definitions/Product")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid ID supplied"
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Product not found"
     *     )
     * )
     */
    public function show(Product $product)
    {

    }


    /**
     * @SWG\Get(
     *     path="/sales/products",
     *     summary="Get products for create  orders",
     *     tags={"Product"},
     *     description="List related products to create order.",
     *     operationId="ProductForOrder",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/ProductWithPrice")
     *         ),
     *     ),
     *     deprecated=false
     * )
     */
    public function products()
    {

    }
}
