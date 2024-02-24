<?php

namespace App\Repositories\Expense;

use App\ExpenseType;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class ExpenseTypeRepository
 * @package App\Repositories\Expense
 */
class ExpenseTypeRepository extends BaseRepository
{
    /**
     * ExpenseTypeRepository constructor.
     * @param ExpenseType |null $expenseType
     */
    public function __construct(ExpenseType $expenseType = null)
    {
        $this->setModel($expenseType ?? new ExpenseType());
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
     * @param ExpenseType $expenseType
     * @return bool
     */
    public function update(Request $request, ExpenseType $expenseType)
    {
        return $this->updateItem($expenseType->id, $request->all());
    }

    /**
     * @param ExpenseType $expenseType
     * @return array
     */
    public function delete(ExpenseType $expenseType)
    {
        if ($expenseType->is_deletable == 'No'){
            return ['success' => true, 'message' => 'deleted success'];
        }
        return $this->destroy($expenseType->id);
    }
}