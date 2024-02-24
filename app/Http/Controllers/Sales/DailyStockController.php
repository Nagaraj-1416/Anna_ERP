<?php

namespace App\Http\Controllers\Sales;

use App\DailyStock;
use App\DailyStockItem;
use App\Http\Requests\Sales\DailyShopStockAllocationStoreRequest;
use App\Http\Requests\Sales\DailyStockAllocationStoreRequest;
use App\Http\Requests\Sales\DailyStockUpdateRequest;
use App\Jobs\AllocateDailyStocks;
use App\Rep;
use App\Repositories\Sales\DailyStockRepository;
use App\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class DailyStockController extends Controller
{
    protected $stock;

    /**
     * DailyStockController constructor.
     * @param DailyStockRepository $stock
     */
    public function __construct(DailyStockRepository $stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (\request()->ajax()) {
            $stocks = $this->stock->grid();
            return response()->json($stocks);
        }
        $breadcrumb = $this->stock->breadcrumbs('index');
        return view('sales.allocation.stock.index', compact('breadcrumb'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $breadcrumb = $this->stock->breadcrumbs('create');
        return view('sales.allocation.stock.create', compact('breadcrumb'));
    }

    /**
     * @param DailyStockAllocationStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DailyStockAllocationStoreRequest $request)
    {
        $this->stock->save($request);
        alert()->success('Stock allocated successfully', 'Success')->persistent();
        return redirect()->route('daily.stock.index');
    }

    /**
     * @param DailyStock $dailyStock
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(DailyStock $dailyStock)
    {
        $products = $dailyStock->items()->with('product')->get();
        $store = $dailyStock->store;
        $products->map(function ($product) use ($store){
            $stock = $store->stocks->where('product_id', $product->product_id)->first();
            if($stock){
                $product->available_qty_in_store = $stock->available_stock;
            }else{
                $product->available_qty_in_store = 0;
            }
            return $product;
        });
        $breadcrumb = $this->stock->breadcrumbs('show');
        return view('sales.allocation.stock.show', compact('breadcrumb', 'dailyStock', 'products'));
    }

    /**
     * @param DailyStock $dailyStock
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(DailyStock $dailyStock)
    {
        $breadcrumb = $this->stock->breadcrumbs('edit', $dailyStock);
        return view('sales.allocation.stock.edit', compact('breadcrumb', 'dailyStock'));
    }

    /**
     * @param DailyStockAllocationStoreRequest $request
     * @param DailyStock $dailyStock
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(DailyStockAllocationStoreRequest $request, DailyStock $dailyStock)
    {
        $dailyStock->items()->delete();
        $dailyStock->delete();
        $this->stock->save($request);
        alert()->success('Stock allocation updated successfully', 'Success')->persistent();
        return redirect()->route('daily.stock.index');
    }

    /**
     * @param DailyStock $dailyStock
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusUpdate(DailyStock $dailyStock, $status)
    {
        $data = $this->stock->statusUpdate($dailyStock, $status);
        return response()->json($data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createShop()
    {
        $breadcrumb = $this->stock->breadcrumbs('create');
        return view('sales.allocation.stock.shop.create', compact('breadcrumb'));
    }

    /**
     * @param DailyShopStockAllocationStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeShop(DailyShopStockAllocationStoreRequest $request)
    {
        $this->stock->saveShop($request);
        alert()->success('Stock allocated successfully', 'Success')->persistent();
        return redirect()->route('daily.stock.index');
    }

    /**
     * @param DailyStock $dailyStock
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editShop(DailyStock $dailyStock)
    {
        $breadcrumb = $this->stock->breadcrumbs('edit', $dailyStock);
        return view('sales.allocation.stock.shop.edit', compact('breadcrumb', 'dailyStock'));
    }

    /**
     * @param DailyShopStockAllocationStoreRequest $request
     * @param DailyStock $dailyStock
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function updateShop(DailyShopStockAllocationStoreRequest $request, DailyStock $dailyStock)
    {
        $dailyStock->items()->delete();
        $dailyStock->delete();
        $this->stock->saveShop($request);
        alert()->success('Stock allocation updated successfully', 'Success')->persistent();
        return redirect()->route('daily.stock.index');
    }

    /**
     * @param DailyStock $dailyStock
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDailyStockProduct(DailyStock $dailyStock)
    {
        $products = $dailyStock->items()->with('product')->get();
        $store = $dailyStock->store;
        $products->map(function ($product) use ($store){
            $stock = $store->stocks->where('product_id', $product->product_id)->first();
            $product->available_stock = $stock->available_stock;
            return $product;
        });

        return response()->json($products->toArray());
    }

    /**
     * @param Request $request
     * @param DailyStock $dailyStock
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDailyStockProducts(DailyStockUpdateRequest $request, DailyStock $dailyStock)
    {
        $items = $dailyStock->items;
        $stockItems = $request->input('stock_items');
        $issued_qty = array_get($stockItems, 'issued_qty');

        foreach ($items as $item) {
            $item->issued_qty = (int)array_get($issued_qty, $item->id);
            $pending = $item->default_qty - $item->issued_qty;
            if ($pending > 0) {
                $item->pending_qty = $pending;
            }
            $item->save();
        }

        $dailyStock->status = 'Allocated';
        $dailyStock->save();
        alert()->success('Daily stock items updated successfully', 'Success')->persistent();
        return redirect()->route('daily.stock.show', $dailyStock);
    }

    public function export(DailyStock $dailyStock)
    {
        $this->pdfExport($dailyStock);
    }

    public function pdfExport($dailyStock)
    {
        $company = $dailyStock->company;
        $route = $dailyStock->route;
        $rep = $dailyStock->rep;
        $store = $dailyStock->store;
        $salesVan = $dailyStock->saleLocation;
        $items = $dailyStock->items;

        $data = [];
        $data['items'] = $items;
        $data['company'] = $company;
        $data['route'] = $route;
        $data['rep'] = $rep;
        $data['store'] = $store;
        $data['salesVan'] = $salesVan;
        $data['dailyStock'] = $dailyStock;

        $pdf = PDF::loadView('sales.allocation.stock.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Stock Allocation (' . $route->name . ')' . '.pdf');
    }

    public function changeRoute(DailyStock $dailyStock)
    {
        $breadcrumb = $this->stock->breadcrumbs('change-route');
        $routes = Route::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('id', '!=', $dailyStock->route_id)
            ->get()->pluck('name', 'id')->toArray();
        return view('sales.allocation.stock.change-route',
            compact('breadcrumb', 'dailyStock', 'routes'));
    }

    public function doChangeRoute(DailyStock $dailyStock)
    {
        $request = request();
        $request->validate(['route' => 'required']);
        $routeId = $request->input('route');
        $dailyStock->setAttribute('route_id', $routeId);
        $dailyStock->save();
        alert()->success('Route updated successfully', 'Success')->persistent();
        return redirect()->route('daily.stock.show', $dailyStock);
    }

    public function changeRep(DailyStock $dailyStock)
    {
        $breadcrumb = $this->stock->breadcrumbs('change-rep');
        $reps = Rep::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('id', '!=', $dailyStock->rep_id)
            ->get()->pluck('name', 'id')->toArray();
        return view('sales.allocation.stock.change-rep',
            compact('breadcrumb', 'dailyStock', 'reps'));
    }

    public function doChangeRep(DailyStock $dailyStock)
    {
        $request = request();
        $request->validate(['rep' => 'required']);
        $repId = $request->input('rep');
        $dailyStock->setAttribute('rep_id', $repId);
        $dailyStock->save();
        alert()->success('Rep updated successfully', 'Success')->persistent();
        return redirect()->route('daily.stock.show', $dailyStock);
    }

}
