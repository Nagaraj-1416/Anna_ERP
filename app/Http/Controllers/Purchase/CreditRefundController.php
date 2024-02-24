<?php

namespace App\Http\Controllers\Purchase;

use App\SupplierCredit;
use App\SupplierCreditRefund;
use App\Http\Requests\Purchase\CreditRefundRequest;
use App\Repositories\Purchase\CreditRefundRepository;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use PDF;
use function Symfony\Component\Debug\Tests\testHeader;

/**
 * Class CreditRefundController
 * @package App\Http\Controllers\Sales
 */
class CreditRefundController extends Controller
{
    protected $refund;

    /**
     * CreditRefundController constructor.
     * @param CreditRefundRepository $refund
     */
    public function __construct(CreditRefundRepository $refund)
    {
        $this->refund = $refund;
    }

    /**
     * @param SupplierCredit $credit
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(SupplierCredit $credit)
    {
        $this->authorize('index', $this->refund->getModel());
        $refunds = $credit->refunds()->get()->toArray();
        return response()->json($refunds);
    }

    /**
     * @param CreditRefundRequest $request
     * @param SupplierCredit $credit
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function save(CreditRefundRequest $request, SupplierCredit $credit)
    {
        $this->authorize('store', $this->refund->getModel());
        $refund = $this->refund->save($request, $credit);
        return response()->json(array('success' => true, 'refund' => $refund));
    }

    /**
     * @param SupplierCredit $credit
     * @param SupplierCreditRefund $refund
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(SupplierCredit $credit, SupplierCreditRefund $refund)
    {
        $this->authorize('edit', $this->refund->getModel());
        $refund = $refund->load(['account', 'bank']);
        return response()->json(array('success' => true, 'refund' => $refund));
    }

    /**
     * @param CreditRefundRequest $request
     * @param SupplierCredit $credit
     * @param SupplierCreditRefund $refund
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreditRefundRequest $request, SupplierCredit $credit, SupplierCreditRefund $refund)
    {
        $this->authorize('update', $this->refund->getModel());
        $refund->update($request->toArray());
        return response()->json(array('success' => true));
    }

    /**
     * @param SupplierCredit $credit
     * @param SupplierCreditRefund $refund
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(SupplierCredit $credit, SupplierCreditRefund $refund)
    {
        $this->authorize('delete', $this->refund->getModel());
        $refund->delete();
        return response()->json(array('success' => true));
    }

    /**
     * @param SupplierCredit $credit
     * @param SupplierCreditRefund $refund
     * @param string $type
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(SupplierCredit $credit, SupplierCreditRefund $refund, $type = 'PDF')
    {
        $this->authorize('export', $this->refund->getModel());
        if ($type == 'PDF') {
            $this->pdfExport($credit, $refund);
        }
    }

    public function pdfExport(SupplierCredit $credit, SupplierCreditRefund $refund)
    {
        $company = $credit->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $credit->supplier;
        $address = $supplier->addresses()->first();
        $data = [];
        $data['credit'] = $credit;
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['supplier'] = $supplier;
        $data['address'] = $address;
        $data['refund'] = $refund;
        $pdf = PDF::loadView('purchases.credit.refund.export', $data);
        return $pdf->download($credit->code . '.pdf');
    }

    /**
     * @param SupplierCredit $credit
     * @param SupplierCreditRefund $refund
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printView(SupplierCredit $credit, SupplierCreditRefund $refund)
    {
        $this->authorize('printView', $this->refund->getModel());
        $company = $credit->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $credit->supplier;
        $address = $supplier->addresses()->first();
        $breadcrumb = $this->refund->breadcrumbs('print', $credit);
        return view('purchases.credit.refund.print', compact(
            'breadcrumb', 'credit', 'company', 'companyAddress',
            'supplier', 'address', 'refund'));
    }
}
