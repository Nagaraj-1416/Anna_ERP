<?php

namespace App\Http\Controllers\Api\Sales;

use App\DailySaleItem;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Sales\HandOverStoreRequest;
use App\Repositories\Sales\HandOverRepository;
use App\SalesHandover;
use Illuminate\Http\Request;

class HandOverController extends ApiController
{
    protected $handOver;

    public function __construct(HandOverRepository $handOver)
    {
        $this->handOver = $handOver;
    }

    public function details()
    {
        $details = $this->handOver->details();
        return response()->json(['data' => $details]);
    }

    public function store(HandOverStoreRequest $request)
    {
        $todayHandover = $this->handOver->todayHandOver();
        if ($todayHandover){
            return response()->json(array(
                'message' => 'handover failed.',
                'errors' => ['today allocated sales, handover processed already completed..']
            ), 403);
        }
        $allocations = getRepAllocation();
        $allocation = $allocations->first();
        if (!$allocation){
            return response()->json(array(
                'message' => 'handover failed.',
                'errors' => ['you don\'t have sales allocation for today.']
            ), 403);
        }
        /** @var SalesHandover $handOver */
        $handOver = $this->handOver->store($request,  $allocation);
        $this->handOver->storeOdometer($request, $handOver, $allocation);
        $this->handOver->updateSoldQty($request, $handOver);
        $this->handOver->storeAllowanceAsExpense($request, $handOver);
        $this->handOver->updateHandOverIdToExpense($allocation, $handOver);
        $this->handOver->storeNotVisitedCustomers($request, $handOver);
        $this->handOver->checkCreditSales();
        repLoggedOutSuccess($allocations);
        return response()->json(['data' =>  $handOver->toArray()]);
    }

    public function confirmStock(Request $request)
    {
        $allocation = getRepAllocation()->first();
        $dailySalesItems = DailySaleItem::where('daily_sale_id', $allocation->id)->get();
        $stocks = $request->input('stocks');
        foreach ($stocks as $productId => $stock) {
            $item = $dailySalesItems->where('product_id', $productId)->first();
            if ($item) {
                /** if balance great than actual */
                if($stock['actual_stock'] != null && $stock['balance_stock'] < $stock['actual_stock']){
                    $item->excess_qty = ($stock['actual_stock'] - $stock['balance_stock']);
                }
                /** if balance less than actual */
                if($stock['actual_stock'] != null && $stock['balance_stock'] > $stock['actual_stock']){
                    $item->shortage_qty = ($stock['balance_stock'] - $stock['actual_stock']);
                }
                $item->actual_stock = $stock['actual_stock'];
                $item->save();
            }
        }
    }

}
