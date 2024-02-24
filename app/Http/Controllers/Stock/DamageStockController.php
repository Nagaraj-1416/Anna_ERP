<?php

namespace App\Http\Controllers\Stock;

use App\Repositories\Stock\DamageStockRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class DamageStockController
 * @package App\Http\Controllers\Stock
 */
class DamageStockController extends Controller
{
    /**
     * @var DamageStockRepository
     */
    public $stock;

    /**
     * DamageStockController constructor.
     * @param DamageStockRepository $stock
     */
    public function __construct(DamageStockRepository $stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        if (\request()->ajax()) {
            $stocks = $this->stock->index();
            return response()->json($stocks);
        }
        $breadcrumb = $this->stock->breadcrumbs('index');
        return view('stock.damaged.index', compact('breadcrumb'));
    }
}
