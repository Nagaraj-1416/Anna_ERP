<?php

namespace App\Repositories\Expense;

use App\ExpenseCategory;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class ExpenseRepository
 * @package App\Repositories\Expense
 */
class ExpenseCategoryRepository extends BaseRepository
{
    /**
     * ExpenseCategoryRepository constructor.
     * @param ExpenseCategory|null $expenseCategory
     */
    public function __construct(ExpenseCategory $expenseCategory = null)
    {
        $this->setModel($expenseCategory ?? new ExpenseCategory());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Request $request)
    {
        return $this->storeItem($request->all());
    }

    /**
     * @param Request $request
     * @param ExpenseCategory $expenseCategory
     * @return bool
     */
    public function update(Request $request, ExpenseCategory $expenseCategory){
        return $this->updateItem($expenseCategory->id, $request->all());
    }

    /**
     * @param ExpenseCategory $expenseCategory
     * @return array
     */
    public function delete(ExpenseCategory $expenseCategory){
        return $this->destroy($expenseCategory->id);
    }
}