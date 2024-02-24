<?php

namespace App\Http\Controllers\Stock;

use App\Exports\StockHistoriesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\AddDamageRequest;
use App\Http\Requests\Stock\ReviewStockRequest;
use App\Http\Requests\Stock\StockReturnStoreRequest;
use App\Http\Requests\Stock\StockStoreRequest;
use App\Http\Requests\Stock\StockTransferStoreRequest;
use App\Product;
use App\Repositories\Stock\StockRepository;
use App\Stock;
use App\StockHistory;
use App\StockReview;
use App\StockReviewItem;
use App\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class StockController extends Controller
{
    /**
     * @var StockRepository
     */
    protected $stock;

    /**
     * StockController constructor.
     * @param StockRepository $stock
     */
    public function __construct(StockRepository $stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumb = $this->stock->breadcrumbs('index');
        if (\request()->ajax()) {
            $products = $this->stock->grid();
            return response()->json($products);
        }
        return view('stock.list.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()) {
            return $this->stock->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $breadcrumb = $this->stock->breadcrumbs('create');
        return view('stock.list.create', compact('breadcrumb'));
    }

    /**
     * @param StockStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StockStoreRequest $request)
    {
        $this->stock->save($request);
        alert()->success('Stock created successfully', 'Success')->persistent();
        return redirect()->route('stock.index');
    }

    /**
     * @param Stock $stock
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Stock $stock)
    {
        $breadcrumb = $this->stock->breadcrumbs('show', $stock);

        $histories = $stock->histories()->get();
        $inTotal = $stock->histories()->where('transaction', 'In')->sum('quantity');
        $outTotal = $stock->histories()->where('transaction', 'Out')->sum('quantity');
        return view('stock.list.show', compact('breadcrumb', 'stock', 'histories', 'inTotal', 'outTotal'));
    }

    /**
     * @param Stock $stock
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Stock $stock)
    {
        $breadcrumb = $this->stock->breadcrumbs('edit', $stock);
        $stock->items = [
            [
                'product_id' => $stock->product_id,
                'product_name' => $stock->product->name ?? 'None',
                'available_stock' => $stock->available_stock,
            ]
        ];
        return view('stock.list.edit', compact('breadcrumb', 'stock'));
    }

    /**
     * @param Stock $stock
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Stock $stock)
    {
        /*$availableStock = $request->input('available_stock')[0] ?? 0;
        $product =  (int) $request->input()['product_id'] ?? 0;
        $request->merge(['product_id' => $product]);
        $request->merge(['available_stock' => $availableStock]);*/
        $request = request();
        $this->validate($request, [
            'minimum_stock_level' => 'required'
        ]);
        $this->stock->update($request, $stock);
        alert()->success('Stock updated successfully', 'Success')->persistent();
        return redirect()->route('stock.index');
    }

    /**
     * @param Stock $stock
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Stock $stock): JsonResponse
    {
        $response = $this->stock->delete($stock);
        return response()->json($response);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        $breadcrumb = $this->stock->breadcrumbs('search');
        if (\request()->ajax()){
            return $this->stock->searchStock(request());
        }
        return view('stock.search.index', compact('breadcrumb'));
    }

    public function stockOut()
    {
        $breadcrumb = $this->stock->breadcrumbs('stock-out');
        return view('stock.out.index', compact('breadcrumb'));
    }

    public function doStockOut()
    {
        $request = request();
        $request->validate([
            'store_id' => 'required',
            'out_qty.*' => 'required',
            'rate.*' => 'required',
            'product_id.*' => 'required',
            'notes' => 'required'
        ]);

        $products = $request->input('product_id');
        foreach ($products as $key => $product) {
            $stock = Stock::where('product_id', $product)
                ->where('store_id', $request->input('store_id'))->first();
            if($stock){
                $stock->setAttribute('available_stock', ($stock->getAttribute('available_stock') - $request->input('out_qty')[$key]));
                $stock->save();

                /** enter stock history */
                $history = new StockHistory();
                $history->setAttribute('stock_id', $stock->getAttribute('id'));
                $history->setAttribute('quantity', $request->input('out_qty')[$key]);
                $history->setAttribute('rate', $request->input('rate')[$key]);
                $history->setAttribute('type', 'Sale');
                $history->setAttribute('transaction', 'Out');
                $history->setAttribute('trans_date', carbon()->now()->toDateString());
                $history->setAttribute('trans_description', $request->input('notes'));
                $history->setAttribute('sales_location_id', $request->input('sales_location'));
                $history->setAttribute('store_id', $request->input('store_id'));
                $history->save();
            }
        }

        alert()->success('Stock out created successfully', 'Success')->persistent();
        return redirect()->route('stock.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
     */
    public function transferList()
    {
        $breadcrumb = $this->stock->breadcrumbs('transfers');

        if (\request()->ajax()) {
            $products = $this->stock->transferGrid();
            return response()->json($products);
        }

        return view('stock.transfer.index', compact('breadcrumb'));
    }

    /**
     * @param Store $store
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function transfer(Store $store)
    {
        $breadcrumb = $this->stock->breadcrumbs('transfer-create');
        $stocks = Stock::where('store_id', $store->id)->get();
        $stocks = $stocks->reject(function (Stock $stock) {
            return $stock->available_stock <= 0;
        });
        return view('stock.transfer.create', compact('breadcrumb', 'store', 'stocks'));
    }

    /**
     * @param StockTransferStoreRequest $request
     * @param Store $store
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doTransfer(StockTransferStoreRequest $request, Store $store)
    {
        $this->stock->transferStore($store, $request);
        alert()->success('Stock transferred successfully', 'Success')->persistent();
        return redirect()->route('stock.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stockTrans()
    {
        $breadcrumb = $this->stock->breadcrumbs('stock-trans');
        if (\request()->ajax()) {
            $products = $this->stock->stockTrans();
            return response()->json($products);
        }
        return view('stock.trans.index', compact('breadcrumb'));
    }

    public function purchasedHistories()
    {
        $breadcrumb = $this->stock->breadcrumbs('purchased-history');
        if (\request()->ajax()) {
            $histories = $this->stock->purchasedHistories();
            return response()->json($histories);
        }
        return view('stock.purchased.index', compact('breadcrumb', 'histories'));
    }

    public function undoPurchase($stockId)
    {
        $historyId = \request()->input('historyId');
        $history = StockHistory::where('id', $historyId)->where('stock_id', $stockId)->first();
        $stock = Stock::where('id', $stockId)->first();

        /** reduce stock from the available balance */
        if($stock->available_stock > $history->quantity){
            $stock->available_stock = ($stock->available_stock - $history->quantity);
            $stock->save();

            /** delete relevant history from stock */
            $history->delete();
        }
        return ['success' => true];
    }

    /**
     * @param Stock $stock
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addDamage(Stock $stock)
    {
        $breadcrumb = $this->stock->breadcrumbs('add-damage', $stock);
        $stock->items = [
            [
                'product_id' => $stock->product_id,
                'product_name' => $stock->product->name ?? 'None',
                'available_stock' => $stock->available_stock,
            ]
        ];
        return view('stock.list.add-damage', compact('breadcrumb', 'stock'));
    }

    /**
     * @param AddDamageRequest $request
     * @param Stock $stock
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeDamage(AddDamageRequest $request, Stock $stock)
    {
        $damagedQty = $request->input('damaged_qty');

        /** get last rate */
        $rateHistory = StockHistory::where('stock_id', $stock->id)
            ->where('transaction', 'In')
            ->where('type', 'Purchase')
            ->orderBy('id', 'desc')
            ->first();
        $rate = $rateHistory->rate ?? 0;

        $store = Store::where('type', 'Damage')
            ->where('company_id', $stock->getAttribute('company_id'))
            ->first();
        if($store){
            /** check stock available or not  */
            $damagedStock = Stock::where('category', 'Damage')
                ->where('company_id', $stock->getAttribute('company_id'))
                ->where('product_id', $stock->getAttribute('product_id'))
                ->where('store_id', $store->id)->first();

            if($damagedStock){
                /** deduct the qty from the available stock */
                $stock->available_stock = ($stock->available_stock - $damagedQty);
                $stock->save();

                /** add stock history to damage from stock */
                $fromStockHis = new StockHistory();
                $fromStockHis->setAttribute('stock_id', $stock->getAttribute('id'));
                $fromStockHis->setAttribute('quantity', $damagedQty);
                $fromStockHis->setAttribute('rate', 0);
                $fromStockHis->setAttribute('type', 'Damage');
                $fromStockHis->setAttribute('transaction', 'Out');
                $fromStockHis->setAttribute('trans_date', carbon()->now()->toDateString());
                $fromStockHis->setAttribute('trans_description', $request->input('notes'));
                $fromStockHis->setAttribute('store_id', $stock->getAttribute('store_id'));
                $fromStockHis->save();

                /** add qty to damage stock */
                $damagedStock->available_stock = ($damagedStock->available_stock + $damagedQty);
                $damagedStock->save();

                /** add damaged history */
                $dgStockHis = new StockHistory();
                $dgStockHis->setAttribute('stock_id', $damagedStock->getAttribute('id'));
                $dgStockHis->setAttribute('quantity', $damagedQty);
                $dgStockHis->setAttribute('rate', $rate);
                $dgStockHis->setAttribute('type', 'Damage');
                $dgStockHis->setAttribute('transaction', 'In');
                $dgStockHis->setAttribute('trans_date', carbon()->now()->toDateString());
                $dgStockHis->setAttribute('trans_description', $request->input('notes'));
                $dgStockHis->setAttribute('store_id', $store->id);
                $dgStockHis->save();

            }else{
                /** deduct the qty from the available stock */
                $stock->available_stock = ($stock->available_stock - $damagedQty);
                $stock->save();

                /** add stock history to damage from stock */
                $fromStockHis = new StockHistory();
                $fromStockHis->setAttribute('stock_id', $stock->getAttribute('id'));
                $fromStockHis->setAttribute('quantity', $damagedQty);
                $fromStockHis->setAttribute('rate', 0);
                $fromStockHis->setAttribute('type', 'Damage');
                $fromStockHis->setAttribute('transaction', 'Out');
                $fromStockHis->setAttribute('trans_date', carbon()->now()->toDateString());
                $fromStockHis->setAttribute('trans_description', $request->input('notes'));
                $fromStockHis->setAttribute('store_id', $stock->getAttribute('store_id'));
                $fromStockHis->save();

                /** need to create a damaged stock and add stock history */
                $newDgStock = new Stock();
                $newDgStock->setAttribute('store_id', $store->id);
                $newDgStock->setAttribute('available_stock', $damagedQty);
                $newDgStock->setAttribute('product_id', $stock->getAttribute('product_id'));
                $newDgStock->setAttribute('notes', 'Stock transferred to damaged store');
                $newDgStock->setAttribute('type', 'Auto');
                $newDgStock->setAttribute('category', 'Damage');
                $newDgStock->setAttribute('company_id', $stock->getAttribute('company_id'));
                $newDgStock->setAttribute('min_stock_level', $stock->getAttribute('min_stock_level'));
                $newDgStock->save();

                /** add damaged history */
                $newDgStockHis = new StockHistory();
                $newDgStockHis->setAttribute('stock_id', $newDgStock->getAttribute('id'));
                $newDgStockHis->setAttribute('quantity', $damagedQty);
                $newDgStockHis->setAttribute('rate', $rate);
                $newDgStockHis->setAttribute('type', 'Damage');
                $newDgStockHis->setAttribute('transaction', 'In');
                $newDgStockHis->setAttribute('trans_date', carbon()->now()->toDateString());
                $newDgStockHis->setAttribute('trans_description', $request->input('notes'));
                $newDgStockHis->setAttribute('store_id', $store->id);
                $newDgStockHis->save();
            }
        }
        return redirect()->route('stock.show', $stock);
    }

    /**
     * @param Stock $stock
     * @return mixed
     */
    public function exportHistory(Stock $stock)
    {
        if (request()->input()) {
            return $this->exportHistoryExcel($stock);
        }
        $data = [];
        $data['histories'] = $stock->histories;
        $data['stock'] = $stock;
        $data['product'] = $stock->product;
        $pdf = PDF::loadView('stock.list.histories', $data);
        return $pdf->download('Stock Histories (' . $stock->product->code . ')' . '.pdf');
    }

    public function exportHistoryExcel(Stock $stock)
    {
        return Excel::download(new StockHistoriesExport($stock), 'Stock Histories (' . $stock->product->code . ')' . '.xlsx', 'Xlsx');
    }

    public function returnList()
    {
        $breadcrumb = $this->stock->breadcrumbs('returns');

        if (\request()->ajax()) {
            $products = $this->stock->returnGrid();
            return response()->json($products);
        }

        return view('stock.return.index', compact('breadcrumb'));
    }

    public function return(Store $store)
    {
        $breadcrumb = $this->stock->breadcrumbs('return-create');
        $stocks = Stock::where('store_id', $store->id)->get();
        $stocks = $stocks->reject(function (Stock $stock) {
            return $stock->available_stock <= 0;
        });
        return view('stock.return.create', compact('breadcrumb', 'store', 'stocks'));
    }

    /**
     * @param StockReturnStoreRequest $request
     * @param Store $store
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doReturn(StockReturnStoreRequest $request, Store $store)
    {
        $this->stock->returnStocks($store, $request);
        alert()->success('Stock returned successfully', 'Success')->persistent();
        return redirect()->route('stock.return.index');
    }

    public function reviewStock(Stock $stock)
    {
        $breadcrumb = $this->stock->breadcrumbs('review-stock');
        $store = Store::find($stock->store_id);
        $staff = $store->staff()->pluck('short_name', 'id');
        return view('stock.list.review.review', compact('breadcrumb', 'stock', 'staff'));
    }

    public function doReviewStock(ReviewStockRequest $request, Stock $stock)
    {
        $staffId = $request->input('staff_id');
        $availableQty = $request->input('available_stock');
        $actualQty = $request->input('actual_qty');

        /** create stock review records */
        $review = new StockReview();
        $review->setAttribute('date', carbon()->now()->toDateString());
        $review->setAttribute('notes', $request->input('notes'));
        $review->setAttribute('prepared_by', auth()->id());
        $review->setAttribute('prepared_on', carbon()->now()->toDateTimeString());
        $review->setAttribute('store_id', $stock->store->id);
        $review->setAttribute('staff_id', $staffId);
        $review->setAttribute('company_id', $stock->getAttribute('company_id'));
        $review->save();

        /** get stock row data which is going to update */
        $stockToReview = Stock::where('id', $stock->getAttribute('id'))->first();

        /** calculate excess & shortage qty */
        $excessQty = 0;
        $shortageQty = 0;
        if($availableQty > $actualQty) {
            $shortageQty = ($availableQty - $actualQty);
        }
        if($availableQty < $actualQty) {
            $excessQty = ($actualQty - $availableQty);
        }

        /** get rate & amount */
        $stockHistory = StockHistory::where('stock_id', $stockToReview->id)
            ->where('transaction', 'In')
            ->where('type', 'Purchase')
            ->orderBy('id', 'desc')
            ->first();
        if($stockHistory){
            $rate = $stockHistory->rate;
        }else{
            $product = Product::where('id', $stockToReview->product_id)->first();
            $rate = $product->buying_price;
        }

        /** create review items  */
        $reviewItem = new StockReviewItem();
        $reviewItem->setAttribute('date', carbon()->now()->toDateTimeString());
        $reviewItem->setAttribute('available_qty', $availableQty);
        $reviewItem->setAttribute('actual_qty', $actualQty);
        $reviewItem->setAttribute('excess_qty', $excessQty);
        $reviewItem->setAttribute('shortage_qty', $shortageQty);
        $reviewItem->setAttribute('rate', $rate);
        $reviewItem->setAttribute('amount', ($rate * $actualQty));
        $reviewItem->setAttribute('excess_amount', ($rate * $excessQty));
        $reviewItem->setAttribute('shortage_amount', ($rate * $shortageQty));
        $reviewItem->setAttribute('product_id', $stockToReview->product_id);
        $reviewItem->setAttribute('stock_id', $stockToReview->id);
        $reviewItem->setAttribute('stock_review_id', $review->getAttribute('id'));
        $reviewItem->save();

        alert()->success('Stock reviewed successfully', 'Success')->persistent();
        return redirect()->route('stock.show', $stock);
    }

}
