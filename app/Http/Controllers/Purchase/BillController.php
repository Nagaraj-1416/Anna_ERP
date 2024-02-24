<?php

namespace App\Http\Controllers\Purchase;

use App\Bill;
use App\BusinessType;
use App\Http\Controllers\Controller;

use App\Http\Requests\Purchase\BillStoreRequest;
use App\Http\Requests\Purchase\CancelRequest;
use App\PurchaseOrder;
use App\Repositories\Purchase\BillRepository;
use App\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PDF;

class BillController extends Controller
{
    /**
     * @var BillRepository
     */
    protected $bill;

    /**
     * BillController constructor.
     * @param BillRepository $bill
     */
    public function __construct(BillRepository $bill)
    {
        $this->bill = $bill;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->bill->getModel());
        $breadcrumb = $this->bill->breadcrumbs('index');
        $overDue = request()->input('overdue');
        if (\request()->ajax()) {
            $bills = $this->bill->getBills();
            return response()->json($bills);
        }
        return view('purchases.bill.index', compact('breadcrumb', 'overDue'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()) {
            return $this->bill->dataTable($request);
        }
    }

    /**
     * @param PurchaseOrder $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(PurchaseOrder $order)
    {
        $this->authorize('create', $this->bill->getModel());
        $breadcrumb = $this->bill->breadcrumbs('create');
        return view('purchases.bill.create', compact('breadcrumb', 'order'));
    }

    /**
     * @param BillStoreRequest $request
     * @param PurchaseOrder $order
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(BillStoreRequest $request, PurchaseOrder $order)
    {
        $this->authorize('store', $this->bill->getModel());
        $this->bill->save($request, $order);
        alert()->success('Bill created successfully', 'Success')->persistent();
        return redirect()->route('purchase.order.show', [$order]);
    }

    /**
     * @param Bill $bill
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Bill $bill)
    {
        $this->authorize('show', $this->bill->getModel());
        $breadcrumb = $this->bill->breadcrumbs('show', $bill);
        $company = $bill->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $bill->supplier;
        $address = $supplier->addresses()->first();
        $payments = $bill->payments;
        $totalAmount = $bill->amount;
        $paidAmounts = $payments->where('status', 'Paid')->sum('payment');
        $pendingAmount = (float)($totalAmount - $paidAmounts);
        return view('purchases.bill.show',
            compact('breadcrumb', 'bill', 'supplier', 'address', 'company', 'companyAddress', 'payments', 'pendingAmount'));
    }

    /**
     * @param Bill $bill
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Bill $bill)
    {
        $this->authorize('edit', $this->bill->getModel());
        $breadcrumb = $this->bill->breadcrumbs('edit', $bill);
        return view('purchases.bill.edit', compact('breadcrumb', 'bill'));
    }

    /**
     * @param BillStoreRequest $request
     * @param Bill $bill
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(BillStoreRequest $request, Bill $bill)
    {
        $this->authorize('update', $this->bill->getModel());
        $this->bill->update($request, $bill);
        alert()->success('Bill updated successfully', 'Success')->persistent();
        return redirect()->route('purchase.bill.show', [$bill]);
    }

    /**
     * @param Bill $bill
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Bill $bill): JsonResponse
    {
        $this->authorize('delete', $this->bill->getModel());
        $response = $this->bill->delete($bill);
        return response()->json($response);
    }

    /**
     * @param Bill $bill
     * @param string $type
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(Bill $bill, $type = 'PDF')
    {
        $this->authorize('export', $this->bill->getModel());
        if ($type == 'PDF') {
            $this->pdfExport($bill);
        }
    }

    /**
     * @param Bill $bill
     * @return mixed
     */
    public function pdfExport(Bill $bill)
    {
        $company = $bill->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $bill->supplier;
        $address = $supplier->addresses()->first();
        $payments = $bill->payments;
        $data = [];
        $data['bill'] = $bill;
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['supplier'] = $supplier;
        $data['address'] = $address;
        $data['payments'] = $payments;
        $pdf = PDF::loadView('purchases.bill.export', $data);
        return $pdf->download($bill->bill_no . '.pdf');
    }

    /**
     * @param Bill $bill
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printView(Bill $bill)
    {
        $this->authorize('print', $this->bill->getModel());
        $company = $bill->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $bill->supplier;
        $address = $supplier->addresses()->first();
        $payments = $bill->payments;

        $breadcrumb = $this->bill->breadcrumbs('print', $bill);
        return view('purchases.bill.print', compact(
            'breadcrumb', 'bill', 'company', 'companyAddress',
            'supplier', 'address', 'payments'));
    }

    /**
     * @param null $supplier
     * @param null $businessType
     * @param array $where
     * @param null $formatted
     * @param null $q
     * @return JsonResponse
     */
    public function referenceSearch($supplier = null, $businessType = null, $where = [], $formatted = null, $q = null): JsonResponse
    {
        if ($supplier) {
            $supplier = Supplier::find($supplier) ?? null;
        }
        if ($businessType) {
            $businessType = BusinessType::find($businessType) ?? null;
        }
        $modal = new Bill();
        if ($businessType && !$supplier) {
            $modal = Bill::where('business_type_id', $businessType->id)->orWhereNull('business_type_id');
        }
        if ($supplier && !$businessType) {
            $modal = $supplier->bills();
        }
        if ($supplier && $businessType) {
            $modal = $supplier->bills()->where(function ($query) use ($businessType) {
                $query->where('business_type_id', $businessType->id)->orWhereNull('business_type_id');
            });
        }
        if ($where) {
            $modal = $modal->whereIn('status', json_decode($where));
        }
        if ($q == null) {
            $results = $modal->get()->toArray();
        } else {
            $results = $modal->where('bill_no', 'LIKE', '%' . $q . '%')
                ->get()->toArray();
        }
        $results = array_map(function ($obj) use ($formatted) {
            $name = $obj['bill_no'];
            if ($formatted) {
                $amount = $obj['amount'];
                $date = $obj['bill_date'];
                $paids = Bill::find($obj['id'])->payments->sum('payment');
                $name = $obj['bill_no'] . ' (Bill Date - ' . $date . ' | ' . 'Balance - ' . number_format($amount - $paids, 2) . ')';
            }
            return ["name" => $name, "value" => $obj['id']];
        }, $results);
        return response()->json(["success" => true, "results" => $results]);
    }

    /**
     * @param Bill $bill
     * @return JsonResponse
     */
    public function getBill(Bill $bill)
    {
        $paidAmount = $bill->payments->sum('payment') ?? 0;
        $bill->setAttribute('balance', $bill->amount - $paidAmount);
        return response()->json($bill->toArray());
    }

    /**
     * @param Bill $bill
     * @param CancelRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function cancelBill(Bill $bill, CancelRequest $request)
    {
        $this->authorize('cancelBill', $this->bill->getModel());
        $this->bill->cancelBill($bill, $request);
        alert()->success('Bill canceled successfully', 'Success')->persistent();
        return redirect()->route('purchase.bill.show', [$bill]);
    }
}
