<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Report\FinanceReportRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use PDF;

/**
 * Class SalesReportController
 * @package App\Http\Controllers\Report
 */
class FinanceReportController extends Controller
{
    /**
     * @var FinanceReportRepository
     */
    protected $report;

    /**
     * StockReportController constructor.
     * @param FinanceReportRepository $report
     */
    public function __construct(FinanceReportRepository $report)
    {
        $this->report = $report;
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function customerLedger()
    {
        $breadcrumb = $this->breadcrumbs('customer-ledger');
        if (request()->ajax()) {
            $data = $this->report->customerLedger();
            return response()->json($data);
        }
        return view('report.finance.customer-ledger', compact('breadcrumb'));
    }

    public function transfers()
    {
        $breadcrumb = $this->breadcrumbs('transfer');
        if (request()->ajax()) {
            $data = $this->report->transfers();
            return response()->json($data);
        }
        return view('report.finance.transfers', compact('breadcrumb'));
    }

    /**
     * @param string $method
     * @return array
     */
    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'customer-ledger' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Customer Ledger Report'],
            ],
            'transfer' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Customer Ledger Report'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
