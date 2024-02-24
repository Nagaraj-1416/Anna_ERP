<?php

namespace App\Http\Controllers\Sales;

use App\DailySale;
use App\Http\Controllers\Controller;
use App\SalesHandoverExcess;
use PDF;

class ExcessController extends Controller
{

    public function index()
    {
        $breadcrumb = $this->breadcrumbs('index');
        if (\request()->ajax()) {
            $allocationIds = DailySale::whereIn('company_id', userCompanyIds(loggedUser()))->pluck('id');
            $shortages = SalesHandoverExcess::whereIn('daily_sale_id', $allocationIds)
                ->orderby('id', 'desc')
                ->with(['dailySale', 'handover', 'rep', 'submittedBy', 'dailySale.route']);
            return response()->json($shortages->paginate(20)->toArray());
        }
        return view('sales.excess.index', compact('breadcrumb'));
    }

    public function export(SalesHandoverExcess $excess)
    {
        $this->pdfExport($excess);
    }

    public function pdfExport($excess)
    {
        $company = $excess->dailySale->company;
        $route = $excess->dailySale->route;
        $rep = $excess->rep;
        $dailySale = $excess->dailySale;

        $data = [];
        $data['company'] = $company;
        $data['route'] = $route;
        $data['rep'] = $rep;
        $data['dailySale'] = $dailySale;
        $data['excess'] = $excess;

        $pdf = PDF::loadView('sales.excess.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Cash Excess (' . $route->name . ')' . '.pdf');
    }

    /**
     * @param string $method
     * @param SalesHandoverExcess|null $excess
     * @return array
     */
    public function breadcrumbs(string $method, SalesHandoverExcess $excess = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Excess'],
            ],
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
