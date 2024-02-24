<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Repositories\Purchase\PurchaseRepository;
use Illuminate\Http\JsonResponse;

class PurchaseController extends Controller
{
    /**
     * @var PurchaseRepository
     */
    public $purchase;

    /**
     * PurchaseController constructor.
     * @param PurchaseRepository $purchase
     */
    public function __construct(PurchaseRepository $purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Purchase'],
        ];
        return view('purchases.index', compact('breadcrumb'));
    }

    /**
     * @param $model
     * @param null $take
     * @param null $with
     * @param null $where
     * @param null $field
     * @return JsonResponse
     */
    public function summary($model, $take = null, $with = null, $where = null, $field = null)
    {
        $data = $this->purchase->index($model, $take, $with, $where, $field);
        return response()->json($data);
    }

    /**
     * @param null $status
     * @return JsonResponse
     */
    public function getOrderCount($status = null)
    {
        $data = $this->purchase->getOrderCounts($status);
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function getBills()
    {
        $data = $this->purchase->getBills();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function getTopFiveProducts()
    {
        $data = $this->purchase->getTopFiveProducts();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function getTopFiveSupplier()
    {
        $data = $this->purchase->getTopFiveSupplier();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function yearChart()
    {
        $data = $this->purchase->yearChart();
        return response()->json($data);
    }

     /**
     * @return JsonResponse
     */
    public function monthChart()
    {
        $data = $this->purchase->monthChart();
        return response()->json($data);
    }


}
