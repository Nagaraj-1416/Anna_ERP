<?php

namespace App\Repositories\Expense;

use App\Expense;
use App\ExpenseReport;
use App\ExpenseType;
use App\MileageRate;
use App\Repositories\BaseRepository;

/**
 * Class ExpenseRepository
 * @package App\Repositories\Expense
 */
class ExpenseRepository extends BaseRepository
{

    public function __construct(Expense $expense)
    {
        $this->setModel($expense ?? new Expense());
        $this->setCodePrefix('EX');
        $this->codeColumn = 'expense_no';
    }

    /**
     * @param $date
     * @return mixed
     */
    public function getMileageRate($date)
    {
        return MileageRate::whereDate('date', '<=', $date)->orderBy('date', 'desc')->first();
    }

    /**
     * @param $model
     * @param null $where
     * @return array
     */
    public function getSummary($model, $where = null)
    {
        if (!$model) return [];
        $model = app('App\\' . $model);
        if ($where == 'NRE') {
            $model = $model->whereNull('report_id');
        } else if ($where) {
            $model = $model->where('status', $where);
        }
        return ['count' => $model->count()];
    }

    public function yearChart()
    {
        $expenses = Expense::all();
        $expenses = $expenses->map(function (Expense $expense) {
            $expense->year = carbon($expense->expense_date)->year;
            return $expense;
        })->sortBy('year')->groupBy('year');
        $data = [];
        $data['datas'] = [];
        foreach ($expenses as $key => $expense) {
            $amount = $expense->sum('amount');
            array_push($data['datas'], $amount);
        }
        $data['keys'] = array_keys($expenses->toArray());
        return $data;
    }

    public function monthChart()
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
        $thisMonth = carbon()->month;
        $returnMont = [];
        for ($start = 1; $start <= $thisMonth; $start++) {
            $returnMont[$start] = $months[$start - 1];
        }

        $expenses = Expense::all();
        $expenses = $expenses->transform(function (Expense $expense) use ($returnMont) {
            $currentYear = carbon($expense->expense_date)->isCurrentYear();
            if (!$currentYear) return null;
            $expense->month = carbon($expense->expense_date)->month;
            return $expense;
        })->filter();
        $data = [];
        $data['datas'] = [];
        $data['keys'] = [];
        foreach ($returnMont as $key => $value) {
            $expensesData = $expenses->where('month', $key);
            $amount = $expensesData->sum('amount');
            array_push($data['datas'], $amount);
            array_push($data['keys'], $value);
        }
        return $data;
    }

    /**
     * @return array
     */
    public function typeChart()
    {
        $startDate = carbon()->now()->startOfYear()->toDateString();
        $endDate = carbon()->now()->endOfYear()->toDateString();

        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])->get()->groupBy('type_id');
        $data = [];
        $data['datas'] = [];
        $data['keys'] = [];
        foreach ($expenses as $key => $value) {
            $amount = $value->sum('amount');
            $key = ExpenseType::find($key)->name ?? '';
            if (!$key) $key = 'N/A';
            if ($key) {
                $data['datas'][$key] = $amount;
                array_push($data['keys'], $key);
            }

        }
        return $data;
    }

    public function topReports()
    {
        $reports = ExpenseReport::orderBy('created_at', 'desc')->take(10)->get()->toArray();
        return $reports;
    }
}