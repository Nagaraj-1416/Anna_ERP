<?php

namespace App\Repositories\Stock;

use App\Http\Requests\Stock\StockReturnStoreRequest;
use App\Http\Requests\Stock\StockStoreRequest;
use App\Http\Requests\Stock\StockTransferStoreRequest;
use App\Product;
use App\PurchaseReturn;
use App\PurchaseReturnItem;
use App\Repositories\BaseRepository;
use App\SalesLocation;
use App\Stock;
use App\StockHistory;
use App\StockTransfer;
use App\StockTransferItem;
use App\Store;
use Illuminate\Http\Request;

/**
 * Class StockRepository
 * @package App\Repositories\Stock
 */
class StockRepository extends BaseRepository
{
    /**
     * CustomerRepository constructor.
     * @param Stock|null $stock
     */
    public function __construct(Stock $stock = null)
    {
        $this->setModel($stock ?? new Stock());
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['type', 'category', 'product_id', 'store_id', 'vehicle_id', 'available_stock'];
        $searchingColumns = ['type', 'category', 'product_id', 'store_id', 'vehicle_id', 'available_stock'];
        $relationColumns = [
            'product' => [
                [
                    'column' => 'name', 'as' => 'product_name'
                ]
            ],
            'store' => [
                [
                    'column' => 'name', 'as' => 'store_name'
                ]
            ],
            'vehicle' => [
                [
                    'column' => 'vehicle_no', 'as' => 'vehicle_name'
                ]
            ]
        ];
        $data = $this->getTableData($request, $columns, $searchingColumns, $relationColumns);
        $data['data'] = array_map(function ($item) {
            if ($item['type'] == 'Manual') {
                $item['product_name'] = '<a href="' . route('stock.show', $item['id']) . '">' . $item['product_name'] . '</a>';
                $item['action'] = "<div class=\"button-group\">";
                $item['action'] .= actionBtn('Show', null, ['stock.show', [$item['id']]], ['class' => 'btn-success']);
                $item['action'] .= actionBtn('Edit', null, ['stock.edit', [$item['id']]]);
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-stock']);
                $item['action'] .= "</div>";
            } else {
                $item['action'] = "";
            }
            return $item;
        }, $data['data']);
        return $data;
    }

    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $typeId = \request()->input('typeId');
        $categoryId = \request()->input('categoryId');
        $storeId = \request()->input('storeId');
        $lastWeek = carbon()->subWeek();
        $stocks = Stock::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('category', 'Main')
            ->with('product', 'store', 'company')
            ->orderBy('id', 'desc');
        if ($search) {
            $stocks->where('type', 'LIKE', '%' . $search . '%')
                ->orWhere('category', 'LIKE', '%' . $search . '%')
                ->orwhere(function ($query) use ($search) {
                    $query->whereHas('product', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('code', 'LIKE', '%' . $search . '%');
                    });
                })
                ->orwhere(function ($query) use ($search) {
                    $query->whereHas('store', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    });
                });
        }

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

        if ($typeId) {
            $stocks->where(function ($query) use ($search, $typeId) {
                $query->whereHas('product', function ($q) use ($search, $typeId) {
                    $q->where('type', $typeId);
                });
            });
        }

        if ($categoryId) {
            $stocks->where(function ($query) use ($search, $categoryId) {
                $query->whereHas('product', function ($q) use ($search, $categoryId) {
                    $q->where('category_id', $categoryId);
                });
            });
        }

        if ($storeId) {
            $stocks->where('store_id', $storeId);
        }

        $stocks = $stocks->paginate(20);
        $stocksData = $stocks->getCollection()->transform(function($item){
            $item->stock_as_at = availableStockAsDate($item, carbon()->toDateString())['availableStock'];
            return $item;
        });
        $stocks->setCollection($stocksData);
        return $stocks->toArray();
    }

    public function save(StockStoreRequest $request)
    {
        $request->merge(['type' => 'Manual']);
        $products = $request->input('product_id');
        foreach ($products as $key => $product) {
            $availableStock = (float) $request->input('available_stock')[$key] ?? 0.0;
            $productData = Product::where('id', $product)->first();
            $stock = Stock::where('company_id', $request->input('company_id'))
                ->where('product_id', $product)
                ->where('store_id', $request->input('store_id'))->first();

            if($stock){
                $finalAvailableStock = $availableStock + ($stock->available_stock ?? 0);
                $stock->setAttribute('available_stock', $finalAvailableStock);
                $stock->save();

                /** update stock history */
                $history = new StockHistory();
                $history->setAttribute('stock_id', $stock->getAttribute('id'));
                $history->setAttribute('quantity', $availableStock);
                $history->setAttribute('rate', $request->input('rate')[$key] ? $request->input('rate')[$key] : 0);

                if($request->input('production_unit') != null){
                    $history->setAttribute('type', 'Purchase');
                } else {
                    $history->setAttribute('type', 'Opening');
                }

                $history->setAttribute('transaction', 'In');
                $history->setAttribute('trans_date', carbon()->now()->toDateString());
                $history->setAttribute('trans_description', $request->input('notes'));
                $history->setAttribute('production_unit_id', $request->input('production_unit'));
                $history->setAttribute('store_id', $request->input('store_id'));
                $history->save();

            }else{
                $newStock = new Stock();
                $newStock->setAttribute('store_id', $request->input('store_id'));
                $newStock->setAttribute('available_stock', $availableStock);
                $newStock->setAttribute('product_id', $product);
                $newStock->setAttribute('notes', $request->input('notes'));
                $newStock->setAttribute('type', $request->input('type'));
                $newStock->setAttribute('company_id', $request->input('company_id'));
                $newStock->setAttribute('min_stock_level', $productData->min_stock_level);
                $newStock->save();

                /** update stock history */
                $newStockHistory = new StockHistory();
                $newStockHistory->setAttribute('stock_id', $newStock->getAttribute('id'));
                $newStockHistory->setAttribute('quantity', $availableStock);
                $newStockHistory->setAttribute('rate', $request->input('rate')[$key] ? $request->input('rate')[$key] : 0);

                if($request->input('production_unit')){
                    $newStockHistory->setAttribute('type', 'Purchase');
                } else {
                    $newStockHistory->setAttribute('type', 'Opening');
                }

                $newStockHistory->setAttribute('transaction', 'In');
                $newStockHistory->setAttribute('trans_date', carbon()->now()->toDateString());
                $newStockHistory->setAttribute('trans_description', $request->input('notes'));
                $newStockHistory->setAttribute('production_unit_id', $request->input('production_unit'));
                $newStockHistory->setAttribute('store_id', $request->input('store_id'));
                $newStockHistory->save();
            }
        }

        return true;
    }

    /**
     * @param Request $request
     * @param Stock $stock
     * @return Stock
     */
    public function update(Request $request, Stock $stock)
    {
        $this->setModel($stock);

        $stock->setAttribute('min_stock_level', $request->input('minimum_stock_level'));
        $stock->save();

        return $stock;
    }

    /**
     * @param Stock $stock
     * @return array
     * @throws \Exception
     */
    public function delete(Stock $stock): array
    {
        $stock->delete();
        return ['success' => true];
    }

    public function searchStock(Request $request)
    {
        $stockHistory = [];
        $productId = $request->input('productId');
        $storeStokes = Stock::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereProductId($productId)->with(['histories', 'store'])->get();
        foreach ($storeStokes as $stoke) {
            $lastTransaction = $stoke->histories->sortByDesc('id')->first();
            $inStock = $stoke->histories->where('transaction', 'In')->sortByDesc('id')->first();
            $outStock = $stoke->histories->where('transaction', 'Out')->sortByDesc('id')->first();
            //$availableStock = availableStockAsDate($stoke, carbon()->toDateString())['availableStock'];
            $availableStock = $stoke->available_stock;
            if ($availableStock <= 0) continue;
            $lastTransactionDate = $lastTransaction->trans_date ?? null;
            $data = [
                'name' => $stoke->store->name ?? 'N/A',
                'available_stock' => $availableStock,
                'last_in' => $inStock->quantity ?? 00,
                'last_out' => $outStock->quantity ?? 00,
                'last_transaction_at' =>  $lastTransactionDate ? carbon($lastTransactionDate)->format('F j, Y') : 'None'
            ];
            array_push($stockHistory, $data);
        }

        $salesLocationStokes = SalesLocation::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereHas('dailySales', function ($dailySales) use ($productId) {
            $dailySales->where(function ($query) use ($productId) {
                $query->whereHas('items', function ($items) use ($productId) {
                    $items->where('product_id', $productId);
                });
            })->orWhere(function ($query) use ($productId) {
                $query->whereHas('stockHistories', function ($history) use ($productId) {
                    $history->whereHas('stock', function ($stock) use ($productId) {
                        $stock->where('product_id', $productId);
                    });
                });
            });
        })->with(['dailySales.items' => function ($items) use ($productId) {
            $items->where('product_id', $productId);
        }, 'dailySales.stockHistories' => function ($history) use ($productId) {
            $history->whereHas('stock', function ($stock) use ($productId) {
                $stock->where('product_id', $productId);
            });
        }])->get();

        foreach ($salesLocationStokes as $vanStoke) {
            $dailySales = $vanStoke->dailySales;
            $dailySales = $dailySales->sortByDesc('id');
            $lastDailySales = $dailySales->first();
            $lastProductItems = $lastDailySales->items->first();
            $stockHistories = $lastDailySales->stockHistories->sortByDesc('id');
            $inStock = $stockHistories->where('transaction', 'In')->sortByDesc('id')->first();
            $outStock = $stockHistories->where('transaction', 'Out')->sortByDesc('id')->first();
            $availableStock = 00;
            if ($lastProductItems) {
                $availableStock = (($lastProductItems->quantity ?? 0) + ($lastProductItems->cf_qty ?? 0) + ($lastProductItems->returned_qty ?? 0) + ($lastProductItems->excess_qty ?? 0)) - (($lastProductItems->sold_qty ?? 0) + ($lastProductItems->restored_qty ?? 0) + ($lastProductItems->replaced_qty ?? 0) + ($lastProductItems->shortage_qty ?? 0));
            }
            if ($availableStock <= 0) continue;
            $lastTransactionDate = $stockHistories->first()->trans_date ?? null;
            $data = [
                'name' => $vanStoke->name ?? 'N/A',
                'available_stock' => $availableStock,
                'last_out' => $inStock->quantity ?? 00,
                'last_in' => $outStock->quantity ?? 00,
                'last_transaction_at' =>  $lastTransactionDate ? carbon($lastTransactionDate)->format('F j, Y') : 'None'
            ];
            array_push($stockHistory, $data);
        }
        return $stockHistory;
    }

    public function transferGrid()
    {
        $transfers = StockTransfer::whereIn('company_id', userCompanyIds(loggedUser()))
            ->with('transferBy', 'vehicle', 'transferFrom', 'transferTo', 'company')
            ->orderBy('id', 'desc');

        return $transfers->paginate(20)->toArray();
    }

    /**
     * @param Store $store
     * @param StockTransferStoreRequest $request
     * @return bool
     */
    public function transferStore(Store $store, StockTransferStoreRequest $request)
    {
        $transferToId = $request->input('transfer_to');
        $vehicleId = $request->input('vehicle_id');
        $transferNotes = $request->input('notes');
        $transfers = $request->input('transfers');
        $stockIds = array_get($transfers, 'id');
        $transfer_qty = array_get($transfers, 'qty');

        if ($stockIds) {
            foreach ($stockIds as $key => $val) {

                /** get stock details of transfer from store and update */
                $transferStock = Stock::where('id', $key)->first();

                $stocksTransTo = Stock::where('store_id', $transferToId)
                    ->where('product_id', $transferStock->product_id)
                    ->first();

                $transferToStore = Store::find($transferToId);

                if($stocksTransTo){
                    $stocksTransTo->available_stock = ((float)$stocksTransTo->available_stock + (int)array_get($transfer_qty, $key));
                    $stocksTransTo->save();

                    /** add stock history as Transfer */
                    $history = new StockHistory();
                    $history->setAttribute('stock_id', $stocksTransTo->getAttribute('id'));
                    $history->setAttribute('quantity', (int)array_get($transfer_qty, $key));
                    $history->setAttribute('type', 'Transfer');
                    $history->setAttribute('transaction', 'In');
                    $history->setAttribute('trans_date', carbon()->now()->toDateString());
                    $history->setAttribute('trans_description', 'Stock transferred from '.$store->name);
                    $history->save();
                }else{
                    $newStock = new Stock();
                    $newStock->setAttribute('store_id', $transferToStore->id);
                    $newStock->setAttribute('available_stock', (int)array_get($transfer_qty, $key));
                    $newStock->setAttribute('product_id', $transferStock->product_id);
                    $newStock->setAttribute('notes', 'Stock transferred from '.$store->name);
                    $newStock->setAttribute('type', 'Manual');
                    $newStock->setAttribute('company_id', $transferToStore->company_id);
                    $newStock->setAttribute('min_stock_level', '5000');
                    $newStock->save();

                    /** add new stock history */
                    $newStockHistory = new StockHistory();
                    $newStockHistory->setAttribute('stock_id', $newStock->getAttribute('id'));
                    $newStockHistory->setAttribute('quantity', (int)array_get($transfer_qty, $key));
                    $newStockHistory->setAttribute('type', 'Transfer');
                    $newStockHistory->setAttribute('transaction', 'In');
                    $newStockHistory->setAttribute('trans_date', carbon()->now()->toDateString());
                    $newStockHistory->setAttribute('trans_description', 'Stock transferred from '.$store->name);
                    $newStockHistory->save();
                }

                /** update transfer from store stock  */
                $transferStock->available_stock = ($transferStock->available_stock - (int)array_get($transfer_qty, $key));
                $transferStock->save();

                /** update transfer from stock history */
                $oldStockHistory = new StockHistory();
                $oldStockHistory->setAttribute('stock_id', $transferStock->getAttribute('id'));
                $oldStockHistory->setAttribute('quantity', (int)array_get($transfer_qty, $key));
                $oldStockHistory->setAttribute('type', 'Transfer');
                $oldStockHistory->setAttribute('transaction', 'Out');
                $oldStockHistory->setAttribute('trans_date', carbon()->now()->toDateString());
                $oldStockHistory->setAttribute('trans_description', 'Stock transferred to '.$transferToStore->name);
                $oldStockHistory->save();
            }
        }
        return true;
    }

    public function stockTrans()
    {
        $productId = \request()->input('productId');
        if ($productId) {
            $stockIds = Stock::where('product_id', $productId)
                ->whereIn('company_id', userCompanyIds(loggedUser()))->pluck('id');
        }else{
            $stockIds = Stock::whereIn('company_id', userCompanyIds(loggedUser()))->pluck('id');
        }
        $histories = StockHistory::whereIn('stock_id', $stockIds)->with('stock', 'stock.product', 'stock.store')->orderBy('id', 'desc');
        return $histories->paginate(20)->toArray();
    }

    public function purchasedHistories()
    {
        $stockIds = Stock::whereIn('company_id', userCompanyIds(loggedUser()))->pluck('id');
        $histories = StockHistory::whereIn('stock_id', $stockIds)->where('type', 'Purchase')->with('stock', 'productionUnit', 'store', 'stock.product', 'stock.store')->orderBy('id', 'desc');
        return $histories->paginate(20)->toArray();
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
                ['text' => 'Stocks List'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stocks List', 'route' => 'stock.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stocks List', 'route' => 'stock.index'],
                ['text' => 'Stock Details'],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stocks List', 'route' => 'stock.index'],
                ['text' => 'Stock Details'],
                ['text' => 'Edit'],
            ],
            'search' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stock Search']
            ],
            'stock-out' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stock Out']
            ],
            'transfers' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Transfers']
            ],
            'transfer-create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Transfers', 'route' => 'stock.transfer.index'],
                ['text' => 'Create']
            ],
            'stock-trans' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stock Transactions']
            ],
            'purchased-history' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Purchased Histories']
            ],
            'add-damage' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stocks List', 'route' => 'stock.index'],
                ['text' => 'Stock Details'],
                ['text' => 'Add damage'],
            ],
            'returns' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Returns']
            ],
            'return-create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Returns', 'route' => 'stock.return.index'],
                ['text' => 'Create']
            ],
            'review-stock' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Stock Summary', 'route' => 'stock.summary.index'],
                ['text' => 'Stocks List', 'route' => 'stock.index'],
                ['text' => 'Stock Details'],
                ['text' => 'review Stock'],
            ],
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    public function returnGrid()
    {
        $filter = \request()->input('filter');
        $storeId = \request()->input('storeId');
        $lastWeek = carbon()->subWeek();
        $stocks = Stock::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('category', 'Return')
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

    public function returnStocks(Store $store, StockReturnStoreRequest $request)
    {
        $returnedToId = $request->input('return_to');
        $transferNotes = $request->input('notes');
        $returns = $request->input('returns');
        $stockIds = array_get($returns, 'id');
        $returnQty = array_get($returns, 'qty');
        $returnPrices = array_get($returns, 'price');

        $returnToStore = Store::where('type', 'Return')
            ->where('storeable_id', $returnedToId)
            ->where('storeable_type', 'App\ProductionUnit')
            ->first();

        /** save return records */
        $purchaseReturn = new PurchaseReturn();
        $purchaseReturn->setAttribute('date', carbon()->now()->toDateString());
        $purchaseReturn->setAttribute('notes', $transferNotes);
        $purchaseReturn->setAttribute('category', 'Store');
        $purchaseReturn->setAttribute('unit_id', $returnedToId);
        $purchaseReturn->setAttribute('prepared_by', auth()->user()->id);
        $purchaseReturn->setAttribute('prepared_on', carbon()->now()->toDateString());
        $purchaseReturn->setAttribute('is_approved', 'yes');
        $purchaseReturn->setAttribute('approved_by', auth()->user()->id);
        $purchaseReturn->setAttribute('approved_on', carbon()->now()->toDateString());
        $purchaseReturn->setAttribute('company_id', $store->company_id);
        $purchaseReturn->save();

        /** save purchase return items */
        if ($stockIds) {
            foreach ($stockIds as $key => $val) {

                /** get stock details of transfer from store and update */
                $stocksReturnFrom1 = Stock::where('id', $key)->first();

                $purchaseReturnItem = new PurchaseReturnItem();
                $purchaseReturnItem->setAttribute('returned_qty', (int)array_get($returnQty, $key));
                $purchaseReturnItem->setAttribute('returned_rate', array_get($returnPrices, $key));
                $purchaseReturnItem->setAttribute('returned_amount', ((int)array_get($returnQty, $key) * array_get($returnPrices, $key)));
                $purchaseReturnItem->setAttribute('purchase_return_id', $purchaseReturn->id);
                $purchaseReturnItem->setAttribute('product_id', $stocksReturnFrom1->product_id);
                $purchaseReturnItem->setAttribute('company_id', $store->company_id);
                $purchaseReturnItem->save();
            }
        }

        /** register stock records */
        if ($stockIds) {
            foreach ($stockIds as $key => $val) {

                /** get stock details of transfer from store and update */
                $stocksReturnFrom = Stock::where('id', $key)->first();

                $stocksReturnTo = Stock::where('store_id', $returnToStore->id)
                    ->where('product_id', $stocksReturnFrom->product_id)
                    ->first();

                if($stocksReturnTo){
                    $stocksReturnTo->available_stock = ((float)$stocksReturnTo->available_stock + (int)array_get($returnQty, $key));
                    $stocksReturnTo->save();

                    /** add stock history as Transfer */
                    $history = new StockHistory();
                    $history->setAttribute('stock_id', $stocksReturnTo->getAttribute('id'));
                    $history->setAttribute('quantity', (int)array_get($returnQty, $key));
                    $history->setAttribute('type', 'Return');
                    $history->setAttribute('transaction', 'In');
                    $history->setAttribute('trans_date', carbon()->now()->toDateString());
                    $history->setAttribute('trans_description', 'Stock returned from '.$store->name);
                    $history->save();
                }else{
                    $newStock = new Stock();
                    $newStock->setAttribute('store_id', $returnToStore->id);
                    $newStock->setAttribute('available_stock', (int)array_get($returnQty, $key));
                    $newStock->setAttribute('product_id', $stocksReturnFrom->product_id);
                    $newStock->setAttribute('notes', 'Stock returned from '.$store->name);
                    $newStock->setAttribute('type', 'Manual');
                    $newStock->setAttribute('company_id', $returnToStore->company_id);
                    $newStock->setAttribute('min_stock_level', '5000');
                    $newStock->save();

                    /** add new stock history */
                    $newStockHistory = new StockHistory();
                    $newStockHistory->setAttribute('stock_id', $newStock->getAttribute('id'));
                    $newStockHistory->setAttribute('quantity', (int)array_get($returnQty, $key));
                    $newStockHistory->setAttribute('type', 'Return');
                    $newStockHistory->setAttribute('transaction', 'In');
                    $newStockHistory->setAttribute('trans_date', carbon()->now()->toDateString());
                    $newStockHistory->setAttribute('trans_description', 'Stock returned from '.$store->name);
                    $newStockHistory->save();
                }

                /** update transfer from store stock  */
                $stocksReturnFrom->available_stock = ($stocksReturnFrom->available_stock - (int)array_get($returnQty, $key));
                $stocksReturnFrom->save();

                /** update transfer from stock history */
                $oldStockHistory = new StockHistory();
                $oldStockHistory->setAttribute('stock_id', $stocksReturnFrom->getAttribute('id'));
                $oldStockHistory->setAttribute('quantity', (int)array_get($returnQty, $key));
                $oldStockHistory->setAttribute('type', 'Return');
                $oldStockHistory->setAttribute('transaction', 'Out');
                $oldStockHistory->setAttribute('trans_date', carbon()->now()->toDateString());
                $oldStockHistory->setAttribute('trans_description', 'Stock returned to '.$returnToStore->name);
                $oldStockHistory->save();
            }
        }
        return true;
    }
}
