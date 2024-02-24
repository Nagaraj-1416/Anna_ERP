<?php

namespace App\Http\Controllers\Api\Sales;

use App\DailySale;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Sales\AllocationAddExpenseRequest;
use App\Http\Resources\SalesExpenseResource;
use App\Repositories\Sales\SalesExpenseRepository;
use App\SalesExpense;
use Illuminate\Http\Request;

class ExpenseController extends ApiController
{
    /**
     * @var SalesExpenseRepository
     */
    protected $salesExpense;

    /**
     * ExpenseController constructor.
     * @param SalesExpenseRepository $salesExpense
     */
    public function __construct(SalesExpenseRepository $salesExpense)
    {
        $this->salesExpense = $salesExpense;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function todayIndex()
    {
        $expenses = $this->salesExpense->todayIndex();
        return SalesExpenseResource::collection($expenses);
    }

    /**
     * @param AllocationAddExpenseRequest $request
     * @return SalesExpenseResource
     */
    public function store(AllocationAddExpenseRequest $request)
    {
        $expense = $this->salesExpense->save($request);
        return new SalesExpenseResource($expense);
    }

    /**
     * @param SalesExpense $expense
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(SalesExpense $expense)
    {
        $response = $this->salesExpense->delete($expense);
        return response()->json($response);
    }

}
