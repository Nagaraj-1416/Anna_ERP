<?php

namespace App\Http\Controllers\Purchase;

use App\SupplierCredit;
use App\Http\Requests\Purchase\SupplierCreditRequest;
use App\Repositories\Purchase\SupplierCreditRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PDF;
use function Symfony\Component\Debug\Tests\testHeader;

class SupplierCreditController extends Controller
{
    protected $credit;

    /**
     * SupplierCreditController constructor.
     * @param SupplierCreditRepository $credit
     */
    public function __construct(SupplierCreditRepository $credit)
    {
        $this->credit = $credit;
    }

    /**
     * @return Factory|JsonResponse|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->credit->getModel());
        $breadcrumb = $this->credit->breadcrumbs('index');
        if (\request()->ajax()) {
            $credits = $this->credit->index();
            return response()->json($credits);
        }
        return view('purchases.credit.index', compact('breadcrumb'));
    }

    /**
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->credit->getModel());
        $breadcrumb = $this->credit->breadcrumbs('create');
        return view('purchases.credit.create', compact('breadcrumb'));
    }

    /**
     * @param SupplierCreditRequest $request
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function save(SupplierCreditRequest $request)
    {
        $this->authorize('store', $this->credit->getModel());
        $credit = $this->credit->save($request);
        alert()->success('Supplier credit created successfully', 'Success')->persistent();
        return redirect()->route('purchase.credit.index');
    }

    /**
     * @param SupplierCredit $credit
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(SupplierCredit $credit)
    {
        $this->authorize('edit', $this->credit->getModel());
        $breadcrumb = $this->credit->breadcrumbs('edit', $credit);
        return view('purchases.credit.edit', compact('breadcrumb', 'credit'));
    }

    /**
     * @param SupplierCreditRequest $request
     * @param SupplierCredit $credit
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(SupplierCreditRequest $request, SupplierCredit $credit)
    {
        $this->authorize('update', $this->credit->getModel());
        $credit = $this->credit->update($request, $credit);
        alert()->success('Supplier credit updated successfully', 'Success')->persistent();
        return redirect()->route('purchase.credit.index');
    }

    /**
     * @param SupplierCredit $credit
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(SupplierCredit $credit)
    {
        $this->authorize('show', $this->credit->getModel());
        $breadcrumb = $this->credit->breadcrumbs('show', $credit);
        $company = $credit->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $credit->supplier;
        $address = $supplier->addresses()->first() ?? null;
        return view('purchases.credit.show', compact('breadcrumb', 'credit', 'company', 'companyAddress', 'address', 'supplier'));
    }

    /**
     * @param SupplierCredit $credit
     * @param string $type
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(SupplierCredit $credit, $type = 'PDF')
    {
        $this->authorize('export', $this->credit->getModel());
        if ($type == 'PDF') {
            $this->pdfExport($credit);
        }
    }

    public function pdfExport(SupplierCredit $credit)
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
        $pdf = PDF::loadView('purchases.credit.export', $data);
        return $pdf->download($credit->code . '.pdf');
    }

    /**
     * @param SupplierCredit $credit
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printView(SupplierCredit $credit)
    {
        $this->authorize('printView', $this->credit->getModel());
        $company = $credit->company;
        $companyAddress = $company->addresses()->first();
        $supplier = $credit->supplier;
        $address = $supplier->addresses()->first();

        $breadcrumb = $this->credit->breadcrumbs('print', $credit);
        return view('purchases.credit.print', compact(
            'breadcrumb', 'credit', 'company', 'companyAddress',
            'supplier', 'address'));
    }

    /**
     * @param SupplierCredit $credit
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function statusChange(SupplierCredit $credit, Request $request)
    {
        $this->authorize('statusChange', $this->credit->getModel());
        $credit->update($request->toArray());
        return response()->json(['success' => true]);
    }

    /**
     * @param SupplierCredit $credit
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function clone(SupplierCredit $credit)
    {
        $this->authorize('clone', $this->credit->getModel());
        $breadcrumb = $this->credit->breadcrumbs('clone', $credit);
        return view('purchases.credit.clone', compact('breadcrumb', 'credit'));
    }

    /**
     * @param SupplierCreditRequest $request
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function copy(SupplierCreditRequest $request)
    {
        $this->authorize('copy', $this->credit->getModel());
        $credit = $this->credit->save($request);
        return redirect()->route('purchase.credit.show', [$credit['id']]);
    }
}
