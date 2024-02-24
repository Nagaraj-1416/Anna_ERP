<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Report\StockReportRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use PDF;

/**
 * Class SalesReportController
 * @package App\Http\Controllers\Report
 */
class StockReportController extends Controller
{
    /**
     * @var StockReportRepository
     */
    protected $report;

    /**
     * StockReportController constructor.
     * @param StockReportRepository $report
     */
    public function __construct(StockReportRepository $report)
    {
        $this->report = $report;
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function stockLedger()
    {
        $breadcrumb = $this->breadcrumbs('stockLedger');
        if (request()->ajax()) {
            $data = $this->report->stockLedger();
            return response()->json($data);
        }
        return view('report.stock.stock-ledger', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function stockLedgerExport()
    {
        $data = $this->report->stockLedger();
        $pdf = PDF::loadView('report.stock.export.stock-ledger', $data);
        return $pdf->download(env('APP_NAME') . ' - Stock-Ledger.pdf');
    }

    /**
     * @param string $method
     * @return array
     */
    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'stockLedger' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Stock Ledger Report'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
