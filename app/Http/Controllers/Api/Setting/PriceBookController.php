<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\PriceBookResource;
use App\PriceBook;
use http\Env\Response;
use Illuminate\Http\Request;

class PriceBookController extends ApiController
{
    public function show(PriceBook $priceBook = null)
    {
        if (!$priceBook) $priceBook = getPriceBooksByAllocation();
        if ($priceBook){
            $priceBook->load('prices');
        }else{
            return response()->json(['data' => null]);
        }
        return new PriceBookResource($priceBook);
    }
}
