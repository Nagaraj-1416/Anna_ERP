<?php

namespace App\Http\Controllers\Sales;

use App\CustomerCredit;
use App\CustomerCreditRefund;
use App\Http\Requests\Sales\CreditRefundRequest;
use App\Repositories\Sales\CreditRefundRepository;
use App\Http\Controllers\Controller;
use PDF;

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
     * @param CustomerCredit $credit
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(CustomerCredit $credit)
    {
        $this->authorize('index', $this->refund->getModel());
        $refunds = $credit->refunds()->get()->toArray();
        return response()->json($refunds);
    }

    /**
     * @param CreditRefundRequest $request
     * @param CustomerCredit $credit
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function save(CreditRefundRequest $request, CustomerCredit $credit)
    {
        $this->authorize('store', $this->refund->getModel());
        $refund = $this->refund->save($request, $credit);
        return response()->json(array('success' => true, 'refund' => $refund));
    }

    /**
     * @param CustomerCredit $credit
     * @param CustomerCreditRefund $refund
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(CustomerCredit $credit, CustomerCreditRefund $refund)
    {
        $this->authorize('edit', $this->refund->getModel());
        $refund = $refund->load(['account', 'bank']);
        return response()->json(array('success' => true, 'refund' => $refund));
    }

    /**
     * @param CreditRefundRequest $request
     * @param CustomerCredit $credit
     * @param CustomerCreditRefund $refund
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CreditRefundRequest $request, CustomerCredit $credit, CustomerCreditRefund $refund)
    {
        $this->authorize('update', $this->refund->getModel());
        $refund->update($request->toArray());
        return response()->json(array('success' => true));
    }

    /**
     * @param CustomerCredit $credit
     * @param CustomerCreditRefund $refund
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(CustomerCredit $credit, CustomerCreditRefund $refund)
    {
        $this->authorize('delete', $this->refund->getModel());
        $refund->delete();
        return response()->json(array('success' => true));
    }

    /**
     * @param CustomerCredit $credit
     * @param CustomerCreditRefund $refund
     * @param string $type
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(CustomerCredit $credit, CustomerCreditRefund $refund, $type = 'PDF')
    {
        $this->authorize('export', $this->refund->getModel());
        if ($type == 'PDF') {
            $this->pdfExport($credit, $refund);
        }
    }

    /**
     * @param CustomerCredit $credit
     * @param CustomerCreditRefund $refund
     * @return mixed
     */
    public function pdfExport(CustomerCredit $credit, CustomerCreditRefund $refund)
    {
        $company = $credit->company;
        $companyAddress = $company->addresses()->first();
        $customer = $credit->customer;
        $address = $customer->addresses()->first();
        $data = [];
        $data['credit'] = $credit;
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['customer'] = $customer;
        $data['address'] = $address;
        $data['refund'] = $refund;
        $pdf = PDF::loadView('sales.credit.refund.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Customer Credit Refund Receipt' . '.pdf');
    }

    /**
     * @param CustomerCredit $credit
     * @param CustomerCreditRefund $refund
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printView(CustomerCredit $credit, CustomerCreditRefund $refund)
    {
        $this->authorize('printView', $this->refund->getModel());
        $company = $credit->company;
        $companyAddress = $company->addresses()->first();
        $customer = $credit->customer;
        $address = $customer->addresses()->first();
        $breadcrumb = $this->refund->breadcrumbs('print', $credit);
        return view('sales.credit.refund.print', compact(
            'breadcrumb', 'credit', 'company', 'companyAddress',
            'customer', 'address', 'refund'));
    }
}
