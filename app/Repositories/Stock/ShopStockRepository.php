<?php

namespace App\Repositories\Stock;

use App\DailySale;
use App\Product;
use App\Repositories\BaseRepository;
use App\SalesLocation;
use App\Vehicle;

/**
 * Class ShopStockRepository
 * @package App\Repositories\Stock
 */
class ShopStockRepository extends BaseRepository
{
    /**
     * @return array
     */
    public function index()
    {
        $data = [];
        $request = request();
        $shop = $request->input('shopId');
        $toDate = $request->input('date') ?? carbon()->toDateString();

        $location = SalesLocation::where('id', $shop)->first();

        $items = null;

        $dailySale = DailySale::whereIn('status', ['Active','Progress','Completed'])
            ->where('sales_location_id', $shop)
            ->where('to_date', '<=', $toDate)
            ->orderBy('id', 'desc')->first();

        if($dailySale){
            $items = $dailySale->items()->with('product')->get();
        }

        $data['shop'] = $location;
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
                ['text' => 'Shop Stocks'],
            ],
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}