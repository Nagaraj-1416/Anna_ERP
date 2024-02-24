<?php

namespace App\Repositories\Report;

use App\Customer;
use App\CustomerCredit;
use App\DailySale;
use App\Estimate;
use App\Invoice;
use App\InvoicePayment;
use App\Product;
use App\ProductCategory;
use App\Rep;
use App\Repositories\BaseRepository;
use App\Route;
use App\SalesInquiry;
use App\SalesLocation;
use App\SalesOrder;
use App\SalesReturn;
use App\SalesReturnItem;
use Illuminate\Http\Request;

/**
 * Class SalesReportRepository
 * @package App\Repositories\Report
 */
class SalesReportRepository extends BaseRepository
{
    protected $mappedSummery;

    public function salesSummary()
    {
        $request = request();
        $orders = $this->getSo($request);
        $orders = $orders->with(['invoices', 'customer', 'payments', 'salesRep', 'route', 'location', 'company'])->get();
        return $this->reportGroup($orders);
    }

    public function reportGroup($orders)
    {
        return $orders->groupBy('company_id')->map(function ($items, $key) {
            $orderByCategory = $items->groupBy('sales_category');
            $mappedCategoryOrders = $orderByCategory->map(function ($items, $key) {
                $item = $items->first();
                $children = collect();
                $summary = '';
                switch ($item->sales_category) {
                    case 'Van':
                        $children = $this->mapVanOrders($items);
                        $summary = 'VAN SALES';
                        break;
                    case 'Shop':
                        $children = $this->mapShopOrders($items);
                        $summary = 'SHOP SALES';
                        break;
                    case 'Office':
                        $children = $this->mapOfficeOrders($items);
                        $summary = 'OFFICE SALES';
                        break;
                }
                return $this->sumOrderSummery($children, [
                    'summary' => $summary,
                    'related_id' => $item->sales_category,
                    'related_column' => 'sales_category',
                    'class' => 'table-blue',
                    'children' => $children,
                    'query' => [
                        'sales_category' => $item->sales_category,
                        'company_id' => $item->company_id,
                    ],
                ]);
            });

            $item = $items->first();
            return $this->sumOrderSummery($mappedCategoryOrders, [
                'summary' => $item->company->name ?? 'Not Available',
                'related_id' => $item->company_id,
                'related_column' => 'company_id',
                'class' => 'table-green',
                'children' => $mappedCategoryOrders,
                'query' => [
                    'company_id' => $item->company_id,
                ],
            ]);
        });
    }

    public function salesSummaryList(Request $request)
    {
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $orders = SalesOrder::whereIn('status', ['Open', 'Closed'])
            ->whereBetween('order_date', [$fromDate, $toDate]);

        $filterColumns = ['sales_category', 'location_id', 'customer_id', 'route_id', 'rep_id', 'company_id'];
        foreach ($request->all() as $key => $value) {
            if (in_array($key, $filterColumns)) {
                $value = $value ? $value : null;
                $orders = $orders->where($key, $value);
            }
        }
        return $orders->with('customer')->get()->map(function ($item) {
            $totalSales = $item->total;
            $PaymentItems = $item->payments;
            $totalPaid = $PaymentItems->sum('payment');
            return [
                'customer_name' => $item->customer->display_name ?? 'None',
                'customer_id' => $item->customer_id,
                'ref' => $item->ref,
                'id' => $item->id,
                'status' => $item->status,
                'is_credit_sales' => $item->is_credit_sales == 'No' ? 'Cash' : 'Credit',
                'order_date' => carbon($item->created_at)->toDayDateTimeString(),
                'total_sales' => (float)$totalSales,
                'total_paid' => (float)$totalPaid,
                'total_cash' => (float)$PaymentItems->where('payment_mode', 'Cash')->sum('payment'),
                'total_cheque' => (float)$PaymentItems->where('payment_mode', 'Cheque')->sum('payment'),
                'total_deposit' => (float)$PaymentItems->where('payment_mode', 'Direct Deposit')->sum('payment'),
                'total_card' => (float)$PaymentItems->where('payment_mode', 'Credit Card')->sum('payment'),
                'total_balance' => (float)$totalSales - $totalPaid,
            ];
        });
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function salesSummaryProductList(Request $request)
    {
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $orders = SalesOrder::whereIn('status', ['Open', 'Closed'])
            ->whereBetween('order_date', [$fromDate, $toDate]);

        $filterColumns = ['sales_category', 'location_id', 'customer_id', 'route_id', 'rep_id', 'company_id'];
        foreach ($request->all() as $key => $value) {
            if (in_array($key, $filterColumns)) {
                $value = $value ? $value : null;
                $orders = $orders->where($key, $value);
            }
        }
        $products = $orders->with('products.stocks')->get()->pluck('products')->collapse();
        $products = $products->groupBy('id');
        return $products->map(function ($items) {
            $item = $items->first();
            $stock = $item->stocks->first();
            return [
                'id' => $item->id,
                'quantity' => $items->sum('pivot.quantity'),
                'amount' => $items->sum('pivot.amount'),
                'name' => $item->name,
                'available_stock' => $stock ? $stock->available_stock : 0
            ];
        });
    }

    protected function mapShopOrders($orders)
    {
        return $orders->groupBy('location_id')->map(function ($items, $key) {
            $item = $items->first();
            return $this->getMapOrderSummary($items, [
                'summary' => $item->location->name ?? 'Not Available',
                'related_id' => $item->location_id,
                'class' => 'table-success',
                'related_column' => 'location_id',
                'query' => [
                    'sales_category' => 'Shop',
                    'location_id' => $item->location_id,
                    'company_id' => $item->company_id,
                ],
            ]);
        });
    }

    protected function mapOfficeOrders($orders)
    {
        return $orders->groupBy('location_id')->map(function ($items, $key) {
            $orderByCustomer = $items->groupBy('customer_id');
            $mappedCustomerOrders = $orderByCustomer->map(function ($items, $key) {
                $item = $items->first();
                return $this->getMapOrderSummary($items, [
                    'summary' => $item->customer->display_name ?? 'Not Available',
                    'related_id' => $item->customer_id,
                    'class' => 'table-warning',
                    'related_column' => 'customer_id',
                    'query' => [
                        'sales_category' => 'Office',
                        'location_id' => $item->location_id,
                        'customer_id' => $item->customer_id,
                        'company_id' => $item->company_id,
                    ],
                ]);
            });

            $item = $items->first();
            return $this->sumOrderSummery($mappedCustomerOrders, [
                'summary' => $item->location->name ?? 'Not Available',
                'related_id' => $item->location_id,
                'related_column' => 'location_id',
                'class' => 'table-success',
                'children' => $mappedCustomerOrders,
                'query' => [
                    'sales_category' => 'Office',
                    'location_id' => $item->location_id,
                    'company_id' => $item->company_id,
                ],
            ]);
        });
    }

    protected function mapVanOrders($orders)
    {
        return $orders->groupBy('rep_id')->map(function ($items, $key) {
            $ordersByRoute = $items->groupBy('route_id');
            // Map route's order
            $mappedRouteOrders = $ordersByRoute->map(function ($items, $key) {
                $orderByLocation = $items->groupBy('location_id');
                // Map location's order
                $mappedLocationOrders = $orderByLocation->map(function ($items, $key) {
                    $orderByCustomer = $items->groupBy('customer_id');
                    // Map customer's order
                    $mappedCustomerOrders = $orderByCustomer->map(function ($items, $key) {
                        $item = $items->first();
                        return $this->getMapOrderSummary($items, [
                            'summary' => $item->customer->display_name ?? 'Not Available',
                            'related_id' => $item->customer_id,
                            'related_column' => 'customer_id',
                            'class' => '',
                            'query' => [
                                'sales_category' => 'Van',
                                'rep_id' => $item->rep_id,
                                'route_id' => $item->route_id,
                                'location_id' => $item->location_id,
                                'customer_id' => $item->customer_id,
                                'company_id' => $item->company_id,
                            ],
                        ]);
                    });
                    $item = $items->first();
                    return $this->sumOrderSummery($mappedCustomerOrders, [
                        'summary' => $item->location->name ?? 'Not Available',
                        'related_id' => $item->location_id,
                        'related_column' => 'location_id',
                        'class' => 'table-active',
                        'children' => $mappedCustomerOrders,
                        'query' => [
                            'sales_category' => 'Van',
                            'rep_id' => $item->rep_id,
                            'route_id' => $item->route_id,
                            'location_id' => $item->location_id,
                            'company_id' => $item->company_id,
                        ],
                    ]);
                });
                $item = $items->first();
                return $this->sumOrderSummery($mappedLocationOrders, [
                    'summary' => $item->route->name ?? 'Not Available',
                    'related_id' => $item->route_id,
                    'related_column' => 'route_id',
                    'class' => 'table-warning',
                    'children' => $mappedLocationOrders,
                    'query' => [
                        'sales_category' => 'Van',
                        'rep_id' => $item->rep_id,
                        'route_id' => $item->route_id,
                        'company_id' => $item->company_id,
                    ],
                ]);
            });
            $item = $items->first();
            return $this->sumOrderSummery($mappedRouteOrders, [
                'summary' => $item->salesRep->name ?? 'Not Available',
                'related_id' => $item->rep_id,
                'related_column' => 'rep_id',
                'class' => 'table-success',
                'children' => $mappedRouteOrders,
                'query' => [
                    'sales_category' => 'Van',
                    'rep_id' => $item->rep_id,
                    'company_id' => $item->company_id,
                ],
            ]);
        });
    }

    protected function sumOrderSummery($orders, $otherDate = [])
    {
        $data = [
            'total_sales' => $orders->sum('total_sales'),
            'total_invoiced' => $orders->sum('total_invoiced'),
            'total_paid' => $orders->sum('total_paid'),
            'total_cash' => $orders->sum('total_cash'),
            'total_cheque' => $orders->sum('total_cheque'),
            'total_deposit' => $orders->sum('total_deposit'),
            'total_card' => $orders->sum('total_card'),
            'total_balance' => $orders->sum('total_balance'),
        ];
        return array_merge($data, $otherDate);
    }

    protected function getMapOrderSummary($orders, $otherDate = [])
    {
        $totalSales = $orders->sum('total');
        $PaymentItems = $orders->pluck('payments')->collapse();
        $invoiceItems = $orders->pluck('invoices')->collapse();
        $totalPaid = $PaymentItems->where('status', 'Paid')->sum('payment');
        $data = [
            'total_sales' => (float)$totalSales,
            'total_invoiced' => (float)$invoiceItems->sum('amount'),
            'total_paid' => (float)$totalPaid,
            'total_cash' => (float)$PaymentItems->where('payment_mode', 'Cash')->where('status', 'Paid')->sum('payment'),
            'total_cheque' => (float)$PaymentItems->where('payment_mode', 'Cheque')->where('status', 'Paid')->sum('payment'),
            'total_deposit' => (float)$PaymentItems->where('payment_mode', 'Direct Deposit')->where('status', 'Paid')->sum('payment'),
            'total_card' => (float)$PaymentItems->where('payment_mode', 'Credit Card')->where('status', 'Paid')->sum('payment'),
            'total_balance' => (float)$totalSales - $totalPaid,
        ];
        return array_merge($data, $otherDate);
    }

    /**
     * @return array
     */
    public function salesByCus()
    {
        $request = request();
        $customer = $request->input('customer');
        $orders = $this->getSo($request, 'customer');
        if ($customer) {
            $orders->where('customer_id', $customer);
        }
        $data = [];
        $orders = $orders->with(['invoices', 'customer', 'payments'])->get();
        $invoices = $orders->pluck('invoices')->collapse();
        $payments = $orders->pluck('payments')->collapse();
        $customerId = array_keys($orders->groupBy('customer_id')->toArray());
        $customer = Customer::whereIn('id', $customerId)->get(['id', 'display_name']);

        $data['orders'] = $orders->groupBy('customer_id');
        $data['order_total'] = $orders->sum('total');
        $data['invoice_total'] = $invoices->sum('amount');
        $data['payment_total'] = $payments->sum('payment');
        $data['balance'] = $orders->sum('total') - $payments->sum('payment');
        $data['customer'] = $customer;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function salesByRep()
    {
        $request = request();
        $rep = $request->input('rep');
        $orders = $this->getSo($request);
        $orders->where('order_mode', 'customer');
        if ($rep) {
            $orders->where('rep_id', $rep);
        }
        $data = [];
        $orders = $orders->with(['invoices', 'salesRep', 'payments'])->get();
        $invoices = $orders->pluck('invoices')->collapse();
        $payments = $orders->pluck('payments')->collapse();
        $repIds = array_keys($orders->groupBy('rep_id')->toArray());
        $rep = Rep::whereIn('id', $repIds)->get(['id', 'name']);
        $data['orders'] = $orders->groupBy('rep_id');
        $data['order_total'] = $orders->sum('total');
        $data['invoice_total'] = $invoices->sum('amount');
        $data['payment_total'] = $payments->sum('payment');
        $data['balance'] = $orders->sum('total') - $payments->sum('payment');
        $data['rep'] = $rep;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function salesByRoute()
    {
        $request = request();
        $route = $request->input('route');
        $orders = $this->getSo($request);
        $orders->where('order_mode', 'customer');
        if ($route) {
            $orders->where('route_id', $route);
        }
        $data = [];

        $orders = $orders->with(['invoices', 'route', 'payments', 'customer'])->get();

        $orders = $orders->map(function (SalesOrder $order) {
            $order->createdAt = date("F j, Y, g:i a", strtotime($order->created_at));
            return $order;
        });

        $invoices = $orders->pluck('invoices')->collapse();
        $payments = $orders->pluck('payments')->collapse();
        $routeIds = array_keys($orders->groupBy('route_id')->toArray());
        $route = Route::whereIn('id', $routeIds)->get(['id', 'name']);
        $data['orders'] = $orders->groupBy('route_id');
        $data['order_total'] = $orders->sum('total');
        $data['invoice_total'] = $invoices->sum('amount');
        $data['payment_total'] = $payments->sum('payment');
        $data['balance'] = $orders->sum('total') - $payments->sum('payment');
        $data['route'] = $route;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function salesByPro()
    {
        $request = request();

        $productID = $request->input('product');
        $repID = $request->input('rep');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();

        $product = Product::where('id', $productID)->with('salesOrders')->get();

        $products = $product->transform(function (Product $product) use ($repID, $fromDate, $toDate){
            if($repID){
                $product->orders = $product->salesOrders->where('status', '!=', 'Canceled')
                    ->where('is_opining', 'No')
                    ->where('rep_id', $repID)
                    ->where('order_date', '>=', $fromDate)
                    ->where('order_date', '<=', $toDate);
            }else{
                $product->orders = $product->salesOrders->where('status', '!=', 'Canceled')
                    ->where('is_opining', 'No')
                    ->where('order_date', '>=', $fromDate)
                    ->where('order_date', '<=', $toDate);
            }
            return $product;
        });

        $data = [];
        $data['products'] = $products;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function damageByPro()
    {
        $request = request();

        $companyId = $request->input('company');
        $productID = $request->input('product');
        $reason = $request->input('reason');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();

        $items = SalesReturnItem::where('company_id', $companyId)->where('product_id', $productID)
            ->whereBetween('date', [$fromDate, $toDate]);

        if($reason){
            $items = $items->where('reason', 'LIKE', $reason);
        }

        $items = $items->with('order', 'salesReturn.customer', 'salesReturn.allocation')->get();
        $customerIds = array_keys($items->groupBy('customer_id')->toArray());
        $customers = Customer::whereIn('id', $customerIds)->get(['id', 'display_name'])->toArray();

        $data = [];
        $data['items'] = $items->groupBy('customer_id');
        $data['totalQty'] = $items->sum('qty');
        $data['totalAmount'] = $items->sum('returned_amount');
        $data['customers'] = $customers;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function damageByRoute()
    {
        $request = request();

        $companyId = $request->input('company');
        $routeId = $request->input('route');
        $productId = $request->input('product');
        $reason = $request->input('reason');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();

        $items = SalesReturnItem::where('route_id', $routeId)
            ->where('company_id', $companyId)
            ->where('product_id', $productId)
            ->whereBetween('date', [$fromDate, $toDate]);

        if($reason){
            $items = $items->where('reason', 'LIKE', $reason);
        }

        $items = $items->with('order', 'salesReturn.customer', 'salesReturn.allocation')->get();
        $customerIds = array_keys($items->groupBy('customer_id')->toArray());
        $customers = Customer::whereIn('id', $customerIds)->get(['id', 'display_name'])->toArray();

        $data = [];
        $data['items'] = $items->groupBy('customer_id');
        $data['totalQty'] = $items->sum('qty');
        $data['totalAmount'] = $items->sum('returned_amount');
        $data['customers'] = $customers;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function damageByRep()
    {
        $request = request();

        $companyId = $request->input('company');
        $repId = $request->input('rep');
        $productId = $request->input('product');
        $reason = $request->input('reason');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();

        $items = SalesReturnItem::where('rep_id', $repId)
            ->where('company_id', $companyId)
            ->where('product_id', $productId)
            ->whereBetween('date', [$fromDate, $toDate]);

        if($reason){
            $items = $items->where('reason', 'LIKE', $reason);
        }

        $items = $items->with('order', 'salesReturn.customer', 'salesReturn.allocation')->get();
        $customerIds = array_keys($items->groupBy('customer_id')->toArray());
        $customers = Customer::whereIn('id', $customerIds)->get(['id', 'display_name'])->toArray();

        $data = [];
        $data['items'] = $items->groupBy('customer_id');
        $data['totalQty'] = $items->sum('qty');
        $data['totalAmount'] = $items->sum('returned_amount');
        $data['customers'] = $customers;
        $data['request'] = $request->toArray();
        return $data;
    }

    public function damageByCustomer()
    {

    }

    /**
     * @return array
     */
    public function salesByProCat()
    {
        $request = request();
        $categoryID = $request->input('category');
        $request = request();
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $businessType = $request->input('businessType');
        $categories = ProductCategory::with(['products' => function ($q) use ($fromDate, $toDate, $businessType) {
            $q->whereHas('salesOrders', function ($q) use ($fromDate, $toDate, $businessType) {
                $q->whereBetween('order_date', [$fromDate, $toDate]);
                if ($businessType) {
                    $q->where('business_type_id', $businessType);
                }
            });
        }])->has('products.salesOrders')->whereHas('products', function ($q) use ($fromDate, $toDate, $businessType) {
            $q->whereHas('salesOrders', function ($q) use ($fromDate, $toDate, $businessType) {
                $q->whereBetween('order_date', [$fromDate, $toDate]);
                if ($businessType) {
                    $q->where('business_type_id', $businessType);
                }
            });
        })->get();
        if ($categoryID) {
            $categories->where('id', $categoryID);
        }
        $data = [];
        $orders = $categories->pluck('products')->collapse()->pluck('salesOrders')->collapse()->unique();
        $data['categories'] = $categories;
        $data['order_total'] = $orders->sum('total');
        $data['quantity_total'] = $orders->pluck('pivot')->sum('quantity');
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function monthlySales()
    {
        $request = request();
        // Get From To data from Request
        $company = $request->input('company');
        $year = $request->input('year') ?? carbon()->format('Y');
        $fromMonth = $request->input('fromMonth') ?? carbon()->format('M');
        $toMonth = $request->input('toMonth') ?? carbon()->format('M');

        $months = ['Jan' => 1, 'Feb' => 2, 'Mar' => 3, 'Apr' => 4, 'May' => 5, 'Jun' => 6, 'Jul' => 7, 'Aug' => 8, 'Sept' => 9, 'Oct' => 10, 'Nov' => 11, 'Dec' => 12];
        // Create a Dates
        $fromDate = carbon()->year((int)$year)->month((int)array_get($months, $fromMonth))->startOfMonth();
        $toDate = carbon()->year((int)$year)->month(((int)array_get($months, $toMonth)))->endOfMonth();
        //Merge Request for get orders
        $request->merge(['fromDate' => $fromDate->copy()->toDateString()]);
        $request->merge(['toDate' => $toDate->copy()->toDateString()]);
        $request->merge(['company' => $company]);

        //Get Orders
        $orders = $this->getSo($request)->get();

        //Check Difference
        $difference = $fromDate->diffInMonths($toDate);

        //Get All Dates
        $dates = [];
        for ($start = 0; $start <= $difference; $start++) {
            $date = $fromDate->copy()->addMonth($start)->format('M Y');
            array_push($dates, $date);
        }
        $customerGrouped = $orders->groupBy('customer_id');
        $data = [];
        $data['dates'] = $dates;
        $data['data'] = [];
        $data['customer'] = [];
        $orders = $customerGrouped->transform(function ($orders, $index) use (&$data, $dates) {
            $orders = $orders->transform(function (SalesOrder $order) {
                $order->group = carbon($order->order_date)->format('M Y');
                return $order;
            })->groupBy('group')->toArray();
            $customer = Customer::find($index);
            $data['data'][$index] = [];
            $data['customer'][$index] = [];
            $data['customer'][$index] = [
                'id' => $customer->id ?? '', 'display_name' => $customer->display_name ?? '',
            ];

            foreach ($dates as $key => $value) {
                $order = array_get($orders, $value);
                if ($order) {
                    $amounts = array_pluck($order, 'total');
                    $data['data'][$index][$value] = array_sum($amounts);
                } else {
                    $data['data'][$index][$value] = 0;
                }
            }
        });
        //Get Data
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @param $request
     * @param null $mode
     * @return mixed
     */
    public function getSo($request, $mode = null)
    {
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();

        $company = $request->input('company');

        $orders = SalesOrder::whereIn('status', ['Open', 'Closed'])
            ->whereBetween('order_date', [$fromDate, $toDate]);

        if ($mode) {
            $orders->where('order_mode', 'Customer');
        }
        if ($company) {
            $orders->where('company_id', $company);
        }
        return $orders;
    }

    /**
     * @return array
     */
    public function paysReceived()
    {
        $request = request();
        $company = $request->input('company');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $paymentType = $request->input('paymentType');
        $paymentMode = $request->input('paymentMode');

        $payments = InvoicePayment::whereBetween('payment_date', [$fromDate, $toDate]);

        if ($company) {
            $payments->where('company_id', $company);
        }
        if ($paymentType) {
            $payments->where('payment_type', $paymentType);
        }

        if ($paymentMode) {
            $payments->where('payment_mode', $paymentMode);
        }

        $data = [];
        $payments = $payments->with(['invoice', 'customer', 'depositedTo'])->get();
        $customerId = array_keys($payments->groupBy('customer_id')->toArray());
        $customer = Customer::whereIn('id', $customerId)->get(['id', 'display_name']);

        $data['payments'] = $payments->groupBy('customer_id');
        $data['payments_total'] = $payments->sum('payment');
        $data['customer'] = $customer->toArray();
        $data['request'] = $request->toArray();

        return $data;
    }

    /**
     * @return array
     */
    public function creditDetails()
    {
        $request = request();
        $customer = $request->input('customer');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $businessType = $request->input('businessType');

        $credits = CustomerCredit::whereBetween('date', [$fromDate, $toDate]);
        if ($businessType) {
            $credits->where('business_type_id', $businessType);
        }
        if ($customer) {
            $credits->where('customer_id', $customer);
        }
        $data = [];
        $credits = $credits->with(['customer', 'refunds', 'payments'])->get();
        $customerId = array_keys($credits->groupBy('customer_id')->toArray());
        $customer = Customer::whereIn('id', $customerId)->get(['id', 'display_name']);
        $creditsTotal = $credits->sum('amount');
        $refundedTotal = $credits->pluck('refunds')->collapse()->sum('amount');
        $paymentTotal = $credits->pluck('payments')->collapse()->sum('payment');
        $balance = $creditsTotal - ($paymentTotal + $refundedTotal);

        $data['credits'] = $credits->groupBy('customer_id');
        $data['credits_total'] = $creditsTotal;
        $data['refunded_total'] = $refundedTotal;
        $data['payment_total'] = $paymentTotal;
        $data['balance'] = $balance;
        $data['customer'] = $customer;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function customerBalance()
    {
        $request = request();
        $date = $request->input('date') ?? carbon()->toDateString();
        $customers = Customer::where(function ($q) use ($date) {
            $q->where(function ($q) use ($date) {
                $q->whereHas('orders', function ($orders) use ($date) {
                    $orders->where('order_date', '<=', $date);
                });
            })->orWhere(function ($q) use ($date) {
                $q->whereHas('invoices', function ($orders) use ($date) {
                    $orders->where('invoice_date', '<=', $date);
                });
            })->orWhere(function ($q) use ($date) {
                $q->whereHas('payments', function ($orders) use ($date) {
                    $orders->where('payment_date', '<=', $date);
                });
            });
        })->get();

        $soTotal = $customers->pluck('orders')->collapse()->sum('total');
        $paymentTotal = $customers->pluck('payments')->collapse()->sum('payment');
        $data = [];
        $data['customers'] = $customers;
        $data['so_total'] = $soTotal;
        $data['invoice_total'] = $customers->pluck('invoices')->collapse()->sum('amount');
        $data['payment_total'] = $paymentTotal;
        $data['balance'] = $soTotal - $paymentTotal;
        $data['request'] = $request->toArray();

        return $data;
    }

    /**
     * @return array
     */
    public function agingSummary()
    {
        $data = [];
        $returnData = [];
        $request = request();
        $company = $request->input('company');
        $route = $request->input('route');
        $rep = $request->input('rep');
        $date = $request->input('date') ?? carbon()->toDateString();

        if ($company) {
            $invoices = Invoice::where('company_id', $company);
        }

        if ($company && $route) {
            $invoices = Invoice::where('company_id', $company)
                ->where(function ($query) use ($route) {
                    $query->whereHas('order', function ($q) use ($route) {
                        $q->where('route_id', $route);
                    });
                });
        }

        if ($company && $route && $rep) {
            $invoices = Invoice::where('company_id', $company)
                ->where(function ($query) use ($route, $rep) {
                    $query->whereHas('order', function ($q) use ($route, $rep) {
                        $q->where('route_id', $route)->where('rep_id', $rep);
                    });
                });
        }

        $invoices = $invoices->where('due_date', '<=', $date)->with('customer')->get()->groupBy('customer_id');

        $customerID = array_keys($invoices->toArray());
        foreach ($invoices as $key => $invoice) {
            $data[$key] = getDueCollection($invoice, $data[$key]);
        }

        $customers = Customer::whereIn('id', $customerID)->get()->toArray();
        $returnData['data'] = $data;
        $returnData['customers'] = $customers;
        $returnData['request'] = $request->toArray();
        return $returnData;
    }

    /**
     * @return array
     */
    public function invoiceDetails()
    {
        $request = request();

        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $balanceType = $request->input('balanceType');
        $company = $request->input('company');
        $route = $request->input('route');
        $rep = $request->input('rep');

        $invoices = Invoice::whereIn('status', ['Open', 'Partially Paid', 'Paid'])
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->with(['customer', 'payments', 'order', 'order.salesRep']);

        if ($company) {
            $invoices = $invoices->where('company_id', $company);
        }

        if ($route) {
            $invoices = $invoices->where(function ($query) use ($route) {
                $query->whereHas('order', function ($q) use ($route) {
                    $q->where('route_id', $route);
                });
            });
        }

        if ($rep) {
            $invoices = $invoices->where(function ($query) use ($rep) {
                $query->whereHas('order', function ($q) use ($rep) {
                    $q->where('rep_id', $rep);
                });
            });
        }

        $invoices = $invoices->get();

        if ($balanceType ==  'ZeroBalance'){
            $invoices = $invoices->reject(function ($item) use ($toDate){
                $bal = invOutstandingAsAt($item, $toDate)['balance'];
                return $bal > 0;
            });
        }
        if($balanceType ==  'WithBalance'){
            $invoices = $invoices->reject(function ($item) use($toDate){
                $bal = invOutstandingAsAt($item, $toDate)['balance'];
                return $bal == 0;
            });
        }

        $paymentTotal = $invoices->pluck('payments')->collapse()->where('payment_date', '<=', $toDate)->sum('payment');
        $invoiceTotal = $invoices->sum('amount');

        $data = [];
        $data['invoices'] = $invoices;
        $data['invoice_total'] = $invoices->sum('amount');
        $data['invoice_payment'] = $paymentTotal;
        $data['balance'] = $invoiceTotal - $paymentTotal;
        $data['request'] = $request->toArray();
        return $data;
    }

    public function allocationDetails()
    {
        $request = request();

        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $company = $request->input('company');
        $route = $request->input('route');
        $rep = $request->input('rep');
        $repData = Rep::find($rep);

        $allocations = DailySale::whereBetween('to_date', [$fromDate, $toDate])
            ->whereIn('status', ['Progress', 'Completed'])
            ->with(['route', 'rep']);

        if ($company) {
            $allocations = $allocations->where('company_id', $company);
        }

        if ($route) {
            $allocations = $allocations->where('route_id', $route);
        }

        if ($rep) {
            $allocations = $allocations->where('rep_id', $rep);
        }

        $allocations = $allocations->get();
        $allocations = $allocations->map(function ($allocation) {
            $allocation->total_sales = allocationDetails($allocation)['total'];
            $allocation->received = allocationDetails($allocation)['received'];
            $allocation->cash_received = allocationDetails($allocation)['cash_received'];
            $allocation->cheque_received = allocationDetails($allocation)['cheque_received'];
            $allocation->balance = allocationDetails($allocation)['balance'];
            $allocation->expenses = allocationDetails($allocation)['expenses'];
            $allocation->returns = allocationDetails($allocation)['returns'];
            $allocation->old_received = allocationDetails($allocation)['old_received'];
            $allocation->old_cash_received = allocationDetails($allocation)['old_cash_received'];
            $allocation->old_cheque_received = allocationDetails($allocation)['old_cheque_received'];
            return $allocation;
        });
        
        $data = [];
        $data['allocations'] = $allocations;
        $data['allocation_total_sales'] = $allocations->sum('total_sales');
        $data['allocation_received'] = $allocations->sum('received');
        $data['allocation_cash_received'] = $allocations->sum('cash_received');
        $data['allocation_cheque_received'] = $allocations->sum('cheque_received');
        $data['allocation_balance'] = $allocations->sum('balance');
        $data['allocation_expenses'] = $allocations->sum('expenses');
        $data['allocation_returns'] = $allocations->sum('returns');
        $data['allocation_old_received'] = $allocations->sum('old_received');
        $data['allocation_old_cash_received'] = $allocations->sum('old_cash_received');
        $data['allocation_old_cheque_received'] = $allocations->sum('old_cheque_received');
        $data['request'] = $request->toArray();
        $data['rep'] = $repData;
        return $data;
    }

    /**
     * @return array
     */
    public function agingDetails()
    {
        $request = request();
        $data = [];
        $data['data'] = [];
        $data['data']['1-30'] = $this->getDueInvoiceData($request, [1, 30]);
        $data['data']['31-60'] = $this->getDueInvoiceData($request, [31, 60]);
        $data['data']['61-90'] = $this->getDueInvoiceData($request, [61, 90]);
        $data['data']['>90'] = $this->getDueInvoiceData($request, '>90');
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @param $request
     * @param $dateRange
     * @return mixed
     */
    public function getDueInvoiceData($request, $dateRange)
    {
        $date = $request->input('date') ?? carbon()->toDateString();
        $invoices = Invoice::whereNotIn('status', ['Paid', 'Canceled'])->where('due_date', '<', $date)->with(['customer', 'payments'])->get();
        $invoices = $invoices->filter(function ($invoice) use ($dateRange) {
            $dueDate = $invoice->due_date;
            $diff = carbon()->diffInDays(carbon($dueDate));
            $invoice->dateRange = $dateRange;
            $invoice->age = $diff;
            if (!is_array($dateRange) && $dateRange == '>90') {
                if ($diff > 90) {
                    return $invoice;
                }

                return null;
            }
            if (is_array($dateRange)) {
                $start = array_get($dateRange, 0);
                $end = array_get($dateRange, 1);
                if ($start <= $diff && $end >= $diff) {
                    return $invoice;
                }

            }
            return null;
        });
        return $invoices;
    }

    /**
     * @return array
     */
    public function estimateDetails()
    {
        $request = request();

        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $businessType = $request->input('businessType');
        $estimates = Estimate::whereBetween('estimate_date', [$fromDate, $toDate])->with(['customer'])->get();
        if ($businessType) {
            $estimates = $estimates->where('business_type_id', $businessType);
        }
        $estimateTotal = $estimates->sum('total');
        $data = [];
        $data['estimates'] = $estimates;
        $data['estimate_total'] = $estimateTotal;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function inquiryDetails()
    {
        $request = request();

        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $businessType = $request->input('businessType');
        $inquiries = SalesInquiry::whereBetween('inquiry_date', [$fromDate, $toDate])->with(['customer', 'preparedBy'])->get();
        if ($businessType) {
            $inquiries = $inquiries->where('business_type_id', $businessType);
        }

        $data = [];
        $data['inquiries'] = $inquiries;
        $data['inquiry_total'] = $inquiries->sum('total');
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function salesByLocation()
    {
        $request = request();
        $location = $request->input('location');
        $orders = $this->getSo($request);
        if ($location) {
            $orders->where('sales_location_id', $location);
        }
        $data = [];
        $orders = $orders->with(['invoices', 'customer', 'payments', 'salesLocation'])->get();
        $invoices = $orders->pluck('invoices')->collapse();
        $payments = $orders->pluck('payments')->collapse();
        $locationIds = array_keys($orders->groupBy('sales_location_id')->toArray());
        $location = SalesLocation::whereIn('id', $locationIds)->get(['id', 'name']);
        $data['orders'] = $orders->groupBy('sales_location_id');
        $data['order_total'] = $orders->sum('total');
        $data['invoice_total'] = $invoices->sum('amount');
        $data['payment_total'] = $payments->sum('payment');
        $data['balance'] = $orders->sum('total') - $payments->sum('payment');
        $data['location'] = $location;
        $data['request'] = $request->toArray();
        return $data;
    }
}
