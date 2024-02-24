<?php

namespace App\Http\Controllers\Expense;

use App\Http\Requests\Expense\ExpenseCategoryStoreRequest;
use App\Http\Requests\Expense\ExpenseTypeStoreRequest;
use App\Repositories\Expense\ExpenseCategoryRepository;
use App\Repositories\Expense\ExpenseTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenseTypeController extends Controller
{
    /** @var ExpenseTypeRepository  */
    protected $type;

    /**
     * ExpenseCategoryController constructor.
     * @param ExpenseTypeRepository $type
     */
    public function __construct(ExpenseTypeRepository $type)
    {
        $this->type = $type;
    }

    /**
     * @param ExpenseTypeStoreRequest $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(ExpenseTypeStoreRequest $request)
    {
        return $this->type->store($request);
    }

    /**
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($q = null)
    {
        $response = $this->type->search($q, 'name', ['name'], ['is_active' => ['No']]);
        return response()->json($response);
    }
}
