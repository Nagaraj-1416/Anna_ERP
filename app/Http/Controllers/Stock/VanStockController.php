<?php

namespace App\Http\Controllers\Stock;

use App\Repositories\Stock\VanStockRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class VanStockController
 * @package App\Http\Controllers\Stock
 */
class VanStockController extends Controller
{
    /**
     * @var VanStockRepository
     */
    public $stock;

    /**
     * VanStockController constructor.
     * @param VanStockRepository $stock
     */
    public function __construct(VanStockRepository $stock)
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
        return view('stock.van.index', compact('breadcrumb'));
    }
}
