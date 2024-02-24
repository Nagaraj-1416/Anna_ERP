<?php

namespace App\Repositories\Stock;

use App\Repositories\BaseRepository;
use App\Stock;
use App\Store;


/**
 * Class StoreStockRepository
 * @package App\Repositories\Stock
 */
class StoreStockRepository extends BaseRepository
{
    /**
     * @return array
     */
    public function index()
    {
        $data = [];
        $request = request();
        $toDate = $request->input('date') ?? carbon()->toDateString();
        $storeID = $request->input('storeId');
        $search = $request->input('query');

        $stocks = Stock::where('store_id', $storeID)->where(function ($query) use ($search) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        });

        $allStocks = $stocks->with('product')->get();
        $allStocks = $allStocks->map(function ($item) use ($toDate){
            $item->in_stock_as_at = availableStockAsDate($item, $toDate)['inStock'];
            $item->out_stock_as_at = availableStockAsDate($item, $toDate)['outStock'];
            //$item->stock_as_at = availableStockAsDate($item, $toDate)['availableStock'];
            $item->stock_as_at = $item->available_stock;
            return $item;
        });

        $noStocks = $allStocks->where('stock_as_at', 0)->count();

        $store = Store::where('id', $storeID)->first();

        $data['store'] = $store ? $store->toArray() : '';
        $data['stocks'] = $allStocks;
        $data['noStocks'] = $noStocks;
        $data['request'] = $request->toArray();

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
                ['text' => 'Store Stocks'],
            ],
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}