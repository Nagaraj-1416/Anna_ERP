<?php

namespace App\Http\Controllers\Stock;

use App\Repositories\Stock\StoreStockRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

/**
 * Class StoreStockRepository
 * @package App\Http\Controllers\Stock
 */
class StoreStockController extends Controller
{
    /**
     * @var StoreStockRepository
     */
    public $stock;

    /**
     * StoreStockController constructor.
     * @param StoreStockRepository $stock
     */
    public function __construct(StoreStockRepository $stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = $this->stock->index();
            return response()->json($data);
        }
        $breadcrumb = $this->stock->breadcrumbs('index');
        return view('stock.store.index', compact('breadcrumb'));
    }

    public function export()
    {
        $data = $this->stock->index();
        $pdf = PDF::loadView('stock.store._inc.store-stocks', $data);
        return $pdf->download(env('APP_NAME') . '-Store-Stocks-Details.pdf');
    }

}
