<?php

namespace App\Http\Controllers\Sales;

use App\CustomerCredit;
use App\Http\Requests\Sales\CustomerCreditRequest;
use App\Repositories\Sales\CustomerCreditRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PDF;

class CustomerCreditController extends Controller
{
    protected $credit;

    /**
     * CustomerCreditController constructor.
     * @param CustomerCreditRepository $credit
     */
    public function __construct(CustomerCreditRepository $credit)
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
        return view('sales.credit.index', compact('breadcrumb'));
    }

    /**
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->credit->getModel());
        $breadcrumb = $this->credit->breadcrumbs('create');
        return view('sales.credit.create', compact('breadcrumb'));
    }

    /**
     * @param CustomerCreditRequest $request
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function save(CustomerCreditRequest $request)
    {
        $this->authorize('create', $this->credit->getModel());
        $credit = $this->credit->save($request);
        alert()->success('Customer credit created successfully', 'Success')->persistent();
        return redirect()->route('sales.credit.show', [$credit]);
    }

    /**
     * @param CustomerCredit $credit
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(CustomerCredit $credit)
    {
        $this->authorize('edit', $this->credit->getModel());
        $breadcrumb = $this->credit->breadcrumbs('edit', $credit);
        return view('sales.credit.edit', compact('breadcrumb', 'credit'));
    }

    /**
     * @param CustomerCreditRequest $request
     * @param CustomerCredit $credit
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CustomerCreditRequest $request, CustomerCredit $credit)
    {
        $this->authorize('update', $this->credit->getModel());
        $credit = $this->credit->update($request, $credit);
        alert()->success('Customer credit updated successfully', 'Success')->persistent();
        return redirect()->route('sales.credit.show', [$credit]);
    }

    /**
     * @param CustomerCredit $credit
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(CustomerCredit $credit)
    {
        $this->authorize('show', $this->credit->getModel());
        $breadcrumb = $this->credit->breadcrumbs('show', $credit);
        $company = $credit->company;
        $companyAddress = $company->addresses()->first();
        $customer = $credit->customer;
        $address = $customer->addresses()->first() ?? null;
        return view('sales.credit.show', compact('breadcrumb', 'credit', 'company', 'companyAddress', 'address', 'customer'));
    }

    /**
     * @param CustomerCredit $credit
     * @param string $type
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(CustomerCredit $credit, $type = 'PDF')
    {
        $this->authorize('export', $this->credit->getModel());
        if ($type == 'PDF') {
            $this->pdfExport($credit);
        }
    }

    public function pdfExport(CustomerCredit $credit)
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
        $pdf = PDF::loadView('sales.credit.export', $data);
        return $pdf->download(env('APP_NAME').' - Customer Credit ('.$credit->code.')'.'.pdf');
    }

    /**
     * @param CustomerCredit $credit
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printView(CustomerCredit $credit)
    {
        $this->authorize('printView', $this->credit->getModel());
        $company = $credit->company;
        $companyAddress = $company->addresses()->first();
        $customer = $credit->customer;
        $address = $customer->addresses()->first();

        $breadcrumb = $this->credit->breadcrumbs('print', $credit);
        return view('sales.credit.print', compact(
            'breadcrumb', 'credit', 'company', 'companyAddress',
            'customer', 'address'));
    }

    /**
     * @param CustomerCredit $credit
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function statusChange(CustomerCredit $credit, Request $request)
    {
        $this->authorize('statusChange', $this->credit->getModel());
        $credit->update($request->toArray());
        return response()->json(['success' => true]);
    }

    /**
     * @param CustomerCredit $credit
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function clone(CustomerCredit $credit)
    {
        $this->authorize('clone', $this->credit->getModel());
        $breadcrumb = $this->credit->breadcrumbs('clone', $credit);
        return view('sales.credit.clone', compact('breadcrumb', 'credit'));
    }

    /**
     * @param CustomerCreditRequest $request
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function copy(CustomerCreditRequest $request)
    {
        $this->authorize('copy', $this->credit->getModel());
        $credit = $this->credit->save($request);
        return redirect()->route('sales.credit.show', [$credit['id']]);
    }
}
