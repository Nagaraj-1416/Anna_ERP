<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Stock;

class StockSummaryController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Stock'],
        ];
        $stocks = Stock::whereIn('company_id', userCompanyIds(loggedUser()))->count();
        return view('stock.index', compact('breadcrumb', 'stocks'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function lowStockItems()
    {
        $stocks = Stock::whereIn('company_id', userCompanyIds(loggedUser()))
            ->with('product')->get();
        $stocks = $stocks->transform(function ($stock) {
            $min = $stock->min_stock_level ?? 0;
            if ($stock->available_stock == 0) return null;
            if ($stock->available_stock >= $min) {
                return null;
            }
            return $stock;
        })->filter();
        return response()->json($stocks->toArray());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function outOfStock()
    {
        $stocks = Stock::whereIn('company_id', userCompanyIds(loggedUser()))
            ->with('product')->where('available_stock', 0)->get();
        return response()->json($stocks->toArray());
    }
}
