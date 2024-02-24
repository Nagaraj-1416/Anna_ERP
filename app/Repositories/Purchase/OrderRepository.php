<?php

namespace App\Repositories\Purchase;

use App\Brand;
use App\Http\Requests\Purchase\CancelRequest;
use App\Http\Requests\Purchase\OrderStoreRequest;
use App\Product;
use App\PurchaseOrder;
use App\PurchaseRequest;
use App\Repositories\BaseRepository;
use App\Repositories\General\DocumentRepository;
use App\Stock;
use App\StockHistory;
use App\Store;
use App\Supplier;
use Illuminate\Http\Request;

/**
 * Class SupplierRepository
 * @package App\Repositories\Settings
 */
class OrderRepository extends BaseRepository
{
    protected $document;

    /**
     * OrderRepository constructor.
     * @param PurchaseOrder|null $order
     * @param DocumentRepository $document
     */
    public function __construct(PurchaseOrder $order = null, DocumentRepository $document)
    {
        $this->document = $document;
        $this->setModel($order ?? new PurchaseOrder());
        $this->setCodePrefix('PO', 'po_no');
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['po_no', 'order_date', 'delivery_date', 'po_type', 'scheduled_date', 'terms', 'notes', 'status', 'delivery_status',
            'bill_status', 'prepared_by', 'approval_status', 'approved_by', 'supplier_id', 'business_type_id'];

        $searchingColumns = ['po_no', 'order_date', 'delivery_date', 'po_type', 'scheduled_date', 'terms', 'notes', 'status', 'delivery_status',
            'bill_status', 'prepared_by', 'approval_status', 'approved_by', 'supplier_id', 'business_type_id'];

        $relation = ['supplier' => [['as' => 'supplier_name', 'column' => 'display_name']]];

        $data = $this->getTableData($request, $columns, $searchingColumns, $relation);
        $data['data'] = array_map(function ($item) {
            $item['po_no'] = '<a href="' . route('purchase.order.show', $item['id']) . '">' . $item['po_no'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Show', null, ['purchase.order.show', [$item['id']]], ['class' => 'btn-success']);
            $item['action'] .= actionBtn('Edit', null, ['purchase.order.edit', [$item['id']]]);
            $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-po']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function save(OrderStoreRequest $request)
    {
        return $this->StoreData($request);
    }

    public function StoreData(OrderStoreRequest $request)
    {
        if (!$this->model->getAttribute('po_no')) {
            $this->model->setAttribute('po_no', $this->getCode());
        }

        $this->model->setAttribute('order_date', $request->input('order_date'));
        $this->model->setAttribute('po_type', 'Manual');
        $this->model->setAttribute('po_mode', $request->input('po_mode'));
        $this->model->setAttribute('po_for', $request->input('po_for'));
        $this->model->setAttribute('notes', $request->input('notes'));
        $this->model->setAttribute('status', 'Pending');
        $this->model->setAttribute('prepared_by', auth()->id());

        if($request->input('po_for') == 'Store'){
            $unitSupplier = Supplier::find($request->input('supplier_id'));
            if($unitSupplier && $unitSupplier->supplierable_type === 'App\ProductionUnit'){
                $this->model->setAttribute('production_unit_id', $unitSupplier->supplierable_id);
            }
        }else{
            $this->model->setAttribute('production_unit_id', $request->input('production_unit_id'));
        }

        /* map supply from */
        $supplier = Supplier::find($request->input('supplier_id'));

        /* if supplier production unit */
        if($supplier->supplierable_type === 'App\ProductionUnit'){
            $this->model->setAttribute('supply_from', 'PUnit');
        }
        /* if supplier store */
        else if($supplier->supplierable_type === 'App\Store') {
            $this->model->setAttribute('supply_from', 'Store');
            $this->model->setAttribute('supply_store_id', $supplier->supplierable_id);
        }
        /* if outside */
        else{
            $this->model->setAttribute('supply_from', 'Outside');
        }

        if($request->input('po_for') == 'PUnit'){
            /** get production store */
            $productionStore = Store::where('type', 'Production')
                ->where('storeable_id', $request->input('production_unit_id'))
                ->where('storeable_type', 'App\ProductionUnit')
                ->first();
            if($productionStore){
                $this->model->setAttribute('store_id', $productionStore->id);
            }else{
                $this->model->setAttribute('store_id', $request->input('store_id'));
            }
        }else{
            $this->model->setAttribute('store_id', $request->input('store_id'));
        }

        $this->model->setAttribute('shop_id', $request->input('shop_id'));
        $this->model->setAttribute('supplier_id', $request->input('supplier_id'));
        $this->model->setAttribute('company_id', $request->input('company_id'));
        $this->model->save();

        $products = $this->mapProducts($request);
        $this->model->products()->attach($products);

        // upload documents
        $files = $request->file('files');
        if ($files) {
            foreach ($files as $file) {
                $this->document->setDocumentable($this->model);
                $this->document->save($file);
            }
        }
        return $this->model;
    }

    /**
     * @param OrderStoreRequest $request
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function update(OrderStoreRequest $request)
    {
        $this->model->products()->detach();
        //$this->model->delete();
        return $this->StoreData($request);
    }

    /**
     * @param PurchaseOrder $order
     * @return array
     * @throws \Exception
     */
    public function delete(PurchaseOrder $order): array
    {
        $order->delete();
        return ['success' => true];
    }

    /**
     * @param PurchaseOrder $order
     * @return array
     * @throws \Exception
     */
    public function approve(PurchaseOrder $order): array
    {
        $order->setAttribute('status', 'Sent');
        $order->save();
        return ['success' => true];
    }

    /**
     * @param PurchaseOrder $order
     * @return array
     * @throws \Exception
     */
    public function convert(PurchaseOrder $order): array
    {
        $order->setAttribute('approved_by', auth()->id());
        $order->setAttribute('status', 'Sent');
        $order->save();
        return ['success' => true];
    }

    /**
     * @param string $method
     * @param PurchaseOrder|null $order
     * @return array
     */
    public function breadcrumbs(string $method, PurchaseOrder $order = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Orders'],
            ],
            'requests' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'PO Requests'],
            ],
            'request-confirm' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'PO Requests', 'route' => 'purchase.order.request'],
                ['text' => 'Request Confirm'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Orders', 'route' => 'purchase.order.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Orders', 'route' => 'purchase.order.index'],
                ['text' => $order->po_no ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Orders', 'route' => 'purchase.order.index'],
                ['text' => $order->po_no ?? ''],
                ['text' => 'Edit'],
            ],
            'clone' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Orders', 'route' => 'purchase.order.index'],
                ['text' => $order->po_no ?? ''],
                ['text' => 'Clone'],
            ],
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Orders', 'route' => 'purchase.order.index'],
                ['text' => $order->po_no ?? ''],
                ['text' => 'Print Order'],
            ],
            'confirm' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Orders', 'route' => 'purchase.order.index'],
                ['text' => $order->po_no ?? ''],
                ['text' => 'Confirm Order'],
            ],
            'bill' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Orders', 'route' => 'purchase.order.index'],
                ['text' => $order->po_no ?? ''],
                ['text' => 'Generate Bill'],
            ]
            ,
            'payment' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'Orders', 'route' => 'purchase.order.index'],
                ['text' => $order->po_no ?? ''],
                ['text' => 'Record Payment'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * Mapping product data for store
     * @param OrderStoreRequest $request
     * @return array
     */
    public function mapProducts(OrderStoreRequest $request)
    {
        $mappedProducts = [];
        $products = $request->input('product');
        $qty = $request->input('quantity');
        foreach ($products as $key => $product) {
            if (!$product) continue;
            $mappedProduct = [
                'purchase_order_id' => $this->model->id ?? null,
                'product_id' => $products[$key] ?? null,
                'production_unit_id' => $request->input('production_unit_id'),
                'store_id' => $request->input('store_id'),
                'shop_id' => $request->input('shop_id'),
                'quantity' => $qty[$key] ?? null,
                'status' => 'Pending'
            ];
            array_push($mappedProducts, $mappedProduct);
        }
        return $mappedProducts;
    }

    /**
     * Mapping product data for front end form
     * @param PurchaseOrder $order
     * @return array
     */
    public function productItems(PurchaseOrder $order)
    {
        $order->load('products');
        $products = $order->products()->wherePivot('status', 'Pending')->get();
        $stores = Store::all();
        $brands = Brand::all();
        return $products->map(function ($productItem) use ($products, $stores, $brands) {
            if (!$productItem->pivot) {
                return $productItem;
            }
            $brand = $brands->where('id', $productItem->pivot->brand_id)->first();
            $product = $products->where('id', $productItem->pivot->product_id)->first();
            $store = $stores->where('id', $productItem->pivot->store_id)->first();
            $pivotData = $productItem->pivot;
            $productItem->store_name = $store ? $store->name : null;
            $productItem->is_expirable = $product ? $product->is_expirable : null;
            $productItem->product_name = $product ? $product->name : null;
            $productItem->brand_name = $brand ? $brand->name : null;
            $productItem->pivot = null;
            return array_merge($productItem->toArray(), $pivotData->toArray());
        });
    }

    public function getRequests()
    {
        $filter = \request()->input('filter');
        $search = \request()->input('search');

        $requests = PurchaseRequest::where('status', 'Drafted');

        if(isDirectorLevelStaff() || isAccountLevelStaff()) {
            $requests = PurchaseRequest::whereIn('company_id', userCompanyIds(loggedUser()));
        }

        if(isStoreLevelStaff()){
            $requests = PurchaseRequest::whereIn('store_id', userStoreIds(loggedUser()));
        }

        $requests = $requests->with('store', 'company')->orderBy('id', 'desc');

        $lastWeek = carbon()->subWeek();
        switch ($filter) {
            case 'recentlyCreated':
                $requests->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $requests->where('updated_at', '>', $lastWeek);
                break;
        }

        if ($search) {
            $requests->where('request_no', 'LIKE', '%' . $search . '%');
        }

        return $requests->paginate(12)->toArray();
    }

    public function getOrders()
    {
        $filter = \request()->input('filter');
        $search = \request()->input('search');
        $userId = \request()->input('userId');
        $supplierId = \request()->input('supplierId');
        $productId = \request()->input('productId');

        $orders = PurchaseOrder::where('status', '!=', 'Drafted');

        if(isProductionLevelStaff()){
            $orders = PurchaseOrder::whereIn('production_unit_id', userUnitIds(loggedUser()));
        }

        if(isDirectorLevelStaff() || isAccountLevelStaff()) {
            $orders = PurchaseOrder::whereIn('company_id', userCompanyIds(loggedUser()));
        }

        if(isStoreLevelStaff()){
            $orders = PurchaseOrder::whereIn('company_id', userCompanyIds(loggedUser()))
                ->orWhereIn('supply_store_id', userStoreIds(loggedUser()));
        }

        $orders = $orders->with('supplier', 'store', 'company')
            ->orderBy('id', 'desc');

        $lastWeek = carbon()->subWeek();
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
            case 'overDue':
                $orders->where('delivery_date', '<', carbon());
                break;
            case 'partiallyBilled':
                $orders->where('bill_status', 'Partially Billed');
                break;
            case 'fullyBilled':
                $orders->where('bill_status', 'Billed');
                break;
        }

        if ($search) {
            $orders->where('po_no', 'LIKE', '%' . $search . '%')
                ->orWhere('status', 'LIKE', '%' . $search . '%')
                ->orWhere('delivery_status', 'LIKE', '%' . $search . '%')
                ->orWhere('bill_status', 'LIKE', '%' . $search . '%')
                ->orwhere(function ($query) use ($search) {
                    $query->whereHas('supplier', function ($q) use ($search) {
                        $q->where('full_name', 'LIKE', '%' . $search . '%');
                    });
                });
        }

        if ($userId) {
            $orders->where('prepared_by', $userId);
        }
        if ($supplierId) {
            $orders->where('supplier_id', $supplierId);
        }

        if ($productId) {
            $orders->whereHas('products', function ($q) use ($productId) {
                $q->where('id', $productId);
            });
        }
        return $orders->paginate(12)->toArray();
    }

    /**
     * @param PurchaseOrder $order
     * @param CancelRequest $request
     */
    public function cancelOrder(PurchaseOrder $order, CancelRequest $request)
    {
        $comment = $request->input('cancel_notes_order');
        $order->setAttribute('status', 'Canceled');
        $order->save();
        createComment($request, $order, $comment);
        if ($order->bills) {
            foreach ($order->bills as $bill) {
                $bill->setAttribute('status', 'Canceled');
                $bill->save();
                createComment($request, $bill, $comment);
            }
        }
        if ($order->payments) {
            foreach ($order->payments as $payment) {
                $payment->setAttribute('status', 'Canceled');
                $payment->save();
                createComment($request, $payment, $comment);
            }
        }
    }

    public function generatePoRequest($stocks, $store)
    {
        $order = new PurchaseOrder();
        $order->setAttribute('po_no', $this->getCode());
        $order->setAttribute('order_date', carbon()->now()->toDateString());
        $order->setAttribute('po_for', 'Store');
        $order->setAttribute('po_type', 'Auto');
        $order->setAttribute('notes', 'PO Request created by system on behalf of '.$store->name);
        $order->setAttribute('status', 'Drafted');
        $order->setAttribute('prepared_by', 1);
        $order->setAttribute('store_id', $store->id);
        $order->setAttribute('company_id', $store->company_id);
        $order->save();

        /** generate order items */
        $mapPoItems = $this->mapPoItems($stocks, $store, $order);
        $order->products()->attach($mapPoItems);
        return $order;
    }

    public function mapPoItems($items, $store, $order)
    {
        $mappedItems = [];
        foreach ($items as $key => $item) {
            if (!$item) continue;
            $mappedItem = [
                'purchase_order_id' => $order->id,
                'product_id' => $item->product_id,
                'store_id' => $store->id,
                'quantity' => $item->require_qty,
                'status' => 'Drafted'
            ];
            array_push($mappedItems, $mappedItem);
        }
        return $mappedItems;
    }

    public function generateShopPoRequest($allocation, $items, $shop)
    {
        $order = new PurchaseOrder();
        $order->setAttribute('po_no', $this->getCode());
        $order->setAttribute('order_date', carbon()->now()->toDateString());
        $order->setAttribute('po_for', 'Shop');
        $order->setAttribute('po_type', 'Auto');
        $order->setAttribute('notes', 'PO Request created by system on behalf of '.$shop->name);
        $order->setAttribute('status', 'Drafted');
        $order->setAttribute('prepared_by', 1);
        $order->setAttribute('shop_id', $shop->id);
        $order->setAttribute('company_id', $shop->company_id);
        $order->save();

        /** generate order items */
        $mapShopPoItems = $this->mapShopPoItems($items, $shop, $order);
        $order->products()->attach($mapShopPoItems);
        return $order;
    }

    public function mapShopPoItems($items, $shop, $order)
    {
        $mappedItems = [];
        foreach ($items as $key => $item) {
            if (!$item) continue;
            $mappedItem = [
                'purchase_order_id' => $order->id,
                'product_id' => $item->product_id,
                'shop_id' => $shop->id,
                'quantity' => $item->require_qty,
                'status' => 'Pending'
            ];
            array_push($mappedItems, $mappedItem);
        }
        return $mappedItems;
    }

    public function generatePOFromRequest($purchaseRequest, $items, $store)
    {
        $order = new PurchaseOrder();
        $order->setAttribute('po_no', $this->getCode());
        $order->setAttribute('order_date', carbon()->now()->toDateString());
        $order->setAttribute('po_for', $purchaseRequest->request_for);
        $order->setAttribute('po_type', $purchaseRequest->request_type);
        $order->setAttribute('notes', 'PO Request created by system on behalf of '.$store->name);
        $order->setAttribute('status', 'Pending');
        $order->setAttribute('prepared_by', 1);
        $order->setAttribute('supplier_id', $purchaseRequest->supplier_id);

        if($purchaseRequest->request_for == 'Store'){
            $unitSupplier = Supplier::where('id', $purchaseRequest->supplier_id)
                ->where('supplierable_type', 'App\ProductionUnit')
                ->first();
            if($unitSupplier){
                $order->setAttribute('supply_from', 'PUnit');
                $order->setAttribute('production_unit_id', $unitSupplier->supplierable_id);
            }
            $storeSupplier = Supplier::where('id', $purchaseRequest->supplier_id)
                ->where('supplierable_type', 'App\Store')
                ->first();
            if($storeSupplier){
                $order->setAttribute('supply_from', 'Store');
                $order->setAttribute('supply_store_id', $storeSupplier->supplierable_id);
            }
        }

        $order->setAttribute('store_id', $store->id);
        $order->setAttribute('company_id', $store->company_id);
        $order->setAttribute('purchase_request_id', $purchaseRequest->id);
        $order->save();

        /** generate order items */
        $mapPoItems = $this->mapPOItemFromRequest($items, $store, $order);
        $order->products()->attach($mapPoItems);
        return $order;

    }

    public function mapPOItemFromRequest($items, $store, $order)
    {
        $mappedItems = [];
        foreach ($items as $key => $item) {
            if (!$item) continue;
            $mappedItem = [
                'purchase_order_id' => $order->id,
                'product_id' => $item->product_id,
                'store_id' => $store->id,
                'quantity' => $item->quantity,
                'status' => 'Pending'
            ];
            array_push($mappedItems, $mappedItem);
        }
        return $mappedItems;
    }

}