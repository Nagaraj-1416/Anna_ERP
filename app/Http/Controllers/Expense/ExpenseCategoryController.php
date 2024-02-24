<?php

namespace App\Http\Controllers\Expense;

use App\Http\Requests\Expense\ExpenseCategoryStoreRequest;
use App\Repositories\Expense\ExpenseCategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExpenseCategoryController extends Controller
{
    /** @var ExpenseCategoryRepository  */
    protected $category;

    /**
     * ExpenseCategoryController constructor.
     * @param ExpenseCategoryRepository $category
     */
    public function __construct(ExpenseCategoryRepository $category)
    {
        $this->category = $category;
    }

    /**
     * @param ExpenseCategoryStoreRequest $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(ExpenseCategoryStoreRequest $request)
    {
        return $this->category->store($request);
    }

    /**
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($q = null)
    {
        $response = $this->category->search($q, 'name', ['name'], ['is_active' => ['No']]);
        return response()->json($response);
    }
}
