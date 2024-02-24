<?php

namespace App\Http\Controllers\Sales;

use App\BusinessType;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\CancelRequest;
use App\Http\Requests\Sales\InvoiceStoreRequest;
use App\Http\Requests\Sales\RefundRequest;
use App\Invoice;
use App\Repositories\Sales\InvoiceRepository;
use App\SalesOrder;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;
use Illuminate\View\View;
use PDF;

class InvoiceController extends Controller
{
    /**
     * @var InvoiceRepository
     */
    protected $invoice;

    /**
     * InvoiceController constructor.
     * @param InvoiceRepository $invoice
     */
    public function __construct(InvoiceRepository $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->invoice->getModel());
        $breadcrumb = $this->invoice->breadcrumbs('index');
        $overDue = \request()->input('overdue');
        if (\request()->ajax()) {
            $invoices = $this->invoice->getInvoices();
            return response()->json($invoices);
        }
        return view('sales.invoice.index', compact('breadcrumb', 'overDue'));
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function dataTableData(Request $request)
    {
        $this->authorize('index', $this->invoice->getModel());
        if (\request()->ajax()) {
            return $this->invoice->dataTable($request);
        }
    }

    /**
     * @param SalesOrder $order
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(SalesOrder $order)
    {
        $this->authorize('create', $this->invoice->getModel());
        $breadcrumb = $this->invoice->breadcrumbs('create');
        return view('sales.invoice.create', compact('breadcrumb', 'order'));
    }

    /**
     * @param InvoiceStoreRequest $request
     * @param SalesOrder $order
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(InvoiceStoreRequest $request, SalesOrder $order)
    {
        $this->authorize('store', $this->invoice->getModel());
        $invoice = $this->invoice->save($request, $order);
        alert()->success('Invoice created successfully', 'Success')->persistent();
        return redirect()->route('sales.order.show', [$order]);
    }

    /**
     * @param Invoice $invoice
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Invoice $invoice)
    {
        $this->authorize('show', $this->invoice->getModel());
        $breadcrumb = $this->invoice->breadcrumbs('show', $invoice);
        $company = $invoice->company;
        $companyAddress = $company->addresses()->first();
        $customer = $invoice->customer ?? new Customer();
        $address = $customer->addresses()->first();
        $payments = $invoice->payments;
        $totalAmount = $invoice->amount;
        $paidAmounts = $payments->where('status', 'Paid')->sum('payment');
        $pendingAmount = $totalAmount - $paidAmounts;
        return view('sales.invoice.show',
            compact('breadcrumb', 'invoice', 'customer', 'address', 'company', 'companyAddress', 'payments', 'pendingAmount'));
    }

    /**
     * @param Invoice $invoice
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Invoice $invoice)
    {
        $this->authorize('edit', $this->invoice->getModel());
        $breadcrumb = $this->invoice->breadcrumbs('edit', $invoice);
        return view('sales.invoice.edit', compact('breadcrumb', 'invoice'));
    }

    /**
     * @param InvoiceStoreRequest $request
     * @param Invoice $invoice
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(InvoiceStoreRequest $request, Invoice $invoice)
    {
        $this->authorize('update', $this->invoice->getModel());
        $this->invoice->update($request, $invoice);
        alert()->success('Invoice updated successfully', 'Success')->persistent();
        return redirect()->route('sales.invoice.show', [$invoice]);
    }

    /**
     * @param Invoice $invoice
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Invoice $invoice): JsonResponse
    {
        $this->authorize('delete', $this->invoice->getModel());
        $response = $this->invoice->delete($invoice);
        return response()->json($response);
    }

    /**
     * @param Invoice $invoice
     * @param string $type
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(Invoice $invoice, $type = 'PDF')
    {
        $this->authorize('export', $this->invoice->getModel());
        if ($type == 'PDF') {
            $this->pdfExport($invoice);
        }
    }

    /**
     * @param Invoice $invoice
     * @return mixed
     */
    public function pdfExport(Invoice $invoice)
    {
        $company = $invoice->company;
        $companyAddress = $company->addresses()->first();
        $customer = $invoice->customer;
        $address = $customer->addresses()->first();
        $payments = $invoice->payments;
        $data = [];
        $data['invoice'] = $invoice;
        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['customer'] = $customer;
        $data['address'] = $address;
        $data['payments'] = $payments;
        $pdf = PDF::loadView('sales.invoice.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Invoice (' . $invoice->invoice_no . ')' . '.pdf');
    }

    /**
     * @param Invoice $invoice
     * @return Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printView(Invoice $invoice)
    {
        $this->authorize('printView', $this->invoice->getModel());
        $company = $invoice->company;
        $companyAddress = $company->addresses()->first();
        $customer = $invoice->customer;
        $address = $customer->addresses()->first();
        $payments = $invoice->payments;

        $breadcrumb = $this->invoice->breadcrumbs('print', $invoice);
        return view('sales.invoice.print', compact(
            'breadcrumb', 'invoice', 'company', 'companyAddress',
            'customer', 'address', 'payments'));
    }

    public function search($q = null): JsonResponse
    {
        $response = $this->invoice->search($q, 'invoice_no', ['invoice_no']);
        return response()->json($response);
    }

    public function referenceSearch($customer = null, $businessType = null, $where = [], $formatted = null, $q = null): JsonResponse
    {
        if ($customer) {
            $customer = Customer::find($customer) ?? null;
        }
        if ($businessType) {
            $businessType = BusinessType::find($businessType) ?? null;
        }
        $modal = new Invoice();
        if ($businessType && !$customer) {
            $modal = Invoice::where('business_type_id', $businessType->id);
        }
        if ($customer && !$businessType) {
            $modal = $customer->invoices();
        }
        if ($customer && $businessType) {
            $modal = $customer->invoices()->where('business_type_id', $businessType->id);
        }
        if ($where) {
            $modal = $modal->whereIn('status', json_decode($where));
        }
        if ($q == null) {
            $results = $modal->get()->toArray();
        } else {
            $results = $modal->where('invoice_no', 'LIKE', '%' . $q . '%')
                ->get()->toArray();
        }

        $results = array_map(function ($obj) use ($formatted) {
            $name = $obj['invoice_no'];
            if ($formatted) {
                $amount = $obj['amount'];
                $date = $obj['invoice_date'];
                $paids = Invoice::find($obj['id'])->payments->sum('payment');
                $name = $obj['invoice_no'] . ' (Invoice Date - ' . $date . ' | ' . 'Balance - ' . number_format($amount - $paids, 2) . ')';
            }
            return ["name" => $name, "value" => $obj['id']];
        }, $results);
        return response()->json(["success" => true, "results" => $results]);
    }

    /**
     * @param Invoice $invoice
     * @return JsonResponse
     */
    public function getInvoice(Invoice $invoice)
    {
        $paidAmount = $invoice->payments->sum('payment') ?? 0;
        $invoice->setAttribute('balance', $invoice->amount - $paidAmount);
        return response()->json($invoice->toArray());
    }

    /**
     * @param Invoice $invoice
     * @param CancelRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function cancelInvoice(Invoice $invoice, CancelRequest $request)
    {
        $this->authorize('cancel', $this->invoice->getModel());
        $this->invoice->cancelInvoice($invoice, $request);
        alert()->success('Invoice canceled successfully', 'Success')->persistent();
        return redirect()->route('sales.invoice.show', [$invoice]);
    }

    /**
     * @param Invoice $invoice
     * @param RefundRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function refundInvoice(Invoice $invoice, RefundRequest $request)
    {
        $this->authorize('refund', $this->invoice->getModel());
        $this->invoice->refundInvoice($invoice, $request);
        alert()->success('Invoice canceled successfully', 'Success')->persistent();
        return redirect()->route('sales.invoice.show', [$invoice]);
    }
}
