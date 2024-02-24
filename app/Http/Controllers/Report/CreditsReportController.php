<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Report\CreditsReportRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Class SalesReportController
 * @package App\Http\Controllers\Report
 */
class CreditsReportController extends Controller
{
    /**
     * @var CreditsReportRepository
     */
    protected $report;

    /**
     * CreditsReportController constructor.
     * @param CreditsReportRepository $report
     */
    public function __construct(CreditsReportRepository $report)
    {
        $this->report = $report;
    }

    /**
     * @return JsonResponse|View
     */
    public function creditByRoute()
    {
        $breadcrumb = $this->breadcrumbs('creditByRoute');
        if (request()->ajax()) {
            return response()->json($this->report->creditByRoute());
        }
        return view('report.credits.credits-by-route', compact('breadcrumb'));
    }

    /**
     * @return JsonResponse|View
     */
    public function creditByRep()
    {
        $breadcrumb = $this->breadcrumbs('creditByRep');
        if (request()->ajax()) {
            return response()->json($this->report->creditByRep());
        }
        return view('report.credits.credits-by-rep', compact('breadcrumb'));
    }

    /**
     * @return JsonResponse|View
     */
    public function creditByCustomer()
    {
        $breadcrumb = $this->breadcrumbs('creditByCustomer');
        if (request()->ajax()) {
            return response()->json($this->report->creditByCustomer());
        }
        return view('report.credits.credits-by-customer', compact('breadcrumb'));
    }

    /**
     * @return JsonResponse
     */
    public function salesList(){
        if (request()->ajax()) {
            $data = $this->report->salesList(request());
            return response()->json($data);
        }
    }

    /**
     * @return JsonResponse
     */
    public function soldItemsList()
    {
        if (request()->ajax()) {
            $data = $this->report->soldItemsList(request());
            return response()->json($data);
        }
    }

    /**
     * @param string $method
     * @return array
     */
    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'creditByRoute' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Credits by Route'],
            ],
            'creditByRep' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Credits by Rep'],
            ],
            'creditByCustomer' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Credits by Customer'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
