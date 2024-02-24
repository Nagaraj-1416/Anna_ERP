<?php

namespace App\Repositories\Settings;

use App\Designation;
use App\ExpenseCategory;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class ExpenseRepository
 * @package App\Repositories\Expense
 */
class DesignationRepository extends BaseRepository
{
    /**
     * ExpenseCategoryRepository constructor.
     * @param Designation|null $designation
     */
    public function __construct(Designation $designation = null)
    {
        $this->setModel($designation ?? new Designation());
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
     * @param Designation $designation
     * @return bool
     */
    public function update(Request $request, Designation $designation)
    {
        return $this->updateItem($designation->id, $request->all());
    }

    /**
     * @param Designation $designation
     * @return array
     */
    public function delete(Designation $designation)
    {
        return $this->destroy($designation->id);
    }
}