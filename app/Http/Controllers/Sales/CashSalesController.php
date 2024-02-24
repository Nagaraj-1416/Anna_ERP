<?php

namespace App\Http\Controllers\Sales;

use App\DailySale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\CashSalesCustomerStoreRequest;
use App\Http\Requests\Sales\CashSalesRequest;
use App\Product;
use App\Repositories\Sales\CashSalesRepository;
use App\Repositories\Sales\CustomerRepository;
use App\SalesOrder;

class CashSalesController extends Controller
{
    /**
     * @var CashSalesRepository
     */
    protected $order;
    protected $customer;

    /**
     * CashSalesController constructor.
     * @param CashSalesRepository $order
     * @param CustomerRepository $customer
     */
    public function __construct(CashSalesRepository $order, CustomerRepository $customer)
    {
        $this->order = $order;
        $this->customer = $customer;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumb = $this->order->breadcrumbs('index');
        $allocation = $this->order->getAllocationData();
        if (\request()->ajax()) {
            $cashSales = $this->order->grid();
            return response()->json($cashSales);
        }
        $orders = SalesOrder::where('order_mode', 'Cash')->get();
        return view('sales.cash.index', compact('breadcrumb', 'orders', 'allocation'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $breadcrumb = $this->order->breadcrumbs('create');
        return view('sales.cash.create', compact('breadcrumb'));
    }

    /**
     * @param CashSalesRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CashSalesRequest $request)
    {
        $this->order->save($request);
        alert()->success('Sales created successfully', 'Success');
        return redirect()->route('cash.sales.index');
    }

    public function storeCustomer(CashSalesCustomerStoreRequest $request)
    {
        $customer = $this->customer->saveCashSalesCustomer($request);
        if ($request->ajax()) {
            return response()->json($customer->toArray());
        }
    }

    /**
     * @param string $ids
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchSalesProduct($ids = '', $q = null)
    {
        if (!json_decode($ids) && !$q && !is_array(json_decode($ids))) {
            $q = $ids;
        }
        $response = $this->order->searchProducts($ids, $q);
        return response()->json($response);
    }

    /**
     * @param SalesOrder $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(SalesOrder $order)
    {
        $this->order->cancel($order);
        return response()->json(['success' => true]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function handOverData()
    {
        $data = $this->order->getHandOverData();
        return response()->json($data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function handOverSave()
    {
        $data = $this->order->saveHandOverData();
        return response()->json($data);
    }

    /**
     * @param DailySale $allocation
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function allocationProduct(DailySale $allocation, Product $product)
    {
        $item = $allocation->items()->where('product_id', $product->id)->first();
        $product->av_qty = ($item->quantity + $item->cf_qty) - ($item->sold_qty + $item->restored_qty + $item->replaced_qty);

        $product->name = $product->name . ' (Av QTY: ' . $product->av_qty . ')';
        $product->selling_price = getItemShopSellingPrice($allocation->company, $allocation->salesLocation, $product);

        return response()->json($product);
    }

    public function productForBarcode()
    {
        if (request()->ajax()){
            return response()->json($this->order->productForBarcode());
        }
    }
}
