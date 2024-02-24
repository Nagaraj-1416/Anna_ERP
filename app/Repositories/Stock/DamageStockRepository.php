<?php

namespace App\Repositories\Stock;

use App\Company;
use App\DailySale;
use App\DailySaleItem;
use App\Product;
use App\Repositories\BaseRepository;
use App\SalesLocation;
use App\Stock;
use App\Vehicle;

/**
 * Class VanStockRepository
 * @package App\Repositories\Stock
 */
class DamageStockRepository extends BaseRepository
{
    /**
     * @return array
     */
    public function indexOld()
    {
        $data = [];
        $request = request();
        $companyId = $request->input('companyId');
        $toDate = $request->input('date') ?? carbon()->toDateString();

        $company = Company::where('id', $companyId)->first();

        $products = Product::where('type', 'Finished Good')->get();

        $products = $products->map(function ($product) use ($company, $toDate){
            $product->damagedQty = getDamageQtySumAsAt($product, $company, $toDate)['damagedQty'];
            $product->damagedItems = getDamageQtySumAsAt($product, $company, $toDate)['damagedItems'];
            return $product;
        });

        $products = $products->reject(function ($product) {
            return $product->damagedQty == 0;
        });

        $data['company'] = $company;
        $data['items'] = $products;
        return $data;
    }

    public function index()
    {
        $filter = \request()->input('filter');
        $storeId = \request()->input('storeId');
        $lastWeek = carbon()->subWeek();
        $stocks = Stock::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('category', 'Damage')
            ->with('product', 'store', 'company')
            ->orderBy('id', 'desc');

        if ($filter) {
            switch ($filter) {
                case 'recentlyCreated':
                    $stocks->where('created_at', '>', $lastWeek);
                    break;
                case 'recentlyModified':
                    $stocks->where('updated_at', '>', $lastWeek);
                    break;
            }
        }

        if ($storeId) {
            $stocks->where('store_id', $storeId);
        }

        return $stocks->paginate(12)->toArray();
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
                ['text' => 'Damaged Stocks'],
            ],
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}