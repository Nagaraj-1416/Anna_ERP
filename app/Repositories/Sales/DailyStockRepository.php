<?php

namespace App\Repositories\Sales;

use App\DailySaleItem;
use App\DailyStock;
use App\DailyStockItem;
use App\Http\Requests\Stock\StockStoreRequest;
use App\Product;
use App\Repositories\BaseRepository;
use App\Route;
use App\SalesLocation;
use App\Stock;
use App\StockHistory;
use Illuminate\Http\Request;

/**
 * Class DailyStockRepository
 * @package App\Repositories\Sales
 */
class DailyStockRepository extends BaseRepository
{
    /**
     * DailyStockRepository constructor.
     * @param DailyStock|null $stock
     */
    public function __construct(DailyStock $stock = null)
    {
        $this->setModel($stock ?? new DailyStock());
    }

    /**
     * @return mixed
     */
    public function grid()
    {
        $stocks = DailyStock::whereIn('company_id', userCompanyIds(loggedUser()))
            ->with('saleLocation', 'route', 'rep', 'store', 'company', 'preparedBy', 'items')->orderBy('id', 'desc');

        return $stocks->paginate(20)->toArray();
    }

    /**
     * @param $request
     * @return bool
     */
    public function save($request)
    {
        $route = Route::where('id', $request->input('route_id'))->first();
        $routeProducts = $route->products;

        $allocation = new DailyStock();
        $allocation->setAttribute('sales_location', 'Van');
        $allocation->setAttribute('sales_location_id', $request->input('sales_location_id'));
        $allocation->setAttribute('route_id', $request->input('route_id'));
        $allocation->setAttribute('rep_id', $request->input('rep_id'));
        $allocation->setAttribute('store_id', $request->input('store_id'));
        $allocation->setAttribute('prepared_by', auth()->id());
        $allocation->setAttribute('status', 'Pending');
        $allocation->setAttribute('company_id', $request->input('company_id'));
        $allocation->save();

        $routeProducts->each(function (Product $product) use ($request, $allocation){
            $stockItem = new DailyStockItem();
            $stockItem->setAttribute('daily_stock_id', $allocation->id);
            $stockItem->setAttribute('product_id', $product->id);
            $stockItem->setAttribute('store_id', $request->input('store_id'));
            $stockItem->setAttribute('available_qty', 0);
            $stockItem->setAttribute('default_qty', $product->pivot->default_qty ?? 0);
            $stockItem->setAttribute('required_qty', $product->pivot->default_qty ?? 0);
            $stockItem->save();
        });

        return true;
    }

    public function saveShop($request)
    {
        $shop = SalesLocation::where('id', $request->input('sales_location_id'))->first();
        $products = $shop->products;

        $allocation = new DailyStock();
        $allocation->setAttribute('sales_location', 'Shop');
        $allocation->setAttribute('sales_location_id', $request->input('sales_location_id'));
        $allocation->setAttribute('store_id', $request->input('store_id'));
        $allocation->setAttribute('prepared_by', auth()->id());
        $allocation->setAttribute('status', 'Pending');
        $allocation->setAttribute('company_id', $request->input('company_id'));
        $allocation->save();

        $products->each(function (Product $product) use ($request, $allocation){
            $stockItem = new DailyStockItem();
            $stockItem->setAttribute('daily_stock_id', $allocation->id);
            $stockItem->setAttribute('product_id', $product->id);
            $stockItem->setAttribute('store_id', $request->input('store_id'));
            $stockItem->setAttribute('available_qty', 0);
            $stockItem->setAttribute('default_qty', $product->pivot->default_qty ?? 0);
            $stockItem->setAttribute('required_qty', $product->pivot->default_qty ?? 0);
            $stockItem->save();
        });

        return true;
    }

    /**
     * @param DailyStock $dailyStock
     * @param $status
     * @return array
     */
    public function statusUpdate(DailyStock $dailyStock ,$status)
    {
        $dailyStock->setAttribute('status', $status);
        $dailyStock->save();
        return ['success' => true];
    }

    /**
     * @param string $method
     * @param DailyStock|null $dailyStock
     * @return array
     */
    public function breadcrumbs(string $method, DailyStock $dailyStock = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Allocations'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Allocations', 'route' => 'daily.stock.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Allocations', 'route' => 'daily.stock.index'],
                ['text' => 'Details'],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Allocations', 'route' => 'daily.stock.index'],
                ['text' => 'Allocation Details', 'route' => 'daily.stock.show', 'parameters' => [$dailyStock->id ?? null]],
                ['text' => 'Edit Details'],
            ],
            'change-route' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Allocations', 'route' => 'daily.stock.index'],
                ['text' => 'Allocation Details', 'route' => 'daily.stock.show', 'parameters' => [$dailyStock->id ?? null]],
                ['text' => 'Change Route'],
            ],
            'change-rep' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Allocations', 'route' => 'daily.stock.index'],
                ['text' => 'Allocation Details', 'route' => 'daily.stock.show', 'parameters' => [$dailyStock->id ?? null]],
                ['text' => 'Change Rep'],
            ],
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}
