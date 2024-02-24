<?php

namespace App\Repositories\Purchase;

use App\PurchaseOrder;
use App\PurchaseRequest;
use App\PurchaseRequestItem;
use App\Repositories\BaseRepository;
use App\Repositories\General\DocumentRepository;

/**
 * Class SupplierRepository
 * @package App\Repositories\Settings
 */
class RequestRepository extends BaseRepository
{
    protected $document;

    /**
     * RequestRepository constructor.
     * @param PurchaseRequest|null $request
     * @param DocumentRepository $document
     */
    public function __construct(PurchaseRequest $request = null, DocumentRepository $document)
    {
        $this->document = $document;
        $this->setModel($request ?? new PurchaseRequest());
        $this->setCodePrefix('PR', 'request_no');
    }

    public function generatePoRequest($stocks, $store)
    {
        $request = new PurchaseRequest();
        $request->setAttribute('request_no', $this->getCode());
        $request->setAttribute('request_date', carbon()->now()->toDateString());
        $request->setAttribute('request_type', 'Auto');
        $request->setAttribute('request_mode', 'Internal');
        $request->setAttribute('request_for', 'Store');
        $request->setAttribute('notes', 'Purchase request created by system on behalf of '.$store->name);
        $request->setAttribute('status', 'Drafted');
        $request->setAttribute('prepared_by', 1);
        $request->setAttribute('store_id', $store->id);
        $request->setAttribute('company_id', $store->company_id);
        $request->save();

        /** generate request items */
        $mapRequestItems = $this->mapRequestItems($stocks, $store, $request);
        foreach ($mapRequestItems as $mapRequestItem){
            $requestItem = new PurchaseRequestItem();
            $requestItem->setAttribute('purchase_request_id', $mapRequestItem['purchase_request_id']);
            $requestItem->setAttribute('product_id', $mapRequestItem['product_id']);
            $requestItem->setAttribute('store_id', $mapRequestItem['store_id']);
            $requestItem->setAttribute('quantity', $mapRequestItem['quantity']);
            $requestItem->setAttribute('status', $mapRequestItem['status']);
            $requestItem->save();
        }
        return $request;
    }

    public function mapRequestItems($items, $store, $request)
    {
        $mappedItems = [];
        foreach ($items as $key => $item) {
            if (!$item) continue;
            $mappedItem = [
                'purchase_request_id' => $request->id,
                'product_id' => $item->product_id,
                'store_id' => $store->id,
                'quantity' => $item->require_qty,
                'status' => 'Pending'
            ];
            array_push($mappedItems, $mappedItem);
        }
        return $mappedItems;
    }

    /**
     * @param string $method
     * @return array
     */
    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'request-confirm' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Purchase', 'route' => 'purchase.index'],
                ['text' => 'PO Requests', 'route' => 'purchase.order.request'],
                ['text' => 'Request Confirm'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}