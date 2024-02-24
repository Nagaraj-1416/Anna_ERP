<?php

namespace App\Http\Controllers\Purchase;

use App\Grn;
use App\GrnItem;
use App\Http\Controllers\Controller;

use App\Http\Requests\Purchase\GrnReceiveRequest;
use App\Http\Requests\Purchase\GrnStoreRequest;
use App\PurchaseOrder;
use App\Repositories\Purchase\GrnRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrnController extends Controller
{
    /**
     * @var GrnRepository
     */
    protected $grn;

    /**
     * GrnController constructor.
     * @param GrnRepository $grn
     */
    public function __construct(GrnRepository $grn)
    {
        $this->grn = $grn;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\Vie
     */
    public function index()
    {
        $breadcrumb = $this->grn->breadcrumbs('index');
        if (\request()->ajax()) {
            $grns = $this->grn->grid();
            return response()->json($grns);
        }
        return view('purchases.grn.index', compact('breadcrumb'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $breadcrumb = $this->grn->breadcrumbs('create');
        $orderId = request()->input('order');
        $order = PurchaseOrder::where('id', $orderId)->first();
        $company = $order->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $order->supplier;
        $store = $order->store;
        $productionUnit = $order->productionUnit;
        $supplierAddress = $supplier->addresses()->first();
        $items = $order->products()->wherePivot('status', 'Pending')->get();

        $items = $items->map(function ($item) use ($company, $order){
            if($order->po_for == 'Store' && $order->supply_from == 'PUnit'){
                $item->purchase_price = getItemPurchasePriceFromPUnit($company->id, $order->production_unit_id, $item);
            }else if($order->po_for == 'Store' && $order->supply_from == 'Store'){
                $item->purchase_price = getItemPurchasePriceFromStore($company->id, $order->supply_store_id, $item);
            }else{
                $item->purchase_price = $item->buying_price;
            }
            return $item;
        });

        return view('purchases.grn.create', compact('breadcrumb', 'order', 'company', 'companyAddress', 'supplier', 'supplierAddress', 'items'));
    }

    /**
     * @param GrnStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GrnStoreRequest $request)
    {
        $orderId = $request->input('purchase_order_id');
        $order = PurchaseOrder::where('id', $orderId)->first();
        $grn = $this->grn->save($request, $order);
        alert()->success('GRN created successfully', 'Success')->persistent();
        return redirect()->route('purchase.grn.show', [$grn]);
    }

    public function show(Grn $grn)
    {
        $breadcrumb = $this->grn->breadcrumbs('show', $grn);
        $company = $grn->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $grn->supplier;
        $address = $supplier->addresses()->first();
        $items = $grn->items;
        $order = $grn->purchaseOrder;
        $bills = $order->bills;
        $payments = $order->payments;
        return view('purchases.grn.show',
            compact('breadcrumb', 'grn', 'company', 'companyAddress', 'supplier', 'address', 'items', 'bills', 'payments'));
    }

    public function approve(Grn $grn)
    {
        $response = $this->grn->approve($grn);
        return response()->json($response);
    }

    public function receive(Grn $grn)
    {
        $breadcrumb = $this->grn->breadcrumbs('receive', $grn);
        $items = $grn->items;
        return view('purchases.grn.receive',
            compact('breadcrumb', 'grn', 'items'));
    }

    public function doReceive(GrnReceiveRequest $request, Grn $grn)
    {
        $this->grn->receive($request, $grn);
        alert()->success('GRN received successfully', 'Success')->persistent();
        return redirect()->route('purchase.grn.show', [$grn]);
    }

}
