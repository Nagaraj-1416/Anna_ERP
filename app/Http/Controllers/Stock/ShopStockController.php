<?php

namespace App\Http\Controllers\Stock;

use App\Repositories\Stock\ShopStockRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class ShopStockRepository
 * @package App\Http\Controllers\Stock
 */
class ShopStockController extends Controller
{
    /**
     * @var ShopStockRepository
     */
    public $stock;

    /**
     * ShopStockController constructor.
     * @param ShopStockRepository $stock
     */
    public function __construct(ShopStockRepository $stock)
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
        return view('stock.shop.index', compact('breadcrumb', 'query'));
    }
}
