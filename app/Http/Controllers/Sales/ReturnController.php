<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Repositories\Sales\ReturnRepository;
use App\SalesReturn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
   /** @var ReturnRepository  */
    protected $return;

    /**
     * ReturnController constructor.
     * @param ReturnRepository $return
     */
    public function __construct(ReturnRepository $return)
    {
        $this->return = $return;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumb = $this->return->breadcrumbs('index');
        if (\request()->ajax()) {
            $returns = $this->return->grid();
            return response()->json($returns);
        }
        return view('sales.return.index', compact('breadcrumb'));
    }

    /**
     * @param SalesReturn $return
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(SalesReturn $return)
    {
        $breadcrumb = $this->return->breadcrumbs('show', $return);
        $items = $return->items;
        return view('sales.return.show',
            compact('breadcrumb', 'return', 'breadcrumb', 'items'));
    }

}
