<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use App\Repositories\Expense\ExpenseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ExpenseController
 * @package App\Http\Controllers\Expense
 */
class ExpenseController extends Controller
{
    /** @var ExpenseRepository */
    protected $expense;

    /**
     * ExpenseController constructor.
     * @param ExpenseRepository $expense
     */
    public function __construct(ExpenseRepository $expense)
    {
        $this->expense = $expense;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Expense'],
        ];
        return view('expense.index', compact('breadcrumb'));
    }

    public function getMileageRate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);
        return response()->json(
            $this->expense->getMileageRate(
                $request->input('date')
            )
        );
    }

    /**
     * @param $model
     * @param null $where
     * @return JsonResponse
     */
    public function getSummary($model, $where = null)
    {
        $data = $this->expense->getSummary($model, $where);
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function yearChart()
    {
        $data = $this->expense->yearChart();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function monthChart()
    {
        $data = $this->expense->monthChart();
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function typeChart()
    {
        $data = $this->expense->typeChart();
        return response()->json($data);
    }

    public function topReports()
    {
        $data = $this->expense->topReports();
        return response()->json($data);
    }
}
