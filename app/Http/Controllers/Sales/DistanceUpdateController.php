<?php

namespace App\Http\Controllers\Sales;

use App\DailySaleCustomer;
use App\Http\Controllers\Controller;
use App\SalesOrder;

class DistanceUpdateController extends Controller
{
    /**
     * @param DailySaleCustomer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function allocationCustomerDistance(DailySaleCustomer $customer)
    {
        $customer->distance = \request()->input('distance');
        $customer->save();
        return response()->json(['success' => true]);
    }

    /**
     * @param SalesOrder $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function salesOrderDistance(SalesOrder $order)
    {
        $order->distance = \request()->input('distance');
        $order->save();
        return response()->json(['success' => true]);
    }
}
