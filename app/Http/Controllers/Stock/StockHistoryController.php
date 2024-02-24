<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Repositories\Stock\StockHistoryRepository;

class StockHistoryController extends Controller
{
    /**
     * @var StockHistoryRepository
     */
    protected $stockHistory;

    /**
     * StockHistoryController constructor.
     * @param StockHistoryRepository $stock
     */
    public function __construct(StockHistoryRepository $stock)
    {
        $this->stockHistory = $stock;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumb = $this->stockHistory->breadcrumbs('index');
        return view('stock.history.index', compact('breadcrumb'));
    }

    /**
     * @return StockHistoryRepository|\Illuminate\Support\Collection
     */
    public function historyData()
    {
        if (\request()->ajax()){
            return $this->stockHistory->index();
        }
    }
}
