<?php

namespace App\Repositories\Stock;

use App\Company;
use App\Http\Requests\Stock\StockStoreRequest;
use App\Repositories\BaseRepository;
use App\SalesLocation;
use App\Stock;
use App\StockHistory;
use App\Store;
use Illuminate\Http\Request;

/**
 * Class StockRepository
 * @package App\Repositories\Stock
 */
class StockHistoryRepository extends BaseRepository
{
    /**
     * StockHistoryRepository constructor.
     * @param Stock|null $stock
     */
    public function __construct(Stock $stock = null)
    {
        $this->setModel($stock ?? new Stock());
    }

    public function index()
    {
        return $this->getCompaniesStock();
    }

    public function getCompaniesStock()
    {
        $companies = Company::whereIn('id', userCompanyIds(loggedUser()))->get();
        return $companies->map(function ($company) {
            $children = $this->getStoreGroup($company);
            return $this->mapProductsStock([
                'data_model' => 'AccountCategory',
                'id' => $company->id,
                'name' => $company->name,
                'code' => $company->code,
                'class' => 'table-primary',
                'min_level' => '-',
                'children' => $children,
            ]);
        });
    }

    public function getStoreGroup(Company $company)
    {
        $stores = collect([
            [
                'data_model' => 'StoreGroup',
                'id' => null,
                'name' => 'Vans',
                'code' => null,
                'class' => 'table-warning',
                'min_level' => '-',
                'children' => $this->salesLocationStokes('Sales Van', $company),
            ],
            [
                'data_model' => 'StoreGroup',
                'id' => null,
                'name' => 'Shops',
                'code' => null,
                'class' => 'table-warning',
                'min_level' => '-',
                'children' => $this->salesLocationStokes('Shop', $company),
            ],
            [
                'data_model' => 'StoreGroup',
                'id' => null,
                'name' => 'Stores',
                'code' => null,
                'class' => 'table-warning',
                'min_level' => '-',
                'children' => $this->storesStokes($company),
            ]
        ]);

        return $stores->map(function ($store){
            return $this->mapProductsStock($store);
        });
    }

    public function salesLocationStokes($type, Company $company)
    {
        $salesLocationStokes = SalesLocation::whereType($type)->has('dailySales')->where('company_id', $company->id)
            ->with('dailySales.items.product')->get();
        $salesLocationStokes = $salesLocationStokes->map(function ($item) use ($type){
            $dailySales =  $item->dailySales->whereIn('status', ['Draft','Active','Progress','Completed'])->sortByDesc('id')->first();
            $products = $this->mapProducts($dailySales->items);
            return $this->mapProductsStock([
                'data_model' => 'SalesLocation',
                'id' => null,
                'name' => $item->name,
                'code' => null,
                'class' => 'table-info',
                'min_level' => '-',
                'children' => $products
            ]);
        });
       return $salesLocationStokes;
    }

    public function storesStokes(Company $company)
    {
        $storesStokes = Store::has('stocks')->where('company_id', $company->id)->with('stocks.product')->get();
        $storesStokes = $storesStokes->map(function ($item){
            $products = $this->mapStoreStock($item);
            return $this->mapProductsStock([
                'data_model' => 'Store',
                'id' => null,
                'name' => $item->name,
                'code' => null,
                'class' => 'table-info',
                'min_level' => '-',
                'children' => $products
            ]);
        });
       return $storesStokes;
    }

    public function mapStoreStock(Store $store)
    {
        $stokes = $store->stocks;
        return $stokes->map(function ($item){
            return [
                'data_model' => 'Product',
                'id' => $item->product->id ?? 'None',
                'name' => $item->product->name ?? 'None',
                'code' => null,
                //'qty' => availableStockAsDate($item, carbon()->toDateString())['availableStock'],
                'qty' => $item->available_stock,
                'class' => 'table-success',
                'min_level' => $item->min_stock_level ?? 0,
            ];
        })->toArray();
    }

    public function mapProducts($products)
    {
        return $products->map(function ($item){
            $qty = ((float)($item->quantity ?? 00) + (float)($item->cf_qty ?? 00) + (float)($item->returned_qty ?? 00) + (float)($item->excess_qty ?? 00)) - ((float)($item->sold_qty ?? 00) + (float)($item->restored_qty ?? 00) + (float)($item->replaced_qty ?? 00) + (float)($item->shortage_qty ?? 00));
            return [
                'data_model' => 'Product',
                'id' => $item->product_id,
                'name' => $item->product->name ?? 'None',
                'code' => null,
                'qty' => $qty,
                'class' => 'table-success',
                'min_level' => '-',
            ];
        })->toArray();
    }

    public function mapProductsStock($products)
    {
        $items = $products['children'];
        $data = [
            'qty' => (float)array_sum(array_pluck($items, 'qty')),
        ];
        return array_merge($data, $products);
    }


    /**
     * Get the breadcrumbs of the stock module
     * @param string $method
     * @param Stock|null $stock
     * @return array|mixed
     */
    public function breadcrumbs(string $method, Stock $stock = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stock History']
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}
