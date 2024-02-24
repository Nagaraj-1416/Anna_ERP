<?php

namespace App\Http\Controllers\Purchase;

use App\Bill;
use App\Http\Controllers\Controller;
use App\PurchaseReturn;
use App\Repositories\Purchase\ReturnRepository;
use App\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PDF;

class ReturnController extends Controller
{
    /**
     * @var ReturnRepository
     */
    protected $return;

    /**
     * ReturnController constructor.
     * @param ReturnRepository $return
     */
    public function __construct(ReturnRepository $return)
    {
        $this->return = $return;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $breadcrumb = $this->return->breadcrumbs('index');
        if (\request()->ajax()) {
            $returns = $this->return->getReturns();
            return response()->json($returns);
        }
        return view('purchases.return.index', compact('breadcrumb'));
    }

    /**
     * @param PurchaseReturn $return
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(PurchaseReturn $return)
    {
        $breadcrumb = $this->return->breadcrumbs('show');
        $items = $return->items()->with('product')->get();
        return view('purchases.return.show',
            compact('breadcrumb', 'return', 'items'));
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

}
