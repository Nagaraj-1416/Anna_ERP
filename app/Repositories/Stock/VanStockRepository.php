<?php

namespace App\Repositories\Stock;

use App\DailySale;
use App\Product;
use App\Repositories\BaseRepository;
use App\SalesLocation;
use App\Vehicle;

/**
 * Class VanStockRepository
 * @package App\Repositories\Stock
 */
class VanStockRepository extends BaseRepository
{
    /**
     * @return array
     */
    public function index()
    {
        $data = [];
        $request = request();
        $van = $request->input('vanId');
        $toDate = $request->input('date') ?? carbon()->toDateString();

        $location = SalesLocation::where('id', $van)->first();

        $items = null;

        $dailySale = DailySale::whereIn('status', ['Active','Progress','Completed'])
            ->where('sales_location_id', $van)
            ->where('to_date', '<=', $toDate)
            ->orderBy('id', 'desc')->first();

        if($dailySale){
            $items = $dailySale->items()->with('product')->get();
        }

        $data['location'] = $location;
        $data['items'] = $items;
        return $data;
    }

    /**
     * @param string $method
     * @return array
     */
    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Van Stocks'],
            ],
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}