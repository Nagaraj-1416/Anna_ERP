<?php

namespace App\Repositories\Sales;

use App\Account;
use App\Customer;
use App\DailySaleItem;
use App\Estimate;
use App\Invoice;
use App\Jobs\StockUpdateJob;
use App\Jobs\UpdateSoldQty;
use App\Price;
use App\PriceBook;
use App\Product;
use App\Repositories\BaseRepository;
use App\Repositories\General\DocumentRepository;
use App\Route;
use App\SalesHandover;
use App\SalesInquiry;
use App\SalesOrder;
use App\Stock;
use App\Store;
use App\UnitType;
use Illuminate\Http\Request;

/**
 * Class OrderRepository
 * @package App\Repositories\Sales
 */
class OrderRepository extends BaseRepository
{
    protected $document;

    /**
     * OrderRepository constructor.
     * @param SalesOrder|null $order
     * @param DocumentRepository $document
     */
    public function __construct(SalesOrder $order = null, DocumentRepository $document)
    {
        $this->document = $document;
        $this->setModel($order ?? new SalesOrder());
        $this->setCodePrefix('SO', 'order_no');
        $this->setRefPrefix('OR');
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['order_no', 'order_date', 'delivery_date', 'order_type', 'scheduled_date', 'total', 'status',
            'delivery_status', 'invoice_status', 'prepared_by', 'approval_status', 'approved_by', 'customer_id', 'business_type_id', 'company_id'];

        $searchingColumns = ['order_no', 'order_date', 'delivery_date', 'order_type', 'scheduled_date', 'total', 'status',
            'delivery_status', 'invoice_status', 'prepared_by', 'approval_status', 'approved_by', 'customer_id', 'business_type_id', 'company_id'];

        $relation = ['customer' => [['as' => 'customer_name', 'column' => 'display_name']]];

        $data = $this->getTableData($request, $columns, $searchingColumns, $relation);
        $data['data'] = array_map(function ($item) {
            $item['order_no'] = '<a href="' . route('sales.order.show', $item['id']) . '">' . $item['order_no'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Show', null, ['sales.order.show', [$item['id']]], ['class' => 'btn-success']);
            $item['action'] .= actionBtn('Edit', null, ['sales.order.edit', [$item['id']]]);
            $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-so']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    /**
     * @param Request $request
     * @param bool $isAPI
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(Request $request, $isAPI = false)
    {
        $order = $this->storeData($request, $isAPI);
        $this->updateInquiry($request, $order);
        $this->updateEstimation($request, $order);
        return $order;
    }

    /**
     * @param Request $request
     * @param bool $isAPI
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function storeData(Request $request, $isAPI = false)
    {
        // Check Duplication
        $uuid = $request->input('uuid');
        if ($isAPI && !$this->model->id && $uuid) {
            $duplicateOrder = SalesOrder::where('uuid', $uuid)->first();
            if ($duplicateOrder) {
                return $duplicateOrder->load(['customer', 'products']);
            }
            $this->model->setAttribute('uuid', $uuid);
        }

        if (!$request->input('discount_type')) {
            $request->merge(['discount_type' => 'Amount']);
        }

        if (!$request->input('discount_rate')) {
            $request->merge(['discount_rate' => 0]);
        }
        $request->merge(['route_id' => null]);
        $request->merge(['location_id' => null]);
        $customer = Customer::find($request->input('customer_id'));
        if ($isAPI) {
            $request->merge(['route_id' => $customer ? $customer->route_id : null]);
            $request->merge(['location_id' => $customer ? $customer->location_id : null]);
            $request->merge(['sales_type' => 'Distribution']);
            $request->merge(['sales_category' => 'Van']);
            $request->merge(['discount_type' => 'Amount']);
            $request->merge(['discount_rate' => 0]);
            $request->merge(['price_book_id' => null]);
            $request->merge(['adjustment' => 0]);
            $user = auth()->user();
            $request->merge(['submit' => $request->input('save_as')]);
            $rep = $user->staffs->first() ? $user->staffs->first()->rep : null;
            if ($rep) {
                $request->merge(['rep_id' => $rep->id]);
            }
            if ($request->input('is_order_printed')) {
                $this->model->setAttribute('is_order_printed', $request->input('is_order_printed'));
            }
            $location = userVanLocation();
            $request->merge(['sales_location_id' => $location ? $location->id : null]);

            if (!$this->model->id && $request->input('created_at')) {
                $createdAt = carbon($request->input('created_at'));
                $this->model->setAttribute('order_date', $createdAt->toDateString());
                $this->model->setAttribute('created_at', $createdAt->toDateTimeString());
            }

            if (!$this->model->id) {
                $dailySale = getRepAllocation()->first();
                $this->model->setAttribute('daily_sale_id', $dailySale ? $dailySale->id : null);
                // update customer location
                if ($customer && (!$customer->gps_lat || !$customer->gps_long)) {
                    $customer->setAttribute('gps_lat', $request->input('gps_lat'));
                    $customer->setAttribute('gps_long', $request->input('gps_long'));
                    $customer->save();
                }
            }
        } else {
            /*if ($request->input('sales_location_id')){
            $request->merge(['sales_category' => 'Shop']);
            }else{
            $request->merge(['sales_category' => 'Office']);
            }*/

            $request->merge(['order_type' => 'Direct']);

            $request->merge(['ref' => $this->generateRef()]);
            if (!showLocationDropdown()) {
                $location = userShopLocation();
                $request->merge(['sales_location_id' => $location ? $location->id : null]);
            }
        }

        /** get listed products */
        $products = $this->mapProducts($request, $isAPI);
        $productAmounts = array_pluck($products, 'amount');
        $productAmount = array_sum($productAmounts);

        /** get given discount */
        $discount = 0;
        if ($request->input('discount_type') == 'Percentage') {
            if ($request->input('discount_type') > 0) {
                $discount = $productAmount * ($request->input('discount_rate') / 100);
            }
        } else {
            $discount = $request->input('discount_rate');
        }

        if (!$this->model->getAttribute('order_no')) {
            $this->model->setAttribute('order_no', $this->getCode());
        }

        if (!$this->model->id) {
            $this->model->setAttribute('gps_lat', $request->input('gps_lat'));
            $this->model->setAttribute('gps_long', $request->input('gps_long'));
        }

        if (!$this->model->getAttribute('ref')) {
            $this->model->setAttribute('ref', $request->input('ref'));
        }
        $this->model->setAttribute('route_id', $request->input('route_id'));
        $this->model->setAttribute('location_id', $request->input('location_id'));
        $this->model->setAttribute('sales_location_id', $request->input('sales_location_id'));
        $this->model->setAttribute('order_date', $request->input('order_date'));
        $this->model->setAttribute('delivery_date', $request->input('delivery_date'));
        $this->model->setAttribute('order_type', $request->input('order_type'));
        $this->model->setAttribute('sales_type', $request->input('sales_type'));
        $this->model->setAttribute('sales_category', $request->input('sales_category'));
        $this->model->setAttribute('scheduled_date', $request->input('scheduled_date'));

        /** if po received */

        $this->model->setAttribute('terms', $request->input('terms'));
        $this->model->setAttribute('notes', $request->input('notes'));

        /** set subtotal, discount, adjustment and total */
        $this->model->setAttribute('sub_total', $productAmount);
        $this->model->setAttribute('discount_type', $request->input('discount_type'));
        $this->model->setAttribute('discount_rate', $request->input('discount_rate'));
        $this->model->setAttribute('discount', $discount);

        $adjustment = $request->input('adjustment') ? $request->input('adjustment') : 0;
        $this->model->setAttribute('adjustment', $adjustment);

        $totalAmount = ($productAmount - $discount) + ($adjustment);
        $this->model->setAttribute('total', $totalAmount);

        /** set order status to Draft if order save as Save as Draft */
        if (!$isAPI && $request->input('submit') == 'Draft') {
            $this->model->setAttribute('status', 'Draft');
        }

        if (!$isAPI && $request->input('submit') == 'Save') {
            $this->model->setAttribute('status', 'Awaiting Approval');
        }

        /*if (!$this->model->getAttribute('status')) {
            $this->model->setAttribute('status', 'Open');
        }*/

        if ($isAPI && !$this->model->id && $request->input('submit') == 'Save') {
            $this->model->setAttribute('status', 'Open');
        }

        if ($isAPI && $this->model->id && $request->input('status')) {
            $this->model->setAttribute('status', $request->input('status'));
        }

        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('customer_id', $request->input('customer_id'));
        $this->model->setAttribute('rep_id', $request->input('rep_id'));
        $this->model->setAttribute('price_book_id', $request->input('price_book_id'));
        $this->model->setAttribute('business_type_id', $request->input('business_type_id'));
        $this->model->setAttribute('company_id', $customer->company_id ?? 1);
        if ($this->model->id) {
            $this->updateLocationId($this->model);
        }
        $this->model->save();

        /** attach products to order */
        $products = $this->mapProducts($request, $isAPI);
        $this->model->products()->attach($products);

        /** upload support documents */
        $files = $request->file('files');
        if ($files) {
            foreach ($files as $file) {
                $this->document->setDocumentable($this->model);
                $this->document->save($file);
            }
        }
        $this->model->invoices()->update(['customer_id' => $this->model->customer_id]);
        $this->model->payments()->update(['customer_id' => $this->model->customer_id]);
        if ($isAPI) {
            $this->updateVisited($this->model, $request);
        }
        return $this->model->refresh();
    }

    /**
     * @param SalesOrder $order
     * @param Request $request
     */
    public function updateVisited(SalesOrder $order, Request $request)
    {
        $customer = $order->customer;
        $allocations = getRepAllocation()->first();
        $dailySalesCustomers = $allocations ? $allocations->customers : null;
        if (!$dailySalesCustomers) {
            return;
        }

        $dailySalesCustomer = $dailySalesCustomers->where('customer_id', $customer->id)->first();
        if ($dailySalesCustomer && $dailySalesCustomer->is_visited == 'No') {
            $dailySalesCustomer->update([
                'is_visited' => 'Yes',
                'reason' => 'System - Order created',
                'gps_lat' => $request->input('gps_lat'),
                'gps_long' => $request->input('gps_long'),
            ]);
        }
    }

    /**
     * @param Request $request
     * @param bool $isAPI
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(Request $request, $isAPI = false)
    {
        /** get order items */
        $items = $this->model->products;

        /** release stock from selected store */
        $this->stockUpdate('In', $items, $this->model);

        /** release stock from sold qty column in daily sales items table */
        //dispatch(new UpdateSoldQty('Update', $this->model));

        $this->model->products()->detach();
        return $this->storeData($request, $isAPI);
    }

    /**
     * @param $type
     * @param $items
     * @param $transable
     */
    public function stockUpdate($type, $items, $transable)
    {
        $data = [];
        foreach ($items as $item) {
            $data[$item->id] = [];
            $productId = $item->pivot->product_id;
            $storeId = $item->pivot->store_id;
            $quantity = $item->pivot->quantity;
            $stock = Stock::where('product_id', $productId)->where('store_id', $storeId)->first();
            $data[$item->id]['quantity'] = $quantity;
            $data[$item->id]['stock'] = $stock;
            $data[$item->id]['transable'] = $transable;
        }
        dispatch(new StockUpdateJob($type, $data));
    }

    /**
     * Update the status to order
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateStatus(Request $request)
    {
        $this->model->setAttribute('status', $request->input('status'));
        $this->model->save();
        return $this->model;
    }

    /**
     * delete a sales order
     * @param SalesOrder $order
     * @return array
     */
    public function delete(SalesOrder $order): array
    {
        try {
            $order->delete();
            return ['success' => true, 'message' => 'deleted success'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'deleted failed'];
        }
    }

    /**
     * @param Request $request
     * @param SalesOrder $order
     */
    protected function updateInquiry(Request $request, SalesOrder $order)
    {
        if ($request->input('inquiry_id')) {
            $inquiry = SalesInquiry::find($request->input('inquiry_id'));
            if (!$inquiry) {
                return;
            }

            if ($inquiry->status != 'Open') {
                return;
            }

            $inquiry->converted_type = 'App\\SalesOrder';
            $inquiry->converted_id = $order->id;
            $inquiry->status = 'Converted to Order';
            $inquiry->save();
        }
    }

    /**
     * @param Request $request
     * @param SalesOrder $order
     */
    public function updateEstimation(Request $request, SalesOrder $order)
    {
        if ($request->input('estimation_id')) {
            $estimation = Estimate::find($request->input('estimation_id'));
            if (!$estimation) {
                return;
            }

            if ($estimation->status != 'Accepted') {
                return;
            }

            $estimation->converted_type = 'App\\SalesOrder';
            $estimation->converted_id = $order->id;
            $estimation->status = 'Ordered';
            $estimation->order_status = 'Ordered';
            $estimation->save();
        }
    }

    /**
     * @return mixed
     */
    public function todayIndexOld()
    {
        $customers = getAllAllocatedCustomers();
        $status = ['Scheduled', 'Draft', 'Awaiting Approval', 'Open'];
        $customerIds = $customers->pluck('id')->toArray();
        return SalesOrder::where('prepared_by', auth()->id())
            ->where(function ($query) use ($status) {
                $query->whereIn('status', $status)
                    ->orWhere(function ($query) {
                        $query->whereHas('payments', function ($query) {
                            $query->where('payment_date', now()->toDateString())->where('prepared_by', auth()->id());
                        });
                    })
                    ->orWhere(function ($query) {
                        $query->whereHas('invoices', function ($query) {
                            $query->where('invoice_date', now()->toDateString())->where('prepared_by', auth()->id());
                        });
                    })
                    ->orWhere('order_date', now()->toDateString());
            })
            ->whereIn('customer_id', $customerIds)->with([
            'products', 'invoices', 'payments.bank', 'customer.addresses', 'company.addresses',
        ])->get();
    }

    /**
     * @return mixed
     */
    public function todayIndex()
    {
        $allocations = getRepAllocation();
        $allocation = $allocations->first();
        $customers = getAllAllocatedCustomers($allocations);
        $status = ['Open', 'Closed'];
        $orderIds = getAllocationCreditOrdersId();
        $customerIds = $customers->pluck('id')->toArray();
        return SalesOrder::whereIn('customer_id', $customerIds)
            ->whereIn('status', $status)
            ->where(function ($q) use ($orderIds, $allocation) {
                $q->where(function ($q) use ($allocation) {
                    $q->whereBetween('order_date', [$allocation->from_date, $allocation->to_date])
                        ->where('prepared_by', auth()->id());
                })->orWhereIn('id', $orderIds);
            })->with([
            'products', 'invoices', 'payments.bank', 'customer.addresses', 'company.addresses',
        ])->get();
    }

    /**
     * @param string $method
     * @param SalesOrder|null $order
     * @return array
     */
    public function breadcrumbs(string $method, SalesOrder $order = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Orders'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Orders', 'route' => 'sales.order.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Orders', 'route' => 'sales.order.index'],
                ['text' => $order->order_no ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Orders', 'route' => 'sales.order.index'],
                ['text' => $order->order_no ?? ''],
                ['text' => 'Edit'],
            ],
            'clone' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Orders', 'route' => 'sales.order.index'],
                ['text' => $order->order_no ?? ''],
                ['text' => 'Clone'],
            ],
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Orders', 'route' => 'sales.order.index'],
                ['text' => $order->order_no ?? ''],
                ['text' => 'Print Order'],
            ],
            'confirm' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Orders', 'route' => 'sales.order.index'],
                ['text' => $order->order_no ?? ''],
                ['text' => 'Confirm Order'],
            ],
            'invoice' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Orders', 'route' => 'sales.order.index'],
                ['text' => $order->order_no ?? ''],
                ['text' => 'Generate Invoice'],
            ],
            'payment' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Orders', 'route' => 'sales.order.index'],
                ['text' => $order->order_no ?? ''],
                ['text' => 'Record Payment'],
            ],
            'credit-orders' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Credit Orders'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @param Request $request
     * @param bool $isAPI
     * @return array
     */
    public function mapProducts(Request $request, $isAPI = false)
    {
        $mappedProducts = [];
        if ($isAPI) {
            if (!$request->input('order_items') || !is_array($request->input('order_items'))) {
                return [];
            }

            foreach ($request->input('order_items') as $item) {
                // for last reqirments
                $item['discount_rate'] = 0;
                $item['discount_type'] = 'Amount';
                if (!isset($item['quantity'])) {
                    $item['quantity'] = 0;
                }
                $item['rate'] = $this->calculateProductRateForApi($item['product_id'] ?? null, $item['quantity'], $request);
                $amount = ($item['quantity'] * $item['rate']);
                $discount = 0;
                if (isset($item['discount_type']) && isset($item['discount_rate']) && isset($amount) && $item['discount_type'] == 'Percentage') {
                    if ($item['discount_rate'] > 0) {
                        $discount = $amount * ($item['discount_rate'] / 100);
                    }
                }
                if (isset($item['discount_type']) && isset($item['discount_rate']) && isset($amount) && $item['discount_type'] == 'Amount') {
                    $discount = $item['discount_rate'];
                }
                $totalAmount = $amount - $discount;
                $item['discount'] = $discount;
                $item['price_book_id'] = $item['price_book_id'] ?? null;
                $item['unit_type_id'] = $item['unit_type_id'] ?? null;
                $item['amount'] = $totalAmount;
                $item['status'] = 'Pending';
                $item['notes'] = $item['notes'] ?? null;
                $item['sales_order_id'] = $this->model->id;
                $item['is_vehicle'] = 'Yes';
                $user = auth()->user();
                $rep = $user->staffs->first() ? $user->staffs->first()->rep : null;
                $vehicle = $rep ? $rep->vehicle : null;
                if ($vehicle) {
                    $item['store_id'] = $vehicle->id;
                }
                array_push($mappedProducts, $item);
            }
        } else {
            $products = $request->input('product');
            $stores = $request->input('store');
            $qty = $request->input('quantity');
            $unitTypes = $request->input('unit_type');
            $discountRate = $request->input('item_discount_rate');
            $discountType = $request->input('item_discount_type');
            $notes = $request->input('product_notes');
            $priceBook = $request->input('price_book_id');
            $priceBookModel = PriceBook::find($priceBook);
            foreach ($products as $key => $product) {
                if (!$product) {
                    continue;
                }

                $unitType = $unitTypes[$key] ?? null;
                $rate = $this->calculateProductRate($product, $qty[$key], $request, $priceBookModel);
                $amount = ($qty[$key] * $rate);
                $discount = 0;
                if (isset($discountType[$key]) && isset($discountRate[$key]) && isset($amount) && $discountType[$key] == 'Percentage') {
                    if ($discountRate[$key] > 0) {
                        $discount = $amount * ($discountRate[$key] / 100);
                    }
                }
                if (isset($discountType[$key]) && isset($discountRate[$key]) && isset($amount) && $discountType[$key] == 'Amount') {
                    $discount = $discountRate[$key];
                }
                if (!isset($qty[$key])) {
                    $qty[$key] = 0;
                }

                $totalAmount = $amount - $discount;
                $mappedProduct = [
                    'sales_order_id' => $this->model->id ?? null,
                    'price_book_id' => $priceBook,
                    'unit_type_id' => $unitType,
                    'product_id' => $products[$key] ?? null,
                    'store_id' => $stores[$key] ?? null,
                    'quantity' => $qty[$key] ?? null,
                    'rate' => $rate,
                    'discount_type' => $discountType[$key] ?? null,
                    'discount_rate' => $discountRate[$key] ?? null,
                    'discount' => $discount,
                    'amount' => $totalAmount ?? null,
                    'status' => 'Pending',
                    'notes' => $notes[$key] ?? null,
                ];
                array_push($mappedProducts, $mappedProduct);
            }
        }
        return $mappedProducts;
    }

    /**
     * Calculate product rate
     * @param Request $product
     * @param $quantity
     * @param PriceBook $priceBook
     * @param Request $request
     * @param bool $isApi
     * @return int
     */
    public function calculateProductRate($product, $quantity, Request $request, PriceBook $priceBook = null, $isApi = false)
    {
        $product = Product::find($product);
        if (!$product) {
            return 0;
        }

        $salesType = $request->input('sales_type');
        if ($isApi) {
            $salesType = 'Distribution';
        }

        $rate = 0;
        if ($priceBook && $salesType) {
            $price = $priceBook->prices->where('product_id', $product->id)
                ->where('range_start_from', '<=', $quantity)
                ->where('range_end_to', '>=', $quantity)
                ->first();
            if ($price) {
                return $price->price;
            }
        }
        switch ($salesType) {
            case 'Retail':
                $rate = $product->retail_price;
                break;
            case 'Wholesale':
                $rate = $product->wholesale_price;
                break;
            case 'Distribution':
                $rate = $product->distribution_price;
                break;
        }
        return $rate ? $rate : 0;
    }

    /**
     * @param $product
     * @param $quantity
     * @param Request $request
     * @return int
     */
    public function calculateProductRateForApi($product, $quantity, Request $request)
    {
        $priceBook = getPriceBooksByAllocation();
        $salesProducts = $this->salesProducts()->where('id', $product)->first();
        if ($salesProducts) {
            return $this->calculateProductRate($product, $quantity, $request, $priceBook, true);
        }
        if (!$salesProducts) {
            return 0;
        }

        return $salesProducts->distribution_price;
    }

    /**
     * @param SalesOrder $order
     * @return array
     */
    public function productItems(SalesOrder $order)
    {
        $order->load('products');
        $products = $order->products;
        $stores = Store::all();
        $unitTypes = UnitType::all();
        return $products->map(function ($productItem) use ($products, $stores, $unitTypes) {
            if (!$productItem->pivot) {
                return $productItem;
            }
            $product = $products->where('id', $productItem->pivot->product_id)->first();
            $store = $stores->where('id', $productItem->pivot->store_id)->first();
            $unitType = $unitTypes->where('id', $productItem->pivot->unit_type_id)->first();
            $pivotData = $productItem->pivot;
            $productItem->store_name = $store ? $store->name : null;
            $productItem->product_name = $product ? $product->name : null;
            $productItem->unit_type_name = $unitType ? $unitType->name : null;
            $productItem->pivot = null;
            return array_merge($productItem->toArray(), $pivotData->toArray());
        });
    }

    /**
     * @param SalesOrder $order
     * @return array
     * @throws \Exception
     */
    public function approve(SalesOrder $order): array
    {
        $order->setAttribute('approved_by', auth()->id());
        $order->setAttribute('status', 'Open');
        $order->setAttribute('approval_status', 'Approved');
        $order->save();
        return ['success' => true];
    }

    /**
     * @param SalesOrder $order
     * @return array
     * @throws \Exception
     */
    public function convert(SalesOrder $order): array
    {
        $order->setAttribute('approved_by', auth()->id());
        $order->setAttribute('status', 'Open');
        $order->setAttribute('approval_status', 'Approved');
        $order->save();
        return ['success' => true];
    }

    /**
     * @param SalesOrder $order
     * @param Request $request
     * @return SalesOrder
     */
    public function isPrinted(SalesOrder $order, Request $request)
    {
        $order->setAttribute('is_order_printed', $request->input('is_order_printed'));
        if ($order->total != $order->payments->sum('payment')) {
            $order->setAttribute('is_credit_sales', 'Yes');
        }

        if($order->getAttribute('is_sold_qty_deducted') == 'No'){
            /** update order items sold qty in daily sales items table */
            dispatch(new UpdateSoldQty('Save', $order));
        }

        $order->setAttribute('is_sold_qty_deducted', 'Yes');
        $order->save();


        return $order->refresh();
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $id = \request()->input('salesRepId');
        $userId = \request()->input('userId');
        $customerId = \request()->input('customerId');
        $productId = \request()->input('productId');
        $lastWeek = carbon()->subWeek();
        $start = \request()->input('from_date');
        $end = \request()->input('to_date');
        $dateRange = \request()->input('dateRange') ?? false;
        $orders = SalesOrder::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('order_mode', 'Customer')
            ->where('is_opining', 'No')->with('customer', 'salesRep')
            ->orderBy('id', 'desc');
        if ($search) {
            $orders->where('order_no', 'LIKE', '%' . $search . '%')
                ->orWhere('status', 'LIKE', '%' . $search . '%')
                ->orWhere('delivery_status', 'LIKE', '%' . $search . '%')
                ->orWhere('delivery_date', 'LIKE', '%' . $search . '%')
                ->orwhere(function ($query) use ($search) {
                    $query->whereHas('customer', function ($q) use ($search) {
                        $q->where('display_name', 'LIKE', '%' . $search . '%');
                    });
                });
        }
        switch ($filter) {
            case 'drafted':
                $orders->where('status', 'Draft');
                break;
            case 'scheduled':
                $orders->where('status', 'Scheduled');
                break;
            case 'approvalPending':
                $orders->where('status', 'Awaiting Approval');
                break;
            case 'open':
                $orders->where('status', 'Open');
                break;
            case 'closed':
                $orders->where('status', 'Closed');
                break;
            case 'Canceled':
                $orders->where('status', 'Canceled');
                break;
            case 'recentlyCreated':
                $orders->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $orders->where('updated_at', '>', $lastWeek);
                break;
            case 'overdue':
                $orders->where('delivery_date', '<', carbon());
                break;
            case 'partiallyInvoiced':
                $orders->where('invoice_status', 'Partially Invoiced');
                break;
            case 'fullyInvoiced':
                $orders->where('invoice_status', 'Invoiced');
                break;
            case 'today':
                $start = carbon()->toDateString();
                $end = carbon()->toDateString();
                $dateRange = true;
                break;
            case 'opening':
                $orders->where('is_opining', 'Yes');
                break;
        }
        if ($id) {
            $orders->where('rep_id', $id);
        }
        if ($userId) {
            $orders->where('prepared_by', $userId);
        }

        if ($customerId) {
            $orders->where('customer_id', $customerId);
        }
        if ($productId) {
            $orders->whereHas('products', function ($q) use ($productId) {
                $q->where('id', $productId);
            });
        }

        if ($dateRange) {
            $orders->whereBetween('order_date', [$start, $end]);
        }
        return $orders->paginate(16)->toArray();
    }

    /**
     * @return mixed
     */
    public function salesProducts()
    {
        $allocations = getRepAllocation();
        $products = getProductsFromAllocation($allocations);
        $allocatedItems = $allocations->pluck('items')->collapse();
        return $products->transform(function ($item) use ($allocatedItems) {
            $allocationItem = $allocatedItems->firstWhere('product_id', $item->id);
            $item->stock_level = $allocationItem ? $allocationItem->quantity + $allocationItem->cf_qty + $allocationItem->returned_qty : 0;
            $item->actual_stock = $allocationItem ? $allocationItem->actual_stock : 0;
            //$item->sold_stock = $allocationItem ? $allocationItem->sold_qty : 0;
            return $item;
        });
    }

    /**
     * @param SalesOrder $order
     * @param Request $request
     * @return SalesOrder
     */
    public function cancelOrder(SalesOrder $order, Request $request)
    {
        $comment = $request->input('cancel_notes_order');
        $order->setAttribute('status', 'Canceled');
        $order->save();
        createComment($request, $order, $comment);
        if ($order->invoices) {
            foreach ($order->invoices as $invoice) {
                $invoice->setAttribute('status', 'Canceled');
                $invoice->save();

                /**
                 * BEGIN
                 * update transaction record when entire invoice cancel
                 *  Transaction Type - Order Cancel
                 *      DR - Sales
                 *      CR - Account Receivable
                 */
                $debitAccount = Account::find(48); // Sales
                $creditAccount = Account::find(3); // Account Receivable
                recordTransaction($invoice, $debitAccount, $creditAccount, [
                    'date' => now()->toDateString(),
                    'type' => 'Deposit',
                    'amount' => $invoice->amount,
                    'auto_narration' => 'Invoice amount of '.number_format($invoice->amount).' is canceled ('.
                        $invoice->ref.')',
                    'manual_narration' => 'Invoice amount of '.number_format($invoice->amount).' is canceled ('.
                        $invoice->ref.')',
                    'tx_type_id' => 15,
                    'customer_id' => $invoice->customer_id,
                    'company_id' => $invoice->company_id,
                ], 'InvoiceCancel', false);

                createComment($request, $invoice, $comment);
            }
        }

        if ($order->payments) {
            foreach ($order->payments as $payment) {
                $payment->setAttribute('status', 'Canceled');
                $payment->save();
                createComment($request, $payment, $comment);

                /**
                 * if payment mode cheque and cheque in hand related
                 * Update cheque in hand status to Cancel
                 * Remove cheque hand related to transaction
                 */
                if($payment->chequeInHand && $payment->payment_mode == 'Cheque'){
                    $chequeInHand = $payment->chequeInHand;
                    $chequeInHand->status = 'Canceled';
                    $chequeInHand->save();
                }

                /** remove payment related transaction */
                $transaction = $payment->transaction;
                if($transaction){
                    $transaction->records()->delete();
                    $transaction->delete();
                }
                /** end */

                /** get sales hand over details */
                $handOver = SalesHandover::where('daily_sale_id', $order->daily_sale_id)->first();
                if($handOver){
                    if($payment->payment_mode == 'Cash'){
                        $handOver->cash_sales = ($handOver->cash_sales - $payment->payment);
                    }
                    if($payment->payment_mode == 'Cheque'){
                        $handOver->cash_sales = ($handOver->cheque_sales - $payment->payment);
                    }
                    if($payment->payment_mode == 'Direct Deposit'){
                        $handOver->cash_sales = ($handOver->deposit_sales - $payment->payment);
                    }
                    if($payment->payment_mode == 'Credit Card'){
                        $handOver->cash_sales = ($handOver->card_sales - $payment->payment);
                    }
                    $handOver->sales = ($handOver->sales - $payment->payment);
                    $handOver->total_collect = ($handOver->total_collect - $payment->payment);
                    $handOver->save();
                }
            }
        }

        /** update sold qty to allocation */
        if($order->dailySales && $order->sales_category == 'Van') {
            $orderProducts = $order->products;
            foreach ($orderProducts as $orderProduct)
            {
                /** get allocation item */
                $allocationItem = DailySaleItem::where('daily_sale_id', $order->daily_sale_id)
                    ->where('product_id', $orderProduct->id)->first();
                if($allocationItem) {
                    $allocationItem->sold_qty = ($allocationItem->sold_qty - $orderProduct->pivot->quantity);
                    $allocationItem->save();
                }
            }
        }

        return $order;
    }

    /**
     * update Location Id
     */
    protected function updateLocationId()
    {
        $locationId = $this->model->sales_location_id;
        $this->model->invoices()->update(['sales_location_id' => $locationId]);
        $this->model->payments()->update(['sales_location_id' => $locationId]);
    }

    public function creditOrders()
    {
        $request = request();

        $company = $request->input('company');
        $route = $request->input('route');
        $rep = $request->input('rep');

        $orders = SalesOrder::with('customer')->where('is_credit_sales', 'Yes')
            ->where('status', '!=', 'Canceled');

        if ($company) {
            $orders = $orders->where('company_id', $company);
        }

        if ($route) {
            $orders = $orders->where('route_id', $route);
        }

        if ($rep) {
            $orders = $orders->where('rep_id', $rep);
        }

        $orders = $orders->get()->reject(function ($order) {
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

        $paymentTotal = $orders->pluck('payments')->collapse()->sum('payment');
        $orderTotal = $orders->sum('total');

        $routeData = Route::find($route);

        $data = [];
        $data['orders'] = $orders;
        $data['order_total'] = $orderTotal;
        $data['payment_total'] = $paymentTotal;
        $data['balance'] = $orderTotal - $paymentTotal;
        $data['route'] = $routeData;
        $data['request'] = $request->toArray();
        return $data;
    }

}
