<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    public function show(Product $product)
    {
        return new ProductResource($product);
    }
}
