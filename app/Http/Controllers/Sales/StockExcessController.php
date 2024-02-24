<?php

namespace App\Http\Controllers\Sales;

use App\Account;
use App\Http\Controllers\Controller;
use App\Repositories\Sales\StockExcessRepository;
use App\SalesHandoverExcess;
use App\StockExcess;
use App\StockExcessItem;
use PDF;

class StockExcessController extends Controller
{
    /**
     * @var StockExcessRepository
     */
    public $stock;

    /**
     * StockExcessController constructor.
     * @param StockExcessRepository $stock
     */
    public function __construct(StockExcessRepository $stock)
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
        return view('sales.stock-excess.index', compact('breadcrumb'));
    }

    public function show(StockExcess $stock)
    {
        $breadcrumb = $this->breadcrumbs('show');
        $items = $stock->items;
        return view('sales.stock-excess.show', compact('breadcrumb', 'stock', 'items'));
    }

    public function approve(StockExcess $stock, StockExcessItem $stockItem)
    {
        $breadcrumb = $this->breadcrumbs('approve');

        /** debit account - Van Stocks Shortage  */
        $debitAccount = Account::where('prefix', 'VanGoodsShortage')
            ->where('accountable_id', $stock->getAttribute('company_id'))
            ->where('accountable_type', 'App\Company')->first();

        /** credit account - Van Goods Excess */
        $creditAccount = Account::where('prefix', 'VanGoodsExcess')
            ->where('accountable_id', $stock->getAttribute('company_id'))
            ->where('accountable_type', 'App\Company')->first();

        return view('sales.stock-excess.approve',
            compact('breadcrumb', 'stock', 'stockItem', 'debitAccount', 'creditAccount'));
    }

    public function doApprove(StockExcess $stock, StockExcessItem $stockItem)
    {
        $request = request();
        $request->validate([
            'item_qty' => 'required'
        ]);
        $this->stock->approve($request, $stock, $stockItem);
        alert()->success('Excess stock item approved successfully', 'Success')->persistent();
        return redirect()->route('sales.stock.excess.show', $stock);
    }

    public function reject(StockExcess $stock, StockExcessItem $stockItem)
    {
        $breadcrumb = $this->breadcrumbs('reject');
        return view('sales.stock-excess.reject', compact('breadcrumb', 'stock', 'stockItem'));
    }

    public function doReject(StockExcess $stock, StockExcessItem $stockItem)
    {
        $this->stock->reject($stock, $stockItem);
        alert()->success('Excess stock item rejected successfully', 'Success')->persistent();
        return redirect()->route('sales.stock.excess.show', $stock);
    }

    public function export(StockExcess $stock)
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

        $pdf = PDF::loadView('sales.stock-excess.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Stock Excess (' . $route->name . ')' . '.pdf');
    }

    /**
     * @param string $method
     * @param StockExcess|null $stock
     * @return array
     */
    public function breadcrumbs(string $method, StockExcess $stock = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Excesses'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Excesses', 'route' => 'sales.stock.excess.index'],
                ['text' => 'Details'],
            ],
            'approve' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Excesses', 'route' => 'sales.stock.excess.index'],
                ['text' => 'Details', 'route' => 'sales.stock.excess.show', 'parameters' => [$stock->id ?? null]],
                ['text' => 'Approve'],
            ],
            'reject' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Stock Excesses', 'route' => 'sales.stock.excess.index'],
                ['text' => 'Details', 'route' => 'sales.stock.excess.show', 'parameters' => [$stock->id ?? null]],
                ['text' => 'Reject'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
