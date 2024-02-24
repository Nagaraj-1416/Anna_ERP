<?php

namespace App\Http\Controllers\Sales;

use App\Customer;
use App\DailySale;
use App\DailySaleCreditOrder;
use App\DailySaleCustomer;
use App\DailySaleItem;
use App\DailyStock;
use App\DailyStockItem;
use App\Exports\SalesSheetExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\AllocationAddExpenseRequest;
use App\Http\Requests\Sales\AllocationAddProductRequest;
use App\Http\Requests\Sales\AllocationRequest;
use App\Http\Requests\Sales\RestoreStockRequest;
use App\InvoicePayment;
use App\Jobs\CreateHandoverJob;
use App\Jobs\StockUpdateJob;
use App\Product;
use App\Rep;
use App\Repositories\Sales\AllocationRepository;
use App\Repositories\Sales\SalesExpenseRepository;
use App\Route;
use App\SalesExpense;
use App\SalesLocation;
use App\SalesOrder;
use App\Staff;
use App\Stock;
use App\StockHistory;
use App\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jeylabs\AuditLog\Models\AuditLog;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class AllocationController extends Controller
{
    /**
     * @var AllocationRepository
     */
    protected $allocation;
    protected $salesExpense;

    /**
     * AllocationController constructor.
     * @param AllocationRepository $allocation
     * @param SalesExpenseRepository $salesExpense
     */
    public function __construct(AllocationRepository $allocation, SalesExpenseRepository $salesExpense)
    {
        $this->allocation = $allocation;
        $this->salesExpense = $salesExpense;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->allocation->getModel());
        $breadcrumb = $this->allocation->breadcrumbs('index');
        if (\request()->ajax()) {
            $allocations = $this->allocation->grid();
            return response()->json($allocations);
        }
        return view('sales.allocation.index', compact('breadcrumb'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->allocation->getModel());
        $breadcrumb = $this->allocation->breadcrumbs('create');
        $startTime = carbon()->now();
        return view('sales.allocation.create', compact('breadcrumb', 'startTime'));
    }

    /**
     * @param AllocationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(AllocationRequest $request)
    {
        $this->authorize('store', $this->allocation->getModel());
        $allocation = $this->allocation->save($request);
        auditLog()
            ->performedOn(new DailySale())
            ->withProperties(['attributes' => $request->toArray()])
            ->log('Allocation created - (Request data)');
        alert()->success('Allocation created successfully', 'Success')->persistent();
        return redirect()->route('sales.allocation.show', [$allocation]);
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(DailySale $allocation)
    {
        $this->authorize('show', $this->allocation->getModel());
        $breadcrumb = $this->allocation->breadcrumbs('show', $allocation);
        $handover = $allocation->salesHandover;
        $cheques = collect();
        if ($handover) {
            $shortage = $handover->chequeInHands()->whereNotNull('shortage')->get()->sum('amount');
            $handover->cheque_total = ($handover->chequeInHands()->get()->sum('amount')) - $shortage;
            $cheques = $handover->chequeInHands ?? collect();
        }
        if (!$cheques->count()) {
            $cheques = $allocation->payments()->where('payment_mode', 'Cheque')
                ->where('status', 'Paid')->get();

            $cheques = $cheques->transform(function ($cheque) {
                $cheque->amount = $cheque->payment;
                return $cheque;
            });
            if ($handover) {
                $handover->cheque_total = $cheques->sum('amount');
            }
        }

        /** get direct deposit payments */
        $deposits = $allocation->payments()->where('payment_mode', 'Direct Deposit')->get();
        if ($handover) {
            $handover->deposit_total = $deposits->sum('payment');
        }

        $returns = $allocation->returns()->with('resolutions', 'items')->get() ?? collect();
        $refundedAmount = $returns->pluck('resolutions')->collapse()->where('resolution', 'Refund')->sum('amount');
        $expenses = collect();
        if ($handover) {
            $expenses = $handover->salesExpenses()->with('expense')->get() ?? collect();
            $expensesNew = $expenses->pluck('expense');
            $handover->total_expense = $expenses->sum('amount');
            $handover->total_new_expense = $expensesNew->sum('amount');
        }

        /** get non-routed customers */
        /*$nonRoutedCustomers = $allocation->customers;
        $nonRoutedCustomers = $nonRoutedCustomers->reject(function ($customer) use ($allocation) {
            $route = $allocation->route_id;
            return $customer->customer->route_id == $route;
        });*/

        $customers = $allocation->customers()->with('customer')->get();
        $customers = $customers->map(function (DailySaleCustomer $customer) use ($allocation) {

            $order = SalesOrder::where('daily_sale_id', $allocation->id)
                ->where('customer_id', $customer->customer_id)->with('payments')->first();

            if($order){
                $received = $order->payments('status', 'Paid')->sum('payment');
                $balance = ($order->total - $received);

                $customer->sales = number_format($order->total);
                $customer->received = number_format($received);
                $customer->balance = number_format($balance);
            }else{
                $customer->sales = 0;
                $customer->received = 0;
                $customer->balance = 0;
            }
            return $customer;
        });

        return view('sales.allocation.show', compact('breadcrumb', 'allocation', 'handover',
            'cheques', 'expenses', 'refundedAmount', 'customers', 'returns'));
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(DailySale $allocation)
    {
        $this->authorize('edit', $this->allocation->getModel());
        if (!($allocation->status == 'Active' || $allocation->status == 'Draft')) {
            alert()->warning('This sales allocation is in-progress, can\'t edit it!', 'Warning')->persistent();
            return redirect()->route('sales.allocation.show', [$allocation]);
        }
        $products = [];
        $items = $allocation->items;
        $products['id'] = $items->pluck('id', 'product_id')->toArray();
        $products['quantity'] = $items->pluck('quantity', 'product_id')->toArray();
        $products['store'] = $items->pluck('store_id', 'product_id')->toArray();
        $storeName = [];
        $storeId = [];
        foreach ($products['store'] as $key => $store) {
            $store = Store::find($store);
            $storeName[$key] = $store->name ?? '';
            $storeId[$key] = $store->id ?? '';
        }
        $customer = [];
        $customer['id'] = $allocation->customers->pluck('id', 'customer_id');
        $allocation->load('driver');
        $allocation->product = $products;
        $allocation->store_id = $storeId;
        $allocation->store_name = $storeName;
        $allocation->customer = $customer;
        $allocation->odo_meter_reading = $allocation->odoMeterReading->starts_at ?? '';
        $allocation->location_name = $allocation->salesLocation->name ?? '';
        $allocation->rep_name = $allocation->rep->name ?? '';
        $allocation->route_name = $allocation->route->name ?? '';

        if ($allocation->labour_id) {
            $laboursId = explode(',', $allocation->labour_id);
            $allocation->labours = Staff::whereIn('id', $laboursId)->select(['short_name', 'id'])->get();
        }

        $breadcrumb = $this->allocation->breadcrumbs('edit', $allocation);
        return view('sales.allocation.edit', compact('allocation', 'breadcrumb'));
    }

    /**
     * @param DailySale $allocation
     * @param AllocationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(DailySale $allocation, AllocationRequest $request)
    {
        $this->authorize('update', $this->allocation->getModel());
        $this->allocation->update($allocation, $request);
        alert()->success('Allocation updated successfully', 'Success')->persistent();
        return redirect()->route('sales.allocation.show', [$allocation]);
    }

    /**
     * @param Route $route
     * @return JsonResponse
     */
    public function getCustomer(Route $route)
    {
        return response()->json($route->customers->toArray());
    }

    /**
     * @param Route $route
     * @return JsonResponse
     */
    public function getProducts(Route $route)
    {
        /*$products = $route->products()->withPivot('default_qty')->whereIn('type', ['Finished Good', 'Third Party Product'])->get();
        $routeProducts = $products->pluck('id')->toArray();
        $cfProduct = $this->getLastAllocationProducts(true);
        $cfProductId = array_keys($cfProduct);
        $productsData = Product::whereIn('id', $cfProductId)->whereNotIn('id', $routeProducts)->get();
        $productsData = $productsData->transform(function (Product $product) use ($cfProduct) {
            $default_qty = array_get($cfProduct, $product->id);
            if ($default_qty) {
                $product->default_qty = array_get($cfProduct, $product->id);
                return $product;
            }
        });
        $products = $products->merge($productsData);
        return response()->json($products->toArray());*/

        $dailyStock = DailyStock::where('route_id', $route->id)->where('status', 'Allocated')->with('items')->orderBy('id', 'desc')->first();

        $products = $dailyStock->items;

        $products = $products->map(function (DailyStockItem $item) {
            $item->product_name = $item->product->name;
            return $item;
        });

        $products = $products->reject(function ($product) {
            return $product->issued_qty == 0 && $product->available_qty == 0;
        });

        //$products = $products->merge($productsData);
        return response()->json($products->toArray());
    }

    /**
     * @param SalesLocation $location
     * @return JsonResponse
     */
    public function getSalesLocationProducts(SalesLocation $location)
    {
        return response()->json($location->products()->withPivot('default_qty')->whereIn('type', ['Finished Good', 'Third Party Product'])->get()->toArray());
    }

    /**
     * @param bool $return
     * @return array
     */
    public function getLastAllocationProducts($return = false)
    {
        $request = \request();
        $data = $this->allocation->getOldAllocationData($request);
        if ($return) return $data;
        return response()->json($data);
    }

    /**
     * @param DailySale $allocation
     * @param $status
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function statusChange(DailySale $allocation, $status)
    {
        $this->authorize('statusChange', $allocation);
        $data = $this->allocation->statusChange($allocation, $status);
        return response()->json($data);
    }

    /**
     * @param DailySale $allocation
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(DailySale $allocation)
    {
        $this->authorize('export', $allocation);
        $data = [];
        $data['handover'] = $allocation->salesHandover;
        $data['customers'] = $allocation->customers;
        $data['products'] = $allocation->items;
        $data['allocation'] = $allocation;
        $pdf = PDF::loadView('sales.allocation.export', $data);
        return $pdf->download($allocation->code . '.pdf');
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function print(DailySale $allocation)
    {
        $this->authorize('print', $allocation);
        $handover = $allocation->salesHandover;
        $customers = $allocation->customers;
        $products = $allocation->items;
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Sales', 'route' => 'sales.index'],
            ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
            ['text' => 'Print Allocation']
        ];
        return view('sales.allocation.print', compact('allocation', 'handover', 'customers', 'products', 'breadcrumb'));
    }

    /**
     * @param DailySale $allocation
     * @param null $q
     * @return JsonResponse
     */
    public function allocationCustomer(DailySale $allocation, $q = null)
    {
        $companyId = $allocation->company_id;
        if ($q == null) {
            $customers = Customer::where('company_id', $companyId)->get(['id', 'display_name', 'code'])->toArray();
        } else {
            $customers = Customer::where('company_id', $companyId)->where('display_name', 'LIKE', '%' . $q . '%')->get(['id', 'display_name', 'code'])->toArray();
        }
        $customers = array_map(function ($obj) {
            return ["name" => $obj['display_name'] . ' (' . $obj['code'] . ')', "value" => $obj['id']];
        }, $customers);
        return response()->json(["success" => true, "results" => $customers]);
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allocationAddCustomer(DailySale $allocation)
    {
        $breadcrumb = $this->allocation->breadcrumbs('add-customers', $allocation);
        $allocatedCusIds = $allocation->customers()->pluck('customer_id')->toArray();
        $customers = Customer::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereNotIn('id', $allocatedCusIds)
            ->get()->pluck('display_name', 'id')->toArray();
        return view('sales.allocation.add-customers', compact('allocation', 'breadcrumb', 'customers'));
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function allocationStoreCustomer(DailySale $allocation)
    {
        $request = request();
        $request->validate(['customers' => 'required']);
        $customers = $request->input('customers');
        $ids = explode(',', $customers);
        $ids = array_combine($ids, $ids);
        $request->merge(['customer' => ['id' => $ids]]);
        $this->allocation->setModel($allocation);
        $this->allocation->saveCustomers($request);
        alert()->success('Customers added successfully', 'Success')->persistent();
        return redirect()->route('sales.allocation.show', [$allocation]);
    }

    /**
     * @param DailySale $allocation
     * @param null $q
     * @return JsonResponse
     */
    public function allocationProducts(DailySale $allocation, $q = null)
    {
//        $productsId = $allocation->items->pluck('product_id')->toArray();
        $productsId = [];
        $route = $allocation->route;
        $salesLocation = $allocation->salesLocation;
        $searchModal = Product::whereIn('type', ['Finished Good', 'Third Party Product']);
//        if ($route) {
//            $searchModal = $route->products()->whereIn('type', ['Finished Good', 'Third Party Product']);
//        } else {
//            $searchModal = $salesLocation->products()->whereIn('type', ['Finished Good', 'Third Party Product']);
//        }
        if (!$q) {
            $data = $searchModal->whereNotIn('id', $productsId)->get(['id', 'name'])->toArray();
        } else {
            $data = $searchModal->whereNotIn('id', $productsId)
                ->where(function ($query) use ($q) {
                    $query->where('name', 'LIKE', '%' . $q . '%');
                })
                ->get(['id', 'name'])
                ->toArray();
        }
        // mapping the data
        $data = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $data);
        return response()->json(["success" => true, "results" => $data]);
    }

    /**
     * @param DailySale $allocation
     * @param $product
     * @return JsonResponse
     */
    public function getProduct(DailySale $allocation, $product)
    {
        $route = $allocation->route;
        $salesLocation = $allocation->salesLocation;
        $productId = $product;
        if ($route) {
            $product = $route->products()->where('id', $product)->withPivot('default_qty')
                ->whereIn('type', ['Finished Good', 'Third Party Product'])->get()->first();
        } else {
            $product = $salesLocation->products()->where('id', $product)->withPivot('default_qty')
                ->whereIn('type', ['Finished Good', 'Third Party Product'])->get()->first();
        }
        if (!$product) {
            $product = Product::find($productId);
        }
        if ($product) {
            $product = $product->toArray();
        } else {
            $product = [];
        }
        return response()->json($product);
    }

    public function allocateProducts(DailySale $allocation)
    {
        $stocks = Stock::where('category', 'Main')
            ->where('company_id', $allocation->company_id)->get();
        $stocks = $stocks->reject(function (Stock $stock) {
            return $stock->available_stock <= 0;
        });
        $breadcrumb = $this->allocation->breadcrumbs('allocate-products', $allocation);
        return view('sales.allocation.allocate-products', compact('allocation', 'breadcrumb', 'stocks'));
    }

    /**
     * @param DailySale $allocation
     * @param AllocationAddProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProducts(DailySale $allocation, AllocationAddProductRequest $request)
    {
        $products = $request->input('allocates');

        $stockIds = array_get($products, 'stock_id');
        $productIds = array_get($products, 'product_id');
        $storeIds = array_get($products, 'store_id');
        $issueQuantities = array_get($products, 'issue_qty');

        $ids = [];
        $addValues = [];
        foreach ($stockIds as $id => $value) {
            $oldProduct = DailySaleItem::where('daily_sale_id', $allocation->id)->where('product_id', (int)array_get($productIds, $id))->first();
            if ($oldProduct) {
                $product = $oldProduct;
                $product->quantity = $oldProduct->quantity + (int)array_get($issueQuantities, $id);
            } else {
                $product = new DailySaleItem();
                $product->quantity = (int)array_get($issueQuantities, $id);
            }
            $product->daily_sale_id = $allocation->id;
            $product->product_id = (int)array_get($productIds, $id);
            $product->store_id = (int)array_get($storeIds, $id);
            if ($allocation->status == 'Progress') {
                $product->added_stage = 'Later';
            }
            $product->save();
            $addValues[$product->id] = (int)array_get($issueQuantities, $id);
            array_push($ids, $product->id);
        }
        if ($allocation->status == 'Progress') {
            $this->allocation->stockUpdateJob('Out', $ids, $addValues, $allocation);
        }

        alert()->success('Products added successfully', 'Success')->persistent();
        return redirect()->route('sales.allocation.show', [$allocation]);
    }

    /**
     * @param SalesLocation $salesLocation
     * @return JsonResponse
     */
    public function getVehicle(SalesLocation $salesLocation)
    {
        $reading = [];
        $vehicle = $salesLocation->vehicle;
        if ($vehicle) {
            $reading = $vehicle->odoMeterReadings()->orderBy('id', 'desc')->first();
            if ($reading) {
                $reading = $reading->toArray();
            } else {
                $reading = [];
            }
        }
        return response()->json($reading);
    }

    /**
     * @param DailySale $allocation
     * @return JsonResponse
     */
    public function allocationGetCustomer(DailySale $allocation)
    {
        $search = request()->input('search');
        $customers = $allocation->customers()->orderBy('id', 'desc')->with(['customer', 'dailySale.rep'])->whereHas('customer', function ($customer) use ($search) {
            $customer->where('code', 'LIKE', '%' . $search . '%')
                ->orWhere('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('full_name', 'LIKE', '%' . $search . '%')
                ->orWhere('display_name', 'LIKE', '%' . $search . '%')
                ->orWhere('phone', 'LIKE', '%' . $search . '%')
                ->orWhere('fax', 'LIKE', '%' . $search . '%')
                ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%');
        })->get();
        $customers->transform(function ($customer) use ($allocation) {
            $route = $allocation->route_id;
            $customer->route_customer = 'Yes';
            if ($route != $customer->customer->route_id) {
                $customer->route_customer = 'No';
            }
            $customer->visitedAt = date("F j, Y, g:i a", strtotime($customer->updated_at));
            $customer->createdAt = date("F j, Y, g:i a", strtotime($customer->created_at));
            return $customer;
        });
        return response()->json($customers->toArray());
    }

    /**
     * @param DailySale $allocation
     * @return JsonResponse
     */
    public function allocationGetProducts(DailySale $allocation)
    {
        $search = request()->input('search');
        $products = $allocation->items()->orderBy('id', 'desc')->with(['product', 'store'])->where(function ($q) use ($search) {
            $q->whereHas('product', function ($product) use ($search) {
                $product->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('type', 'LIKE', '%' . $search . '%');
            })->orWhereHas('store', function ($store) use ($search) {
                $store->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%');
            });
        })->get();

        $products->transform(function ($product) use ($allocation) {
            $route = $allocation->route_id;
            $product->route_product = 'Yes';
            if ($route) {
                if (!$product->product->routes->where('id', $route)->count()) {
                    $product->route_product = 'No';
                }
            }
            $product->createdAt = date("F j, Y, g:i a", strtotime($product->created_at));

            /** get stock in & out history */
            $histories = StockHistory::where('transable_id', $allocation->id)
                ->where('transable_type', 'App\DailySale')->where(function ($q) use ($product){
                    $q->whereHas('stock', function ($history)  use ($product) {
                        $history->where('product_id', $product->product_id);
                    });
                })->get();

            $product->histories = $histories;

            return $product;
        });
        return response()->json($products->toArray());
    }

    /**
     * @param Rep $rep
     * @param Route $route
     * @return JsonResponse
     */
    public function getOldCustomers(Rep $rep, Route $route)
    {
        $dailySale = DailySale::where('rep_id', $rep->id)->where('route_id', $route->id)->orderBy('created_at', 'desc')->first();
        $customers = [];
        if ($dailySale) {
            $customers = $dailySale->customers()->where('is_visited', 'No')->get()->pluck('customer_id', 'customer_id')->toArray();
        }
        return response()->json($customers);
    }

    /**
     * @param DailySale $allocation
     * @return JsonResponse
     */
    public function getCreditOrder(DailySale $allocation)
    {
        $orders = $this->allocation->creditOrders($allocation);
        if (!$orders->count()) {
            alert()->warning('There are no credit orders available to attach!', 'Warning')->persistent();
            return redirect()->route('sales.allocation.show', [$allocation]);
        }
        $orders = $orders->toArray();
        $breadcrumb = $this->allocation->breadcrumbs('attach', $allocation);
        return view('sales.allocation.credit.order', compact('allocation', 'orders', 'breadcrumb'));
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attachCreditBill(DailySale $allocation)
    {
        $orders = request()->input('orders');
        foreach ($orders as $key => $order) {
            $order = SalesOrder::find($key);
            if ($order) {
                $credit = new DailySaleCreditOrder();
                $credit->daily_sale_id = $allocation->id;
                $credit->customer_id = $order->customer_id;
                $credit->sales_order_id = $order->id;
                if ($allocation->status == 'Progress') {
                    $credit->added_stage = 'Later';
                }
                $credit->save();
            }
        }
        alert()->success('Credit Orders attached successfully', 'Success')->persistent();
        return redirect()->route('sales.allocation.show', [$allocation]);
    }

    /**
     * @param DailySale $allocation
     * @return JsonResponse
     */
    public function getAllocationCreditOrder(DailySale $allocation)
    {
        $data = [];
        $data['total'] = [];
        $orders = $allocation->dailySaleCreditOrders()->with(['customer', 'order', 'allocation'])->get();

        $orders = $orders->map(function ($item) {
            $item->pay_paid = soOutstandingById($item->sales_order_id)['paid'];
            $item->pay_balance = soOutstandingById($item->sales_order_id)['balance'];;
            return $item;
        });

        $data['total']['orderTotal'] = $orders->pluck('order')->sum('total');
        $data['total']['paymentTotal'] = $orders->sum('pay_paid');
        $data['total']['balance'] = $orders->sum('pay_balance');
        $data['orders'] = $orders->toArray();
        return response()->json($data);
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function complete(DailySale $allocation)
    {
        $breadcrumb = $this->allocation->breadcrumbs('complete', $allocation);
        return view('sales.allocation.complete', compact('allocation', 'breadcrumb'));
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doComplete(DailySale $allocation)
    {
        /** get ODO reading */
        $reading = $allocation->odoMeterReading;

        $request = request();
        $this->validate($request, [
            'allowance' => 'required',
            'end_odo_reading' => 'required|numeric|min:' . $reading->starts_at
        ]);

        /** update end ODO meter reading */
        if($reading){
            $reading->setAttribute('ends_at', $request->input('end_odo_reading'));
            $reading->save();
        }

        dispatch(new CreateHandoverJob($allocation));

        return redirect()->route('sales.allocation.show', [$allocation]);
    }

    public function changeActors(DailySale $allocation)
    {
        $breadcrumb = $this->allocation->breadcrumbs('change', $allocation);
        return view('sales.allocation.change', compact('allocation', 'breadcrumb'));
    }

    public function updateActors(DailySale $allocation)
    {
        $request = request();
        $this->validate($request, [
            'rep_id' => 'required',
            'driver_id' => 'required',
            'labour_id' => 'required'
        ]);

        /** update input values */
        $allocation->setAttribute('rep_id', $request->input('rep_id'));
        $allocation->setAttribute('driver_id', $request->input('driver_id'));
        $allocation->setAttribute('labour_id', $request->input('labour_id'));
        $allocation->save();

        alert()->success('Allocation details updated successfully', 'Success')->persistent();
        return redirect()->route('sales.allocation.show', [$allocation]);
    }

    /**
     * @param DailySale $allocation
     * @return JsonResponse
     */
    public function getPhoneOrders(DailySale $allocation)
    {
        $orders = $this->allocation->phoneOrders($allocation);

        if (!$orders->count()) {
            alert()->warning('There are no phone orders available to attach!', 'Warning')->persistent();
            return redirect()->route('sales.allocation.show', [$allocation]);
        }
        $orders = $orders->toArray();
        $breadcrumb = $this->allocation->breadcrumbs('attach-phone-order', $allocation);
        return view('sales.allocation.phone.order', compact('allocation', 'orders', 'breadcrumb'));
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attachPhoneOrders(DailySale $allocation)
    {
        $orders = request()->input('orders');
        foreach ($orders as $key => $order) {
            $order = SalesOrder::find($key);
            if ($order) {
                $order->setAttribute('daily_sale_id', $allocation->getAttribute('id'));
                $order->setAttribute('status', 'Open');
                $order->save();
            }
        }
        alert()->success('Phone orders attached successfully', 'Success')->persistent();
        return redirect()->route('sales.allocation.show', [$allocation]);
    }

    public function salesSheet(DailySale $allocation)
    {
        $breadcrumb = $this->allocation->breadcrumbs('sales-sheet', $allocation);

        $products = $allocation->items;
        $orders = $allocation->orders->whereIn('status', ['Open', 'Closed']);
        $orders = $orders->map(function (SalesOrder $order) {
            $order->createdAt = date("F j, Y, g:i a", strtotime($order->created_at));
            $order->distance = round($order->distance, 2) . 'KM' ?? '0KM';
            if ($order->gps_lat && $order->gps_long) {
                $order->distance_show_route = route('map.index', [
                    'startLat' => $order->customer->gps_lat,
                    'startLng' => $order->customer->gps_long,
                    'startInfo' => json_encode(['heading' => $order->customer->display_name, 'code' => $order->customer->tamil_name]),
                    'endLat' => $order->gps_lat,
                    'endLng' => $order->gps_long,
                    'endInfo' => json_encode(['heading' => $order->ref, 'date' => date("F j, Y, g:i a", strtotime($order->created_at)), 'rep' => $order->salesRep->name ?? ''])
                ]);
            }
            return $order;
        })->sortBy('ref');

        $total = $orders->sum('total');
        $orderIds = $orders->pluck('id');
        $payments = InvoicePayment::whereIn('sales_order_id', $orderIds)
            ->where('daily_sale_id', $allocation->id)->get();

        $received = $payments->where('status', 'Paid')->sum('payment');
        $cashReceived = $payments->where('payment_mode', 'Cash')->where('status', 'Paid')->sum('payment');
        $chequeReceived = $payments->where('payment_mode', 'Cheque')->where('status', 'Paid')->sum('payment');
        $depositReceived = $payments->where('payment_mode', 'Direct Deposit')->where('status', 'Paid')->sum('payment');
        $cardReceived = $payments->where('payment_mode', 'Credit Card')->where('status', 'Paid')->sum('payment');
        $customerCredit = $payments->where('payment_mode', 'Customer Credit')->where('status', 'Paid')->sum('payment');

        $balance = ($total - $received);

        /** get today collection details */
        $oldSales = $this->oldCollection($allocation);
        $oldSales = $oldSales->map(function (InvoicePayment $oldSale) {
            $customer = $oldSale->customer;
            $repName = $oldSale->order->salesRep->name ?? 'N/A';
            $oldSale->orderCreatedAt = date("F j, Y, g:i a", strtotime($oldSale->order->created_at));
            $oldSale->paymentCreatedAt = date("F j, Y, g:i a", strtotime($oldSale->created_at));
            $oldSale->distance_show_route = "#";
            $oldSale->distance = 0;
            if ($oldSale->gps_lat && $oldSale->gps_long) {
                $oldSale->distance_show_route = route('map.index', [
                    'startLat' => $customer->gps_lat,
                    'startLng' => $customer->gps_long,
                    'startInfo' => json_encode(['heading' => $customer->display_name, 'code' => $customer->tamil_name]),
                    'endLat' => $oldSale->gps_lat,
                    'endLng' => $oldSale->gps_long,
                    'endInfo' => json_encode(['heading' => number_format($oldSale->payment, 2), 'date' => date("F j, Y, g:i a", strtotime($oldSale->created_at)), 'rep' => $repName])
                ]);
                $oldSale->distance = round(distance($customer->gps_lat, $customer->gps_long, $oldSale->gps_lat, $oldSale->gps_long), 2);
            }
            return $oldSale;
        });

        $oldReceived = $oldSales->sum('payment');

        $oldCashReceived = $oldSales->where('payment_mode', 'Cash')->where('status', 'Paid')->sum('payment');
        $oldChequeReceived = $oldSales->where('payment_mode', 'Cheque')->where('status', 'Paid')->sum('payment');
        $oldDepositReceived = $oldSales->where('payment_mode', 'Direct Deposit')->where('status', 'Paid')->sum('payment');
        $oldCardReceived = $oldSales->where('payment_mode', 'Credit Card')->where('status', 'Paid')->sum('payment');
        $oldCustomerCredit = $oldSales->where('payment_mode', 'Customer Credit')->where('status', 'Paid')->sum('payment');

        $reasons = $allocation->customers()->pluck('reason')->filter()->unique()->toArray();

        $returns = $allocation->returns()->with('resolutions', 'items')->get() ?? collect();

        /** get credit orders from this allocation */
        $creditOrders = SalesOrder::where('daily_sale_id', $allocation->getAttribute('id'))
            ->where('order_date', '>=', $allocation->getAttribute('from_date'))
            ->where('order_date', '<=', $allocation->getAttribute('to_date'))
            ->get();
        $creditOrders = $creditOrders->reject(function ($order) {
            $amount = $order->total;
            $payments = $order->payments;
            $others = $payments->where('status', 'Paid')->whereNotIn('payment_mode', ['Cheque'])->sum('payment');
            $cheques = $payments->where('status', 'Paid')->where('payment_mode', 'Cheque')->sum('payment');
            $order->balance = $order->total - ($others + $cheques);
            $order->paid = $others + $cheques;
            if ($amount == ($others + $cheques)) {
                return true;
            }
            return false;
        });

        $expenses = $allocation->salesExpenses;

        return view('sales.allocation.sheet', compact(
            'allocation', 'breadcrumb', 'products', 'orders', 'total', 'received', 'balance',
            'cashReceived', 'chequeReceived', 'depositReceived', 'cardReceived', 'customerCredit', 'oldSales',
            'oldReceived', 'oldCashReceived', 'oldChequeReceived', 'oldDepositReceived', 'oldCardReceived',
            'oldCustomerCredit', 'reasons', 'returns', 'creditOrders', 'expenses'
        ));
    }

    public function exportSalesSheet(DailySale $allocation)
    {
        $data = [];
        $data['alCode'] = $allocation->getAttribute('code');
        $data['alFromDate'] = $allocation->from_date;
        $data['alToDate'] = $allocation->to_date;
        return $this->exportSheetToExcel($allocation, $data);
    }

    public function exportSheetToExcel(DailySale $allocation, $data)
    {
        return Excel::download(new SalesSheetExport($allocation, $data), 'Sales Sheet ('.$allocation->route->name.')'.'('. $allocation->getAttribute('code') . ')' . '.xlsx', 'Xlsx');
    }

    public function exportCreditOrders(DailySale $allocation)
    {
        $creditOrders = SalesOrder::where('daily_sale_id', $allocation->getAttribute('id'))
            ->where('order_date', '>=', $allocation->getAttribute('from_date'))
            ->where('order_date', '<=', $allocation->getAttribute('to_date'))
            ->get();
        $creditOrders = $creditOrders->reject(function ($order) {
            $amount = $order->total;
            $payments = $order->payments;
            $others = $payments->where('status', 'Paid')->whereNotIn('payment_mode', ['Cheque'])->sum('payment');
            $cheques = $payments->where('status', 'Paid')->where('payment_mode', 'Cheque')->sum('payment');
            $order->balance = $order->total - ($others + $cheques);
            $order->paid = $others + $cheques;
            if ($amount == ($others + $cheques)) {
                return true;
            }
            return false;
        });

        $company = $allocation->company;
        $route = $allocation->route;
        $rep = $allocation->rep;

        $data = [];
        $data['creditOrders'] = $creditOrders;
        $data['company'] = $company;
        $data['route'] = $route;
        $data['rep'] = $rep;
        $data['allocation'] = $allocation;
        $data['totalSales'] = $creditOrders->sum('total');
        $data['totalPaid'] = $creditOrders->sum('paid');
        $data['totalBalance'] = $creditOrders->sum('balance');

        $pdf = PDF::loadView('sales.allocation.export-credit-orders', $data);
        return $pdf->download(env('APP_NAME') . ' - Credit Orders (' . $route->name . ')' . '.pdf');
    }


    public function oldCollection(DailySale $allocation)
    {
        $payments = InvoicePayment::where('status', 'Paid')->where('daily_sale_id', $allocation->id)
            ->whereBetween('payment_date', [$allocation->from_date, $allocation->to_date])
            ->whereHas('order', function ($q) use ($allocation) {
                $q->whereDate('order_date', '<', $allocation->from_date);
            })
            ->with(['order', 'customer'])->get();

        return $payments;
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addExpense(DailySale $allocation)
    {
        $breadcrumb = $this->allocation->breadcrumbs('add-expense', $allocation);
        return view('sales.allocation.expense', compact('allocation', 'breadcrumb'));
    }

    /**
     * @param AllocationAddExpenseRequest $request
     * @param DailySale $allocation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeExpense(AllocationAddExpenseRequest $request, DailySale $allocation)
    {
        $this->salesExpense->store($request, $allocation);
        alert()->success('Sales expense added successfully', 'Success')->persistent();
        return redirect()->route('sales.allocation.show', $allocation);
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSoldQty(DailySale $allocation)
    {
        $items = $allocation->items;
        $items = $items->map(function (DailySaleItem $item) use ($allocation){
            $item->sold_pro_qty = getSoldQty($allocation, $item);
            return $item;
        });
        $breadcrumb = $this->allocation->breadcrumbs('get-sold-qty', $allocation);
        return view('sales.allocation.get-sold-qty', compact('allocation', 'breadcrumb', 'items'));
    }

    public function updateSoldQty(Request $request, DailySale $allocation)
    {
        $products = $request->input('products');
        foreach ($products as $product){
            $salesItem = DailySaleItem::where('daily_sale_id', $allocation->id)
                ->where('product_id', $product)->first();
            $salesItem->sold_qty = getSoldQty($allocation, $salesItem);
            $salesItem->save();
        }
        alert()->success('Sold Qty updated successfully', 'Success')->persistent();
        return redirect()->route('sales.allocation.sheet', $allocation);
    }

    public function getActualQty(DailySale $allocation)
    {
        $items = $allocation->items;
        $items = $items->map(function (DailySaleItem $item) use ($allocation){
            $item->sold_pro_qty = getSoldQty($allocation, $item);
            return $item;
        });
        $breadcrumb = $this->allocation->breadcrumbs('get-actual-qty', $allocation);
        return view('sales.allocation.get-actual-qty', compact('allocation', 'breadcrumb', 'items'));
    }

    public function updateActualQty(Request $request, DailySale $allocation)
    {
        $products = $request->input('products');
        $items = DailySaleItem::where('daily_sale_id', $allocation->id)
            ->whereIn('product_id', $products)->get();

        foreach ($products as $product){
            $salesItem = DailySaleItem::where('daily_sale_id', $allocation->id)
                ->where('product_id', $product)->first();
            $salesItem->restored_qty = $salesItem->actual_stock;
            $salesItem->save();
        }
        $this->stockUpdateJob($items, $allocation);

        alert()->success('Actual stocks restored successfully', 'Success')->persistent();
        return redirect()->route('sales.allocation.sheet', $allocation);
    }

    public function stockUpdateJob($items, $allocation)
    {
        $data = [];
        foreach ($items as $item) {
            $data[$item->id] = [];
            $productId = $item->product_id;
            $storeId = $item->store_id;
            $quantity = $item->actual_stock;
            $stock = Stock::where('product_id', $productId)
                ->where('store_id', $storeId)
                ->where('company_id', $allocation->company_id)
                ->first();
            $data[$item->id]['quantity'] = $quantity;
            $data[$item->id]['stock'] = $stock;
            $data[$item->id]['transable'] = $allocation;
            $data[$item->id]['allocationRoute'] = $allocation->route ? $allocation->route->name : '';
            $data[$item->id]['allocationDate'] = $allocation->from_date;
        }
        dispatch(new StockUpdateJob('In', $data));
    }

    public function restoreStock(DailySale $allocation, DailySaleItem $item)
    {
        $handover = $allocation->salesHandover;
        $breadcrumb = $this->allocation->breadcrumbs('restore-stock', $allocation);
        return view('sales.allocation.restore-stock', compact('allocation', 'item', 'breadcrumb', 'handover'));
    }

    public function doRestoreStock(RestoreStockRequest $request, DailySale $allocation, DailySaleItem $item)
    {
        $shortageQty = $request->input('shortage_qty');
        $excessQty = $request->input('excess_qty');
        $restoredQty = $request->input('restored_qty');

        /** update daily sales item table */
        $item->setAttribute('shortage_qty', ($item->getAttribute('shortage_qty') + $shortageQty));
        $item->setAttribute('excess_qty', ($item->getAttribute('excess_qty') + $excessQty));
        $item->setAttribute('restored_qty', ($item->getAttribute('restored_qty') + $restoredQty));
        $item->save();

        /** check available stocks and updates */
        $availableStock = Stock::where('store_id', $item->getAttribute('store_id'))
            ->where('product_id', $item->getAttribute('product_id'))
            ->first();
        if($availableStock){
            $availableStock->available_stock = ((float)$availableStock->available_stock + $restoredQty);
            $availableStock->save();

            /** add stock history as Restore */
            $history = new StockHistory();
            $history->setAttribute('stock_id', $availableStock->getAttribute('id'));
            $history->setAttribute('quantity', $restoredQty);
            $history->setAttribute('type', 'Restore');
            $history->setAttribute('transaction', 'In');
            $history->setAttribute('trans_date', carbon()->now()->toDateString());
            $history->setAttribute('trans_description', 'Stock restored (Manual)');
            $history->setAttribute('transable_id', $allocation->id);
            $history->setAttribute('transable_type', 'App\DailySale');
            $history->save();
        }else{
            $newStock = new Stock();
            $newStock->setAttribute('store_id', $item->getAttribute('store_id'));
            $newStock->setAttribute('available_stock', $restoredQty);
            $newStock->setAttribute('product_id', $item->getAttribute('product_id'));
            $newStock->setAttribute('notes', 'Stock restored (Manual)');
            $newStock->setAttribute('type', 'Manual');
            $newStock->setAttribute('company_id', $allocation->company_id);
            $newStock->setAttribute('min_stock_level', '5000');
            $newStock->save();

            /** add new stock history */
            $newStockHistory = new StockHistory();
            $newStockHistory->setAttribute('stock_id', $newStock->getAttribute('id'));
            $newStockHistory->setAttribute('quantity', $restoredQty);
            $newStockHistory->setAttribute('type', 'Restore');
            $newStockHistory->setAttribute('transaction', 'In');
            $newStockHistory->setAttribute('trans_date', carbon()->now()->toDateString());
            $newStockHistory->setAttribute('trans_description', 'Stock restored (Manual)');
            $newStockHistory->setAttribute('transable_id', $allocation->id);
            $newStockHistory->setAttribute('transable_type', 'App\DailySale');
            $newStockHistory->save();
        }

        alert()->success('Stocks restored successfully', 'Success')->persistent();
        return redirect()->route('sales.allocation.show', $allocation);
    }

    public function allowMobileLogin(DailySale $allocation)
    {
        $data = $this->allocation->allowMobileLogin($allocation);
        return response()->json($data);
    }

}
