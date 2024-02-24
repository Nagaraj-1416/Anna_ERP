<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Report\PurchaseReportRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use PDF;

/**
 * Class PurchaseReportController
 * @package App\Http\Controllers\Report
 */
class PurchaseReportController extends Controller
{
    /**
     * @var PurchaseReportRepository
     */
    protected $report;

    /**
     * PurchaseReportController constructor.
     * @param PurchaseReportRepository $report
     */
    public function __construct(PurchaseReportRepository $report)
    {
        $this->report = $report;
    }

    /**
     * @return Factory|JsonResponse|View
     * Purchases
     */
    public function poBySup()
    {
        $breadcrumb = $this->breadcrumbs('poBySup');
        if (request()->ajax()) {
            $data = $this->report->poBySup();
            return response()->json($data);
        }
        return view('report.purchase.purchase-by-supplier', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function poBySupExport()
    {
        $data = $this->report->poBySup();
        $pdf = PDF::loadView('report.purchase.export.purchase-by-supplier', $data);
        return $pdf->download(env('APP_NAME') . ' - Purchase Orders by Suppliers.pdf');
    }

    /**
     * @return Factory|View
     */
    public function poByPro()
    {
        $breadcrumb = $this->breadcrumbs('poByPro');
        if (request()->ajax()) {
            $data = $this->report->poByPro();
            return response()->json($data);
        }
        return view('report.purchase.purchase-by-product', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function poByProExport()
    {
        $data = $this->report->poByPro();
        $pdf = PDF::loadView('report.purchase.export.purchase-by-product', $data);
        return $pdf->download(env('APP_NAME') . ' - Purchase Orders by Product.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function poByProCat()
    {
        $breadcrumb = $this->breadcrumbs('poByProCat');
        if (request()->ajax()) {
            $data = $this->report->poByProCat();
            return response()->json($data);
        }
        return view('report.purchase.purchase-by-product-category', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function poByProCatExport()
    {
        $data = $this->report->poByProCat();
        $pdf = PDF::loadView('report.purchase.export.purchase-by-product-category', $data);
        return $pdf->download(env('APP_NAME') . ' - Purchase Orders by Product category.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function monthlyPos()
    {
        $breadcrumb = $this->breadcrumbs('monthlyPos');
        if (request()->ajax()) {
            $data = $this->report->monthlyPos();
            return response()->json($data);
        }
        return view('report.purchase.monthly-purchases', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function monthlyPosExport()
    {
        $data = $this->report->monthlyPos();
        $pdf = PDF::loadView('report.purchase.export.monthly-purchases', $data);
        return $pdf->download(env('APP_NAME') . ' - Monthly Purchases.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     * Payments Made
     */
    public function paysMade()
    {
        $breadcrumb = $this->breadcrumbs('paysMade');
        if (request()->ajax()) {
            $data = $this->report->paysMade();
            return response()->json($data);
        }
        return view('report.purchase.payments-made', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function paysMadeExport()
    {
        $data = $this->report->paysMade();
        $pdf = PDF::loadView('report.purchase.export.payments-made', $data);
        return $pdf->download(env('APP_NAME') . ' - Payments Made.pdf');
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
        return view('report.purchase.credit-details', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function creditDetailsExport()
    {
        $data = $this->report->creditDetails();
        $pdf = PDF::loadView('report.purchase.export.credit-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Credit Details.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     * Payables
     */
    public function supplierBalance()
    {
        $breadcrumb = $this->breadcrumbs('supplierBalance');
        if (request()->ajax()) {
            $data = $this->report->supplierBalance();
            return response()->json($data);
        }
        return view('report.purchase.supplier-balance', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function supplierBalanceExport()
    {
        $data = $this->report->supplierBalance();
        $pdf = PDF::loadView('report.purchase.export.supplier-balance', $data);
        return $pdf->download(env('APP_NAME') . ' - Supplier Balance.pdf');
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
        return view('report.purchase.aging-summary', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function agingSummaryExport()
    {
        $data = $this->report->agingSummary();
        $pdf = PDF::loadView('report.purchase.export.aging-summary', $data);
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
        return view('report.purchase.aging-details', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function agingDetailsExport()
    {
        $data = $this->report->agingDetails();
        $pdf = PDF::loadView('report.purchase.export.aging-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Aging Details.pdf');
    }

    /**
     * @return Factory|View
     */
    public function poDetails()
    {
        $breadcrumb = $this->breadcrumbs('poDetails');
        return view('report.purchase.po-details', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function poDetailsExport()
    {
        $data = $this->report->poByPro();
        $pdf = PDF::loadView('report.purchase.export.po-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Purchase Details.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function billDetails()
    {
        $breadcrumb = $this->breadcrumbs('billDetails');
        if (request()->ajax()) {
            $data = $this->report->billDetails();
            return response()->json($data);
        }
        return view('report.purchase.bill-details', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function billDetailsExport()
    {
        $data = $this->report->billDetails();
        $pdf = PDF::loadView('report.purchase.export.bill-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Bill Details.pdf');
    }

    /**
     * @return Factory|View
     */
    public function purchaseReturns()
    {
        $breadcrumb = $this->breadcrumbs('purchaseReturns');
        return view('report.purchase.purchase-returns', compact('breadcrumb'));
    }

    /**
     * @param string $method
     * @return array
     */
    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'poBySup' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Purchase by Supplier'],
            ],
            'poByPro' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Purchase by Product'],
            ],
            'poByProCat' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Purchase by Product Category'],
            ],
            'monthlyPos' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Monthly Purchases'],
            ],
            'paysMade' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Payments Made'],
            ],
            'creditDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Credit Details'],
            ],
            'supplierBalance' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Supplier Balances'],
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
            'poDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Purchase Order Details'],
            ],
            'billDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Bill Details'],
            ],
            'purchaseReturns' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Purchase Returns'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
