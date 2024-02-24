<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Report\SalesReportRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use PDF;

/**
 * Class SalesReportController
 * @package App\Http\Controllers\Report
 */
class SalesReportController extends Controller
{
    /**
     * @var SalesReportRepository
     */
    protected $report;

    /**
     * SalesReportController constructor.
     * @param SalesReportRepository $report
     */
    public function __construct(SalesReportRepository $report)
    {
        $this->report = $report;
    }

    /** Sales */
    public function salesSummary()
    {
        $breadcrumb = $this->breadcrumbs('salesSummary');
        if (request()->ajax()) {
            return response()->json($this->report->salesSummary());
        }
        return view('report.sales.sales-summary', compact('breadcrumb'));
    }

    /**
     * @return JsonResponse
     */
    public function salesSummaryList()
    {
        if (request()->ajax()) {
            $data = $this->report->salesSummaryList(request());
            return response()->json($data);
        }
    }

    /**
     * @return JsonResponse
     */
    public function salesSummaryProductList()
    {
        if (request()->ajax()) {
            $data = $this->report->salesSummaryProductList(request());
            return response()->json($data);
        }
    }

    /**
     * @return mixed
     */
    public function salesSummaryExport()
    {
        $data = $this->report->salesSummary();
        $pdf = PDF::loadView('report.sales.export.sales-summary', $data);
        return $pdf->download(env('APP_NAME') . ' - Sales Summary.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function salesByCus()
    {
        $breadcrumb = $this->breadcrumbs('salesByCus');
        if (request()->ajax()) {
            $data = $this->report->salesByCus();
            return response()->json($data);
        }
        return view('report.sales.sales-by-customer', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function salesByCusExport()
    {
        $data = $this->report->salesByCus();
        $pdf = PDF::loadView('report.sales.export.sales-by-customer', $data);
        return $pdf->download(env('APP_NAME') . ' - Sales Orders by Customer.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function salesByPro()
    {
        $breadcrumb = $this->breadcrumbs('salesByPro');
        if (request()->ajax()) {
            $data = $this->report->salesByPro();
            return response()->json($data);
        }
        return view('report.sales.sales-by-product', compact('breadcrumb'));
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function damageByPro()
    {
        $breadcrumb = $this->breadcrumbs('damageByPro');
        if (request()->ajax()) {
            $data = $this->report->damageByPro();
            return response()->json($data);
        }
        return view('report.sales.damage-by-product', compact('breadcrumb'));
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function damageByRoute()
    {
        $breadcrumb = $this->breadcrumbs('damageByRoute');
        if (request()->ajax()) {
            $data = $this->report->damageByRoute();
            return response()->json($data);
        }
        return view('report.sales.damage-by-route', compact('breadcrumb'));
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function damageByRep()
    {
        $breadcrumb = $this->breadcrumbs('damageByRep');
        if (request()->ajax()) {
            $data = $this->report->damageByRep();
            return response()->json($data);
        }
        return view('report.sales.damage-by-rep', compact('breadcrumb'));
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function damageByCustomer()
    {
        $breadcrumb = $this->breadcrumbs('damageByCustomer');
        if (request()->ajax()) {
            $data = $this->report->damageByCustomer();
            return response()->json($data);
        }
        return view('report.sales.damage-by-customer', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function salesByProExport()
    {
        $data = $this->report->salesByPro();
        $pdf = PDF::loadView('report.sales.export.sales-by-product', $data);
        return $pdf->download(env('APP_NAME') . ' - Sales Orders by Product.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function salesByProCat()
    {
        $breadcrumb = $this->breadcrumbs('salesByProCat');
        if (request()->ajax()) {
            $data = $this->report->salesByProCat();
            return response()->json($data);
        }
        return view('report.sales.sales-by-product-category', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function salesByProCatExport()
    {
        $data = $this->report->salesByProCat();
        $pdf = PDF::loadView('report.sales.export.sales-by-product-category', $data);
        return $pdf->download(env('APP_NAME') . ' - Sales Orders by Product Category.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function salesByRep()
    {
        $breadcrumb = $this->breadcrumbs('salesByRep');
        if (request()->ajax()) {
            $data = $this->report->salesByRep();
            return response()->json($data);
        }
        return view('report.sales.sales-by-rep', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function salesByRepExport()
    {
        $data = $this->report->salesByRep();
        $pdf = PDF::loadView('report.sales.export.sales-by-rep', $data);
        return $pdf->download(env('APP_NAME') . ' - Sales Orders by Rep.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function salesByRoute()
    {
        $breadcrumb = $this->breadcrumbs('salesByRoute');
        if (request()->ajax()) {
            $data = $this->report->salesByRoute();
            return response()->json($data);
        }
        return view('report.sales.sales-by-route', compact('breadcrumb'));
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function monthlySales()
    {
        $breadcrumb = $this->breadcrumbs('monthlySales');
        if (request()->ajax()) {
            $data = $this->report->monthlySales();
            return response()->json($data);
        }
        return view('report.sales.monthly-sales', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function monthlySalesExport()
    {
        $data = $this->report->monthlySales();
        $pdf = PDF::loadView('report.sales.export.monthly-sales', $data);
        return $pdf->download(env('APP_NAME') . ' - Monthly Sales.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     * Payments Received
     */
    public function paysReceived()
    {
        $breadcrumb = $this->breadcrumbs('paysReceived');
        if (request()->ajax()) {
            $data = $this->report->paysReceived();
            return response()->json($data);
        }
        return view('report.sales.payment-received', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function paysReceivedExport()
    {
        $data = $this->report->paysReceived();
        $pdf = PDF::loadView('report.sales.export.payment-received', $data);
        return $pdf->download(env('APP_NAME') . ' - Payment Received.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function creditDetails()
    {
        $breadcrumb = $this->breadcrumbs('creditDetails');
        if (request()->ajax()) {
            $data = $this->report->creditDetails();
            return response()->json($data);
        }
        return view('report.sales.credit-details', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function creditDetailsExport()
    {
        $data = $this->report->creditDetails();
        $pdf = PDF::loadView('report.sales.export.credit-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Credit Details.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     * Receivables
     */
    public function customerBalance()
    {
        $breadcrumb = $this->breadcrumbs('customerBalance');
        if (request()->ajax()) {
            $data = $this->report->customerBalance();
            return response()->json($data);
        }
        return view('report.sales.customer-balance', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function customerBalanceExport()
    {
        $data = $this->report->customerBalance();
        $pdf = PDF::loadView('report.sales.export.customer-balance', $data);
        return $pdf->download(env('APP_NAME') . ' - Customer Balance.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function agingSummary()
    {
        $breadcrumb = $this->breadcrumbs('agingSummary');
        if (request()->ajax()) {
            $data = $this->report->agingSummary();
            return response()->json($data);
        }
        return view('report.sales.aging-summary', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function agingSummaryExport()
    {
        $data = $this->report->agingSummary();
        $pdf = PDF::loadView('report.sales.export.aging-summary', $data);
        return $pdf->download(env('APP_NAME') . ' - Aging Summary.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function agingDetails()
    {
        $breadcrumb = $this->breadcrumbs('agingDetails');
        if (request()->ajax()) {
            $data = $this->report->agingDetails();
            return response()->json($data);
        }
        return view('report.sales.aging-details', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function agingDetailsExport()
    {
        $data = $this->report->agingDetails();
        $pdf = PDF::loadView('report.sales.export.aging-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Aging Details.pdf');
    }

    /**
     * @return Factory|View
     */
    public function salesDetails()
    {
        $breadcrumb = $this->breadcrumbs('salesDetails');
        return view('report.sales.sales-details', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function salesDetailsExport()
    {
        $data = $this->report->salesByPro();
        $pdf = PDF::loadView('report.sales.export.sales-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Sales Details.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function invoiceDetails()
    {
        $breadcrumb = $this->breadcrumbs('invoiceDetails');
        if (request()->ajax()) {
            $data = $this->report->invoiceDetails();
            return response()->json($data);
        }
        return view('report.sales.invoice-details', compact('breadcrumb'));
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function allocationDetails()
    {
        $breadcrumb = $this->breadcrumbs('allocationDetails');
        if (request()->ajax()) {
            $data = $this->report->allocationDetails();
            return response()->json($data);
        }
        return view('report.sales.allocation-details', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function allocationDetailsExport()
    {
        $data = $this->report->allocationDetails();
        $pdf = PDF::loadView('report.sales.export.allocation-details', $data, [
            'orientation' => 'L'
        ]);
        return $pdf->download(env('APP_NAME') . ' - Allocation Details.pdf');
    }

    /**
     * @return mixed
     */
    public function invoiceDetailsExport()
    {
        $data = $this->report->invoiceDetails();
        $pdf = PDF::loadView('report.sales.export.invoice-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Invoice Details.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function estimateDetails()
    {
        $breadcrumb = $this->breadcrumbs('estimateDetails');
        if (request()->ajax()) {
            $data = $this->report->estimateDetails();
            return response()->json($data);
        }
        return view('report.sales.estimate-details', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function estimateDetailsExport()
    {
        $data = $this->report->estimateDetails();
        $pdf = PDF::loadView('report.sales.export.estimate-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Estimate Details.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function inquiryDetails()
    {
        $breadcrumb = $this->breadcrumbs('inquiryDetails');
        if (request()->ajax()) {
            $data = $this->report->inquiryDetails();
            return response()->json($data);
        }
        return view('report.sales.inquiry-details', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function inquiryDetailsExport()
    {
        $data = $this->report->inquiryDetails();
        $pdf = PDF::loadView('report.sales.export.inquiry-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Inquiry Details.pdf');
    }

    /**
     * @return Factory|View
     */
    public function salesReturns()
    {
        $breadcrumb = $this->breadcrumbs('salesReturns');
        return view('report.sales.sales-returns', compact('breadcrumb'));
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function salesByLocation()
    {
        $breadcrumb = $this->breadcrumbs('salesByLocation');
        if (request()->ajax()) {
            $data = $this->report->salesByLocation();
            return response()->json($data);
        }
        return view('report.sales.sales-by-location', compact('breadcrumb'));
    }


    public function salesByLocationExport()
    {
        $data = $this->report->salesByLocation();
        $pdf = PDF::loadView('report.sales.export.sales-by-location', $data);
        return $pdf->download(env('APP_NAME') . ' - Sales By Sales Location.pdf');
    }

    /**
     * @param string $method
     * @return array
     */
    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'salesSummary' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales Summary'],
            ],
            'salesByCus' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales by Customer'],
            ],
            'salesByPro' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales by Product'],
            ],
            'salesByProCat' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales by Product Category'],
            ],
            'salesByRep' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales by Sales Rep'],
            ],
            'salesByRoute' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales by Route'],
            ],
            'monthlySales' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Monthly Sales'],
            ],
            'paysReceived' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Payments Received'],
            ],
            'creditDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Credit Details'],
            ],
            'customerBalance' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Customer Balances'],
            ],
            'agingSummary' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Aging Summary'],
            ],
            'agingDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Aging Details'],
            ],
            'salesDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales Order Details'],
            ],
            'invoiceDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales Invoice Details'],
            ],
            'estimateDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales Estimate Details'],
            ],
            'inquiryDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales Inquiry Details'],
            ],
            'salesReturns' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales Returns'],
            ],
            'salesByLocation' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales By Sales Location'],
            ],
            'damageByPro' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Damages by Product'],
            ],
            'damageByRoute' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Damages by Route'],
            ],
            'damageByRep' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Damages by Rep'],
            ],
            'damageByCustomer' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Damages by Customer'],
            ],
            'allocationDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Sales Allocation Details'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
