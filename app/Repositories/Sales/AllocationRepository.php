<?php

namespace App\Repositories\Sales;

use App\Customer;
use App\DailySale;
use App\DailySaleCustomer;
use App\DailySaleItem;
use App\DailySalesOdoReading;
use App\DailyStock;
use App\InvoicePayment;
use App\Jobs\CreateHandoverJob;
use App\Jobs\NotifyAllocationToStore;
use App\Jobs\StockUpdateJob;
use App\Product;
use App\Repositories\BaseRepository;
use App\SalesLocation;
use App\SalesOrder;
use App\Stock;
use Illuminate\Http\Request;

/**
 * Class PaymentRepository
 * @package App\Repositories\Sales
 */
class AllocationRepository extends BaseRepository
{
    /**
     * AllocationRepository constructor.
     * @param DailySale|null $dailySale
     */
    public function __construct(DailySale $dailySale = null)
    {
        $this->setModel($dailySale ?? new DailySale());
        $this->setCodePrefix('SAL', 'code');
    }

    /**
     * @return mixed
     */
    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $route = \request()->input('route');
        $rep = \request()->input('rep');
        $location = \request()->input('location');
        $customer = \request()->input('customer');
        $product = \request()->input('product');
        $start = \request()->input('from_date');
        $end = \request()->input('to_date');

        $lastWeek = carbon()->subWeek();
        $dateRange = \request()->input('dateRange') ?? false;
        $dailySales = DailySale::whereIn('company_id', userCompanyIds(loggedUser()))
            ->orderBy('created_at', 'desc')
            ->with('preparedBy', 'salesLocation', 'vehicle', 'customers',
            'items', 'route', 'rep', 'salesLocation');
        if ($search) {
            $dailySales->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('from_date', 'LIKE', '%' . $search . '%')
                    ->orWhere('to_date', 'LIKE', '%' . $search . '%')
                    ->orWhere('status', 'LIKE', '%' . $search . '%')
                    ->orwhere(function ($query) use ($search) {
                        $query->whereHas('rep', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->orwhere(function ($query) use ($search) {
                        $query->whereHas('route', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->orwhere(function ($query) use ($search) {
                        $query->whereHas('salesLocation', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        });
                    });
            });
        }

        switch ($filter) {
            case 'Van':
                $dailySales->where('sales_location', 'Van');
                break;
            case 'Shop':
                $dailySales->where('sales_location', 'Shop');
                break;
            case 'Draft':
                $dailySales->where('status', 'Draft');
                break;
            case 'Active':
                $dailySales->where('status', 'Active');
                break;
            case 'Progress':
                $dailySales->where('status', 'Progress');
                break;
            case 'Completed':
                $dailySales->where('status', 'Completed');
                break;
            case 'Canceled':
                $dailySales->where('status', 'Canceled');
                break;
            case 'recentlyCreated':
                $dailySales->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $dailySales->where('updated_at', '>', $lastWeek);
                break;
            case 'HoPending':
                $dailySales->with('salesHandover')->whereHas('salesHandover', function ($q) {
                    $q->where('status', 'Pending');
                });
                break;
            case 'today':
                $start = carbon()->toDateString();
                $end = carbon()->toDateString();
                $dateRange = true;
                break;
        }


        if ($route) {
            $dailySales->where('route_id', $route);
        }

        if ($rep) {
            $dailySales->where('rep_id', $rep);
        }

        if ($location) {
            $dailySales->where('sales_location_id', $location);
        }

        if ($customer) {
            $dailySales->whereHas('customers', function ($q) use ($customer) {
                $q->where('customer_id', $customer);
            });
        }

        if ($product) {
            $dailySales->whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product);
            });
        }

        if ($dateRange) {
            $dailySales->where(function ($q) use ($start, $end) {
                $q->where(function ($q) use ($start, $end) {
                    $q->where('from_date', '>=', $start)->where('from_date', '<=', $end);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('to_date', '>=', $start)->where('to_date', '<=', $end);
                });
            });
        }
        return $dailySales->paginate(20)->toArray();
    }

    /**
     * @param Request $request
     * @param bool $isAPI
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function save(Request $request, $isAPI = false)
    {
        $this->model->code = $this->getCode();
        $this->saveAllocationData($request);
        $this->model->save();
        $this->saveReading($request);
        $this->saveCustomers($request);
        if($request->input('sales_location') == 'Van'){
            $this->saveProductsForVan($request);
        }else{
            $this->saveProductsForShop($request);
        }
        return $this->model;
    }

    /**
     * @param DailySale $allocation
     * @param Request $request
     * @param bool $isAPI
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function update(DailySale $allocation, Request $request, $isAPI = false)
    {
        $this->setModel($allocation);
        $this->saveAllocationData($request);
        $this->model->save();
        $this->saveReading($request);
        $this->saveCustomers($request, true);
        if($request->input('sales_location') == 'Van'){
            $this->saveProductsForVan($request, true);
        }else{
            $this->saveProductsForShop($request, true);
        }
        return $this->model;
    }

    /**
     * @param $request
     * @param bool $change
     * @return array
     */
    public function getOldAllocationData($request, $change = true)
    {
        if ($request->input('sales_location_id') && $change) {
            $request->merge([
                'sales_location' => $request->input('sales_location_id')
            ]);
        }
        $location = $request->input('salesLocation') ?? $request->input('sales_location');
        if (!$change) {
            $location = $request->input('sales_location_id');
        }
        $rep = $request->input('rep') ?? $request->input('rep_id');
        $route = $request->input('route') ?? $request->input('route_id');
        $allocationId = $request->input('allocation') ?? '';
        $allocations = DailySale::whereIn('status', ['Progress', 'Completed']);
        if ($allocationId) {
            $allocations->whereNotIn('id', [$allocationId]);
        }
        if ($location) {
            $allocations->where('sales_location_id', $location);
        }

        if ($rep) {
            $allocations->where('rep_id', $rep);
        }

//        if ($route) {
//            $allocations->where('route_id', $route);
//        }
        $data = [];
        $allocation = $allocations->orderBy('from_date', 'DESC')->first();
        if ($allocation) {
            $products = $allocation->items;
            foreach ($products as $product) {
                $quantity = $this->calculateBalanceQty($product);
                if ($quantity > 0) {
                    $data[$product->product_id] = $quantity;
                }
            }
        }

        if ($allocationId) {
            $oldAllocation = DailySale::find($allocationId);
            $products = $oldAllocation->items;
            foreach ($products as $product) {
                if ($product->cf_qty > 0) {
                    $data[$product->product_id] = $product->cf_qty;
                }
            }
        }
        return $data;
    }

    /**
     * @param $product
     * @return mixed
     */
    public function calculateBalanceQty($product)
    {
        $totalQty = $product->quantity + $product->cf_qty + $product->returned_qty  + $product->excess_qty;
        //$forDeduct = $product->sold_qty + $product->restored_qty + $product->replaced_qty + $product->shortage_qty + $product->damaged_qty;
        $forDeduct = $product->sold_qty + $product->restored_qty + $product->replaced_qty + $product->shortage_qty;
        return $totalQty - $forDeduct;
    }

    /**
     * @param DailySale $allocation
     * @param $status
     * @return array
     */
    public function statusChange(DailySale $allocation, $status)
    {
        $oldStatus = $allocation->status;
        if ($allocation->status == $status) return ['success' => true];
        if ($status !== 'Completed') {
            $allocation->status = $status;
            $allocation->save();
        }
        if ($status == 'Completed') {
            dispatch(new CreateHandoverJob($allocation));
        }
        if ($allocation->status == 'Progress') {
            $this->setModel($allocation);
            $this->stockUpdateJob('Out', null, null, $allocation);
        }
        if ($oldStatus == 'Progress' && $allocation->status == 'Canceled' && !$allocation->orders->count()) {
            $this->setModel($allocation);
            $this->restoreProduct($allocation);
            $this->stockUpdateJob('In', null, null, $allocation);
        }
        return ['success' => true];
    }

    /**
     * @param $request
     */
    public function saveAllocationData($request)
    {
        $vehicleId = null;
        $location = null;
        if ($request->input('sales_location_id')) {
            $location = SalesLocation::find($request->input('sales_location_id'));
            $vehicleId = $location->vehicle_id ?? null;
        }
        $this->model->day_type = $request->input('day_type');
        $this->model->from_date = $request->input('from_date');
        $this->model->to_date = $request->input('to_date');
        $this->model->days = carbon($request->input('from_date'))->diffInDays($request->input('to_date'));
        $this->model->sales_location = $request->input('sales_location');
        $this->model->sales_location_id = $request->input('sales_location_id');
        $this->model->vehicle_id = $vehicleId;
        $this->model->rep_id = $request->input('rep_id');
        $this->model->route_id = $request->input('route_id');
        $this->model->store_id = $request->input('store_id');
        $this->model->notes = $request->input('notes');
        $this->model->prepared_by = auth()->user()->id;
        $this->model->company_id = $location->company_id;
        $this->model->driver_id = $request->input('driver_id');
        $this->model->labour_id = $request->input('labour_id');
        $this->model->allowance = $request->input('allowance');
        $this->model->start_time = carbon($request->input('start_time'));
        $this->model->end_time = carbon($request->input('end_time'));
        if ($request->input('submit') == 'Draft') {
            $this->model->status = 'Draft';
        }

    }

    public function saveReading($request)
    {
        $vehicle = $this->model->vehicle;
        $reading = $this->model->odoMeterReading;
        if ($vehicle) {
            if (!$reading) {
                $reading = new DailySalesOdoReading();
            }
            $reading->vehicle_id = $vehicle->id;
            $reading->daily_sale_id = $this->model->id;
            $reading->starts_at = $request->input('odo_meter_reading');
            $reading->save();
        }
    }

    /**
     * @param $request
     * @param null $edit
     */
    public function saveCustomers($request, $edit = null)
    {

        $oldCustomers = [];
        $customers = array_get($request->input('customer'), 'id') ?? [];
        $logData = ['Allocation' => $this->model->code, 'customer-count' => count($customers)];
        $name = 'Allocation Customers';
        if ($edit) {
            $name = 'Allocation Customers edit';
            $logData['edit'] = true;
            $oldCustomers = $this->model->customers->pluck('customer_id', 'customer_id')->toArray();
            $diffArray = array_diff_key($oldCustomers, $customers);
            if ($diffArray) {
                $deleteCustomers = DailySaleCustomer::whereIn('customer_id', $diffArray)->get();
                $logData['deleted data count'] = $deleteCustomers->count();
                foreach ($deleteCustomers as $deleteCustomer) {
                    $deleteCustomer->delete();
                }
            }
        }
        if ($customers) {
            $logData['customers'] = '';
            foreach ($customers as $id => $value) {
                $customerName = Customer::find($id);
                if (array_key_exists($id, $oldCustomers)) continue;
                $customer = new DailySaleCustomer();
                $customer->daily_sale_id = $this->model->id;
                $customer->customer_id = $id;
                if ($this->model->status == 'Progress') {
                    $customer->added_stage = 'Later';
                }
                $customer->save();
                if ($customerName) {
                    $logData['customers'] .= $customerName->display_name;
                    $logData['customers'] .= ', ';
                }
            }
        }
        auditLog()
            ->performedOn(new DailySale())->useLog($name)
            ->withProperties(['attributes' => $logData])
            ->log('Customer Data - (Request data)');
    }

    /**
     * @param $request
     * @param null $edit
     * @throws \Exception
     */
    public function saveProductsForShop($request, $edit = null)
    {
        $oldProducts = [];
        $cfData = $this->getOldAllocationData($request);
        $productsData = $request->input('product');

        $products = array_get($productsData, 'id');
        $stores = array_get($productsData, 'store');
        $name = 'Allocation products';
        $quantities = array_get($productsData, 'quantity');
        $cfQuantities = array_get($productsData, 'cf');

        $logData = ['Allocation' => $this->model->code, 'product-count' => count($products)];
        if ($edit) {
            $logData['edit'] = true;
            $name = 'Allocation products edited';
            $oldProducts = $this->model->items->pluck('product_id', 'product_id')->toArray();
            $diffArray = array_diff_key($oldProducts, $products);
            if ($diffArray) {
                $deleteProducts = DailySaleItem::where('daily_sale_id', $this->model->id)->whereIn('product_id', $diffArray)->get();
                $logData['deleted product'] = $deleteProducts->count();
                foreach ($deleteProducts as $deleteProduct) {
                    /**
                     * @var $deleteProduct DailySaleItem
                     */
                    $deleteProduct->delete();
                }
            }
        }
        $logData['products'] = '';
        foreach ($products as $id => $value) {
            $product = new DailySaleItem();
            $productName = Product::find($id);
            if ($id) {
                $oldProduct = DailySaleItem::where('daily_sale_id', $this->model->id)->where('product_id', $id)->first();
                if ($oldProduct) $product = $oldProduct;
            }
            if ($productName) {
                $logData['products'] .= $productName->name;
                $logData['products'] .= ', ';
            }
            $product->daily_sale_id = $this->model->id;
            $product->product_id = $id;
            $product->store_id = $request->input('store_id');
            $product->quantity = array_get($quantities, $id);
            $product->cf_qty = array_get($cfQuantities, $id);
            $product->save();
        }

        auditLog()
            ->performedOn(new DailySale())->useLog($name)
            ->withProperties(['attributes' => $logData])
            ->log('Product Data - (Request data)');
    }

    public function saveProductsForVan($request, $edit = null)
    {
        $dailyStock = DailyStock::where('route_id', $request->input('route_id'))
            ->where('status', 'Allocated')
            ->with('items')
            ->orderBy('id', 'desc')
            ->first();

        $products = $dailyStock->items;

        $products = $products->reject(function ($product) {
            return $product->issued_qty == 0 && $product->available_qty == 0;
        });

        foreach ($products as $value) {
            $product = new DailySaleItem();
            $product->daily_sale_id = $this->model->id;
            $product->product_id = $value->product_id;
            $product->store_id = $value->store_id;
            $product->quantity = $value->issued_qty ? $value->issued_qty : 0;
            $product->cf_qty = $value->available_qty ? $value->available_qty : 0;
            $product->save();
        }

    }

    /**
     * @param $type
     * @param array $ids
     * @param array $addValues
     * @param $allocation
     */
    public function stockUpdateJob($type, $ids = [], $addValues = [], $allocation)
    {
        $items = $allocation->items;
        if ($ids) {
            $items = $allocation->items()->whereIn('id', $ids)->get();
        }
        $data = [];
        foreach ($items as $item) {
            $data[$item->id] = [];
            $productId = $item->product_id;
            $storeId = $item->store_id;
            $quantity = $item->quantity;
            $stock = Stock::where('product_id', $productId)->where('store_id', $storeId)->first();
            $data[$item->id]['quantity'] = array_get($addValues, $item->id, $quantity);
            $data[$item->id]['stock'] = $stock;
            $data[$item->id]['transable'] = $allocation;
            $data[$item->id]['allocationRoute'] = $allocation->route ? $allocation->route->name : '';
            $data[$item->id]['allocationDate'] = $allocation->from_date;
        }
        dispatch(new StockUpdateJob($type, $data));
    }

    /**
     * @param string $method
     * @param DailySale|null $allocation
     * @return array
     */
    public function breadcrumbs(string $method, DailySale $allocation = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations']
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => 'Create']
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '']
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Edit']
            ],
            'attach' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Attach Credit Orders']
            ],
            'change' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Change Rep / Driver / Labour']
            ],
            'attach-phone-order' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Attach Phone Orders']
            ],
            'sales-sheet' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Sales Sheet']
            ],
            'complete' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Complete Allocation']
            ],
            'add-expense' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Add Expense']
            ],
            'get-sold-qty' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Update Sold Qty']
            ],
            'get-actual-qty' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Restore Actual Qty']
            ],
            'restore-stock' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Restore Stocks']
            ],
            'allow-mobile-login' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Allow Mobile Login']
            ],
            'allocate-products' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Allocate Products']
            ],
            'add-customers' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $allocation->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => [$allocation->id ?? null]],
                ['text' => 'Add Customers']
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @param DailySale $allocation
     * @return mixed
     */
    public function getCheques(DailySale $allocation)
    {
        $fromData = $allocation->from_date;
        $toDate = $allocation->to_date;
        $customers = $allocation->customers()->with('customer.payments')->get();
        $payments = $customers->pluck('customer')->pluck('payments')->collapse()->pluck('id')->toArray() ?? [];
        $payments = InvoicePayment::whereIn('id', $payments)->wherebetween('payment_date', [$fromData, $toDate])->where('payment_mode', 'Cheque')->get();
        return $payments;
    }

    /**
     * @param DailySale $allocation
     */
    public function restoreProduct(DailySale $allocation)
    {
        $products = $allocation->items;
        foreach ($products as $product) {
            $product->restored_qty = $product->quantity;
            $product->save();
        }
    }

    /**
     * @param DailySale $allocation
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static|static[]
     */
    public function creditOrders(DailySale $allocation)
    {
        $dailySaleCreditOrders = $allocation->dailySaleCreditOrders->pluck('sales_order_id')->toArray();
        $ids = $allocation->customers->pluck('customer_id')->toArray();
        $orders = SalesOrder::with('customer')->whereNotIn('id', $dailySaleCreditOrders)
            ->whereIn('customer_id', $ids)
            ->where('is_credit_sales', 'Yes')
            ->where('status', '!=', 'Canceled')
            ->get();
        $orders = $orders->reject(function ($order) {
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
        return $orders;
    }

    public function phoneOrders(DailySale $allocation)
    {
        $allocatedCustomers = $allocation->customers()
            ->pluck('customer_id')->toArray();

        $fromDate = $allocation->from_date;
        $toDate = $allocation->to_date;

        $orders = SalesOrder::with('customer')
            ->whereBetween('order_date', [$fromDate, $toDate])
            ->whereIn('customer_id', $allocatedCustomers)
            ->where('rep_id', $allocation->rep_id)
            ->where('sales_category', 'Van')
            ->where('status', 'Draft')
            ->get();

        $orders = $orders->reject(function ($order) {
            $amount = $order->total;
            $payments = $order->payments;
            $others = $payments->whereNotIn('payment_mode', ['Cheque'])->sum('payment');
            $cheques = $payments->where('payment_mode', 'Cheque')->sum('payment');

            $order->balance = $order->total - ($others + $cheques);
            $order->paid = $others + $cheques;
            if ($amount == ($others + $cheques)) {
                return true;
            }
            return false;
        });

        return $orders;
    }

    public function createShopAllocation(SalesLocation $shop)
    {
        $allocation = new DailySale();
        $allocation->setAttribute('code', $this->getCode());
        $allocation->setAttribute('day_type', 'Multiple');
        $allocation->setAttribute('from_date', carbon()->now()->toDateString());
        $allocation->setAttribute('to_date', carbon()->now()->toDateString());
        $allocation->setAttribute('days', 1);
        $allocation->setAttribute('sales_location', 'Shop');
        $allocation->setAttribute('sales_location_id', $shop->getAttribute('id'));
        $allocation->setAttribute('notes', 'Initial sales allocation to ' . $shop->getAttribute('name'));
        $allocation->setAttribute('status', 'Progress');
        $allocation->setAttribute('prepared_by', 1);
        $allocation->setAttribute('company_id', $shop->getAttribute('company_id'));
        $allocation->save();

        /** attach allocation items */
        $products = $shop->products;

        $products->each(function (Product $product) use ($allocation) {

            /** create sales allocation for shop */
            $allocationItem = new DailySaleItem();
            $allocationItem->setAttribute('daily_sale_id', $allocation->id);
            $allocationItem->setAttribute('product_id', $product->id);
            $allocationItem->setAttribute('quantity', $product->pivot->default_qty);
            $allocationItem->setAttribute('store_id', 1);
            $allocationItem->save();

        });

        /** release the stocks */
        $this->setModel($allocation);
        $this->stockUpdateJob('Out');

        return $allocation;
    }

    public function allowMobileLogin(DailySale $allocation)
    {
        $allocation->setAttribute('is_logged_in', 'No');
        $allocation->setAttribute('is_logged_out', 'No');
        $allocation->setAttribute('logged_in_at', null);
        $allocation->save();

        return ['success' => true];
    }

}
