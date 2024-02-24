<?php

namespace App\Http\Controllers\Sales;

use App\Http\Requests\Sales\InquiryStoreRequest;
use App\Repositories\Sales\InquiryRepository;
use App\SalesInquiry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InquiryController extends Controller
{
    /** @var InquiryRepository  */
    protected $inquiry;

    /**
     * InquiryController constructor.
     * @param InquiryRepository $inquiry
     */
    public function __construct(InquiryRepository $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    /**
     * Load data for index view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->inquiry->getModel());
        $breadcrumb = $this->inquiry->breadcrumbs();
        if (\request()->ajax()){
            $inquiries = $this->inquiry->index();
            return response()->json($inquiries);
        }
        return view('sales.inquiry.index', compact('breadcrumb'));
    }

    /**
     * load create view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->inquiry->getModel());
        $breadcrumb = $this->inquiry->breadcrumbs();
        return view('sales.inquiry.create', compact('breadcrumb'));
    }

    /**
     * Store new inquiry
     * @param InquiryStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(InquiryStoreRequest $request)
    {
        $this->authorize('store', $this->inquiry->getModel());
        $inquiry = $this->inquiry->save($request);
        alert()->success('Inquiry created successfully', 'Success')->persistent();
        return redirect()->route('sales.inquiries.show', [$inquiry]);
    }

    /**
     * @param SalesInquiry $inquiry
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(SalesInquiry $inquiry)
    {
        $this->authorize('show', $this->inquiry->getModel());
        $inquiry->load('products', 'customer', 'converted');
        $breadcrumb = $this->inquiry->breadcrumbs($inquiry);
        $inquiry->setAttribute('product_items', $this->inquiry->productItems($inquiry));
        $customer = $inquiry->customer;
        $address = $customer ? $customer->addresses->first() : null;
        return view('sales.inquiry.show', compact('breadcrumb', 'inquiry', 'customer', 'address'));
    }

    /**
     * @param SalesInquiry $inquiry
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(SalesInquiry $inquiry)
    {
        $this->authorize('edit', $this->inquiry->getModel());
        $inquiry->load('products', 'customer', 'businessType');
        $breadcrumb = $this->inquiry->breadcrumbs($inquiry);
        $inquiry->setAttribute('product_items', $this->inquiry->productItems($inquiry));
        $inquiry->setAttribute('customer_name', $inquiry->customer->display_name ?? '');
        $inquiry->setAttribute('business_type_name', $inquiry->businessType->name ?? '');
        return view('sales.inquiry.edit', compact('breadcrumb', 'inquiry'));
    }

    /**
     * @param SalesInquiry $inquiry
     * @param InquiryStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(SalesInquiry $inquiry, InquiryStoreRequest $request)
    {
        $this->authorize('update', $this->inquiry->getModel());
        $this->inquiry->setModel($inquiry);
        $inquiry = $this->inquiry->update($request);
        alert()->success('Inquiry created successfully', 'Success')->persistent();
        return redirect()->route('sales.inquiries.show', [$inquiry]);
    }

    /**
     * @param SalesInquiry $inquiry
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(SalesInquiry $inquiry){
        $this->authorize('delete', $this->inquiry->getModel());
        $this->inquiry->setModel($inquiry);
        $result = $this->inquiry->delete();
        return response()->json($result);
    }
}
