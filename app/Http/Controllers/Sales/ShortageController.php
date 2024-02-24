<?php

namespace App\Http\Controllers\Sales;

use App\DailySale;
use App\Http\Controllers\Controller;
use App\SalesHandoverShortage;
use Illuminate\Http\Request;
use PDF;

class ShortageController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumb = $this->breadcrumbs('index');
        if (\request()->ajax()) {
            $search = \request()->input('search');
            $allocationIds = DailySale::whereIn('company_id', userCompanyIds(loggedUser()))->pluck('id');
            $shortages = SalesHandoverShortage::whereIn('daily_sale_id', $allocationIds)
                ->orderBy('id', 'desc')
                ->with(['rep', 'handover', 'submittedBy', 'approvedBy', 'rejectedBy', 'dailySale.route']);
            if ($search) {
                $shortages->where(function ($q) use ($search) {
                    $q->orwhere(function ($query) use ($search) {
                        $query->whereHas('rep', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        });
                    })->orwhere(function ($query) use ($search) {
                        $query->whereHas('handover', function ($q) use ($search) {
                            $q->where('code', 'LIKE', '%' . $search . '%');
                        });
                    });
                });
            }
            return response()->json($shortages->paginate(20)->toArray());
        }
        return view('sales.shortage.index', compact('breadcrumb'));
    }

    public function export(SalesHandoverShortage $shortage)
    {
        $this->pdfExport($shortage);
    }

    public function pdfExport($shortage)
    {
        $company = $shortage->dailySale->company;
        $route = $shortage->dailySale->route;
        $rep = $shortage->rep;
        $dailySale = $shortage->dailySale;

        $data = [];
        $data['company'] = $company;
        $data['route'] = $route;
        $data['rep'] = $rep;
        $data['dailySale'] = $dailySale;
        $data['shortage'] = $shortage;

        $pdf = PDF::loadView('sales.shortage.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Cash Shortage (' . $route->name . ')' . '.pdf');
    }

    /**
     * @param string $method
     * @param SalesHandoverShortage|null $shortgae
     * @return array
     */
    public function breadcrumbs(string $method, SalesHandoverShortage $shortgae = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Shortages'],
            ],
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @param SalesHandoverShortage $shortage
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(SalesHandoverShortage $shortage)
    {
        $shortage->status = 'Approved';
        $shortage->approved_by = auth()->id();
        $shortage->approved_at = carbon()->now()->toDateTimeString();
        $shortage->save();
        return response()->json(['success' => true, 'shortage' => $shortage]);
    }

    /**
     * @param SalesHandoverShortage $shortage
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(SalesHandoverShortage $shortage)
    {
        $shortage->status = 'Rejected';
        $shortage->rejected_by = auth()->id();
        $shortage->rejected_at = carbon()->now()->toDateTimeString();
        $shortage->save();
        return response()->json(['success' => true, 'shortage' => $shortage]);
    }
}
