<?php

namespace App\Http\Controllers\Sales;

use App\Estimate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\EstimateStoreRequest;
use App\Repositories\Sales\EstimateRepository;
use App\Repositories\Sales\InquiryRepository;
use App\SalesInquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PDF;

class EstimateController extends Controller
{
   /** @var EstimateRepository  */
    protected $estimate;

    /** @var InquiryRepository  */
    protected $inquiry;

    /**
     * EstimateController constructor.
     * @param EstimateRepository $estimate
     * @param InquiryRepository $inquiry
     */
    public function __construct(EstimateRepository $estimate, InquiryRepository $inquiry)
    {
        $this->estimate = $estimate;
        $this->inquiry = $inquiry;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->estimate->getModel());
        $breadcrumb = $this->estimate->breadcrumbs('index');
        if (\request()->ajax()) {
            $estimates = $this->estimate->getEstimations();
            return response()->json($estimates);
        }
        return view('sales.estimate.index', compact('breadcrumb'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->estimate->getModel());
        $breadcrumb = $this->estimate->breadcrumbs('create');
        if (\request()->input('inquiry')){
            /** @var SalesInquiry $inquiry */
            $inquiry = SalesInquiry::find(request()->input('inquiry'));
            if($inquiry){
                $inquiry->load('products');
                $inquiry->setAttribute('product_items', $this->inquiry->productItems($inquiry));
                $inquiry->setAttribute('customer_name', $inquiry->customer->display_name ?? '');
                $inquiry->setAttribute('business_type_name', $inquiry->businessType->name ?? '');
                $inquiry->setAttribute('sales_type', 'Retail');
            }
        }
        return view('sales.estimate.create', compact('breadcrumb', 'inquiry'));
    }

    /**
     * @param EstimateStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(EstimateStoreRequest $request)
    {
        $this->authorize('store', $this->estimate->getModel());
        $estimate = $this->estimate->save($request);
        alert()->success('Sales estimate created successfully', 'Success')->persistent();
        return redirect()->route('sales.estimate.show', [$estimate]);
    }

    /**
     * @param Estimate $estimate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Estimate $estimate)
    {
        $this->authorize('show', $this->estimate->getModel());
        $breadcrumb = $this->estimate->breadcrumbs('show', $estimate);
        $company = $estimate->company;
        $companyAddress = $company->addresses()->first();
        $customer = $estimate->customer;
        $address = $customer->addresses()->first();
        $items = $estimate->products;
        return view('sales.estimate.show',
            compact('breadcrumb', 'estimate', 'customer', 'address', 'items', 'company', 'companyAddress'));
    }

    /**
     * @param Estimate $estimate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Estimate $estimate)
    {
        $this->authorize('edit', $this->estimate->getModel());
        $estimate->load('businessType', 'customer', 'rep');
        if ($estimate->businessType) {
            $estimate->setAttribute('business_type_name', $estimate->businessType->name);
        }
        if ($estimate->customer) {
            $estimate->setAttribute('customer_name', $estimate->customer->display_name);
        }
        if ($estimate->rep) {
            $estimate->setAttribute('rep_name', $estimate->rep->name);
        }
        $estimate->setAttribute('product_items', $this->estimate->productItems($estimate));
        $breadcrumb = $this->estimate->breadcrumbs('edit', $estimate);
        return view('sales.estimate.edit', compact('breadcrumb', 'estimate', 'productItems'));
    }

    /**
     * @param EstimateStoreRequest $request
     * @param Estimate $estimate
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(EstimateStoreRequest $request, Estimate $estimate)
    {
        $this->authorize('update', $this->estimate->getModel());
        $this->estimate->setModel($estimate);
        $this->estimate->update($request);
        alert()->success('Sales estimate updated successfully', 'Success')->persistent();
        return redirect()->route('sales.estimate.show', [$estimate]);
    }

    /**
     * @param Estimate $estimate
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Estimate $estimate): JsonResponse
    {
        $this->authorize('delete', $this->estimate->getModel());
        $response = $this->estimate->delete($estimate);
        return response()->json($response);
    }

    /**
     * @param Estimate $estimate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function clone(Estimate $estimate)
    {
        $this->authorize('clone', $this->estimate->getModel());
        $estimate->load('businessType', 'customer');
        if ($estimate->businessType) {
            $estimate->setAttribute('business_type_name', $estimate->businessType->name);
        }
        if ($estimate->customer) {
            $estimate->setAttribute('customer_name', $estimate->customer->display_name);
        }
        if ($estimate->salesRep) {
            $estimate->setAttribute('rep_name', $estimate->salesRep->name);
        }
        if ($estimate->priceBook) {
            $estimate->setAttribute('price_book_name', $estimate->priceBook->name);
        }
        $estimate->setAttribute('product_items', $this->estimate->productItems($estimate));
        $breadcrumb = $this->estimate->breadcrumbs('clone', $estimate);
        return view('sales.estimate.clone', compact('breadcrumb', 'estimate', 'productItems'));
    }

    /**
     * @param EstimateStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function copy(EstimateStoreRequest $request)
    {
        $this->authorize('copy', $this->estimate->getModel());
        $estimate = $this->estimate->save($request);
        alert()->success('Sales estimate cloned successfully', 'Success')->persistent();
        return redirect()->route('sales.estimate.show', [$estimate]);
    }

    /**
     * @param Estimate $estimate
     * @param string $type
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(Estimate $estimate, $type = 'PDF')
    {
        $this->authorize('export', $this->estimate->getModel());
        if ($type == 'PDF') {
            $this->pdfExport($estimate);
        }
    }

    /**
     * @param Estimate $estimate
     * @return mixed
     */
    public function pdfExport(Estimate $estimate)
    {
        $company = $estimate->company;
        $companyAddress = $company->addresses()->first();
        $customer = $estimate->customer;
        $address = $customer->addresses()->first();
        $items = $estimate->products;

        $data = [];
        $data['estimate'] = $estimate;
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['customer'] = $customer;
        $data['address'] = $address;
        $data['items'] = $items;
        $pdf = PDF::loadView('sales.estimate.export', $data);
        return $pdf->download(env('APP_NAME').' - Estimate ('.$estimate->estimate_no.')'.'.pdf');
    }

    /**
     * @param Estimate $estimate
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function send(Estimate $estimate): JsonResponse
    {
        $this->authorize('send', $this->estimate->getModel());
        $response = $this->estimate->send($estimate);
        return response()->json($response);
    }

    /**
     * @param Estimate $estimate
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function accept(Estimate $estimate): JsonResponse
    {
        $this->authorize('accept', $this->estimate->getModel());
        $response = $this->estimate->accept($estimate);
        return response()->json($response);
    }

    /**
     * @param Estimate $estimate
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function decline(Estimate $estimate): JsonResponse
    {
        $this->authorize('decline', $this->estimate->getModel());
        $response = $this->estimate->decline($estimate);
        return response()->json($response);
    }

    /**
     * @param Estimate $estimate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printView(Estimate $estimate)
    {
        $this->authorize('printView', $this->estimate->getModel());
        $company = $estimate->company;
        $companyAddress = $company->addresses()->first();
        $customer = $estimate->customer;
        $address = $customer->addresses()->first();
        $items = $estimate->products;

        $breadcrumb = $this->estimate->breadcrumbs('print', $estimate);
        return view('sales.estimate.print', compact(
            'breadcrumb', 'estimate', 'company', 'companyAddress',
            'customer', 'address', 'items'));
    }
}
