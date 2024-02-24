<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Repositories\Purchase\SalesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SalesController extends Controller
{
    protected $sales;

    /**
     * SalesController constructor.
     * @param SalesRepository $sales
     */
    public function __construct(SalesRepository $sales)
    {
        $this->sales = $sales;
    }

    /**
     * @return View
     */
    public function index()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Summary'],
        ];
        return view('sales.index', compact('breadcrumb'));
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
        $data = $this->sales->summary($model, $take, $with, $where, $field);
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function orderSummary()
    {
        $data = $this->sales->orderSummary();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function invoiceSummary()
    {
        $data = $this->sales->invoiceSummary();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function settlementDue()
    {
        $data = $this->sales->settlementDue();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function topCustomers()
    {
        $data = $this->sales->topCustomersByPayment();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function topProduct()
    {
        $data = $this->sales->topProduct();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function topSalesRep()
    {
        $data = $this->sales->topSalesRep();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function yearChart()
    {
        $data = $this->sales->yearChart();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function monthChart()
    {
        $data = $this->sales->monthChart();
        return response()->json($data);
    }
}
