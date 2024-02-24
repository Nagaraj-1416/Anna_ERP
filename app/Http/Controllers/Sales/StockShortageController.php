<?php

namespace App\Http\Controllers\Sales;

use App\Account;
use App\Http\Controllers\Controller;
use App\Repositories\Sales\StockShortageRepository;
use App\SalesHandoverExcess;
use App\StockShortage;
use App\StockShortageItem;
use PDF;

class StockShortageController extends Controller
{
    /**
     * @var StockShortageRepository
     */
    public $stock;

    /**
     * StockShortageController constructor.
     * @param StockShortageRepository $stock
     */
    public function __construct(StockShortageRepository $stock)
    {
        $this->stock = $stock;
    }

    public function index()
    {
        $breadcrumb = $this->breadcrumbs('index');
        if (\request()->ajax()) {
            $stocks = $this->stock->index();
            return response()->json($stocks);
        }
        return view('sales.stock-shortage.index', compact('breadcrumb'));
    }

    public function show(StockShortage $stock)
    {
        $breadcrumb = $this->breadcrumbs('show');
        $items = $stock->items;
        return view('sales.stock-shortage.show', compact('breadcrumb', 'stock', 'items'));
    }

    public function approve(StockShortage $stock, StockShortageItem $stockItem)
    {
        $breadcrumb = $this->breadcrumbs('approve');

        /** debit account - Rep Commission Account  */
        $debitAccount = Account::where('prefix', 'Commission')
            ->where('accountable_id', $stock->getAttribute('rep_id'))
            ->where('accountable_type', 'App\Rep')->first();

        /** credit account - Van Stocks Shortage */
        $creditAccount = Account::where('prefix', 'VanGoodsShortage')
            ->where('accountable_id', $stock->getAttribute('company_id'))
            ->where('accountable_type', 'App\Company')->first();

        return view('sales.stock-shortage.approve',
            compact('breadcrumb', 'stock', 'stockItem', 'debitAccount', 'creditAccount'));
    }

    public function doApprove(StockShortage $stock, StockShortageItem $stockItem)
    {
        $request = request();
        $request->validate([
            'item_qty' => 'required'
        ]);
        $this->stock->approve($request, $stock, $stockItem);
        alert()->success('Shortage stock item approved successfully', 'Success')->persistent();
        return redirect()->route('sales.stock.shortage.show', $stock);
    }

    public function reject(StockShortage $stock, StockShortageItem $stockItem)
    {
        $breadcrumb = $this->breadcrumbs('reject');
        return view('sales.stock-shortage.reject', compact('breadcrumb', 'stock', 'stockItem'));
    }

    public function doReject(StockShortage $stock, StockShortageItem $stockItem)
    {
        $this->stock->reject($stock, $stockItem);
        alert()->success('Shortage stock item rejected successfully', 'Success')->persistent();
        return redirect()->route('sales.stock.shortage.show', $stock);
    }

    public function export(StockShortage $stock)
    {
        $this->pdfExport($stock);
    }

    public function pdfExport($stock)
    {
        $company = $stock->company;
        $route = $stock->route;
        $rep = $stock->rep;
        $dailySale = $stock->dailySale;
        $items = $stock->items->where('status', 'Approved');

        $data = [];
        $data['items'] = $items;
        $data['company'] = $company;
        $data['route'] = $route;
        $data['rep'] = $rep;
        $data['dailySale'] = $dailySale;
        $data['stock'] = $stock;

        $pdf = PDF::loadView('sales.stock-shortage.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Stock Shortage (' . $route->name . ')' . '.pdf');
    }

    /**
     * @param string $method
     * @param StockShortage|null $stock
     * @return array
     */
    public function breadcrumbs(string $method, StockShortage $stock = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Shortages'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Shortages', 'route' => 'sales.stock.shortage.index'],
                ['text' => 'Details'],
            ],
            'approve' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Shortages', 'route' => 'sales.stock.shortage.index'],
                ['text' => 'Details', 'route' => 'sales.stock.shortage.show', 'parameters' => [$stock->id ?? null]],
                ['text' => 'Approve'],
            ],
            'reject' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Shortages', 'route' => 'sales.stock.shortage.index'],
                ['text' => 'Details', 'route' => 'sales.stock.shortage.show', 'parameters' => [$stock->id ?? null]],
                ['text' => 'Reject'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
