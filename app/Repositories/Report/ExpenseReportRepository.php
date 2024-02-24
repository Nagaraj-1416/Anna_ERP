<?php

namespace App\Repositories\Report;

use App\Expense;
use App\ExpenseReport;
use App\Rep;
use App\Repositories\BaseRepository;

/**
 * Class ExpenseReportRepository
 * @package App\Repositories\Report
 */
class ExpenseReportRepository extends BaseRepository
{
    /**
     * ExpenseReportRepository constructor.
     */
    public function __construct()
    {
        $this->setModel(new Expense());
    }

    /**
     * @return array
     */
    public function expenseDetails()
    {
        $request = request();
        $companyId = $request->input('company');
        $typeId = $request->input('type');
        $expenses = $this->getExpenses($request)->with(
            [
                'type',
                'salesExpense',
                'salesExpense.dailySale',
                'salesExpense.dailySale.route',
                'salesExpense.dailySale.rep'
            ]
        );

        if ($companyId) {
            $expenses->where('company_id', $companyId);
        }

        if ($typeId) {
            $expenses->where('type_id', $typeId);
        }

        $expenses = $expenses->where('expense_category', 'Van')->get();

        $data = [];
        $data['expenses'] = $expenses;
        $data['total'] = $expenses->sum('amount');
        $data['request'] = $request->toArray();

        return $data;
    }

    public function expenseByType()
    {
        $request = request();
        $typeId = $request->input('type');
        $expenses = $this->getExpenses($request)->with(
            [
                'company',
                'salesExpense',
                'salesExpense.dailySale',
                'salesExpense.dailySale.route',
                'salesExpense.dailySale.rep'
            ]
        );

        if ($typeId) {
            $expenses->where('type_id', $typeId);
        }
        $expenses = $expenses->get();

        $data = [];
        $data['expenses'] = $expenses;
        $data['total'] = $expenses->sum('amount');
        $data['request'] = $request->toArray();

        return $data;
    }

    public function expenseByRep()
    {
        $request = request();
        $repId = $request->input('rep');
        $typeId = $request->input('type');
        $rep = Rep::find($repId);
        if(!$rep) return;
        $staff = $rep->staff;
        $user = $staff->user;

        $expenses = $this->getExpenses($request)->with(
            [
                'type',
                'company',
                'salesExpense',
                'salesExpense.dailySale',
                'salesExpense.dailySale.route',
                'salesExpense.dailySale.rep'
            ]
        );

        if ($user) {
            $expenses->where('prepared_by', $user->id);
        }

        if ($typeId) {
            $expenses->where('type_id', $typeId);
        }

        $expenses = $expenses->where('expense_category', 'Van')->get();

        $data = [];
        $data['expenses'] = $expenses;
        $data['total'] = $expenses->sum('amount');
        $data['request'] = $request->toArray();

        return $data;
    }

    /**
     * @return array
     */
    public function officeExpByCompany()
    {
        $request = request();
        $companyId = $request->input('company');
        $typeId = $request->input('type');
        $expenses = $this->getExpenses($request)->with(
            [
                'type',
                'salesExpense',
                'salesExpense.dailySale',
                'salesExpense.dailySale.route',
                'salesExpense.dailySale.rep'
            ]
        );

        if ($companyId) {
            $expenses->where('company_id', $companyId);
        }

        if ($typeId) {
            $expenses->where('type_id', $typeId);
        }

        $expenses = $expenses->where('expense_category', 'Office')->get();

        $data = [];
        $data['expenses'] = $expenses;
        $data['total'] = $expenses->sum('amount');
        $data['request'] = $request->toArray();

        return $data;
    }

    /**
     * @return array
     */
    public function expenseByCat()
    {
        $request = request();
        $data = [];
        $category = $request->input('category');
        $expenses = $this->getExpenses($request)->with(['customer', 'supplier']);
        if ($category) {
            $expenses->where('category_id', $category);
        }
        $expenses = $expenses->get();
        $data['expenses'] = $expenses->groupBy('category_id');
        $data['total'] = $expenses->sum('amount');
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function expenseByCus()
    {
        $request = request();
        $data = [];
        $category = $request->input('customer');
        $expenses = $this->getExpenses($request)->with(['customer', 'supplier', 'category'])->whereNotNull('customer_id');
        if ($category) {
            $expenses->where('customer_id', $category);
        }
        $expenses = $expenses->get();
        $data['expenses'] = $expenses->groupBy('customer_id');
        $data['total'] = $expenses->sum('amount');
        $data['request'] = $request->toArray();
        return $data;
    }

    public function expenseBySup()
    {
        $request = request();
        $data = [];
        $supplier = $request->input('supplier');
        $expenses = $this->getExpenses($request)->with(['customer', 'supplier', 'category'])->whereNotNull('supplier_id');
        if ($supplier) {
            $expenses->where('supplier_id', $supplier);
        }
        $expenses = $expenses->get();
        $data['expenses'] = $expenses->groupBy('supplier_id');
        $data['total'] = $expenses->sum('amount');
        $data['request'] = $request->toArray();
        return $data;
    }

    public function mileageExpByEmp()
    {
        $request = request();
        $data = [];
        $supplier = $request->input('staff');
        $expenses = $this->getExpenses($request)->where('expense_type', 'Mileage')->with(['customer', 'supplier', 'category'])->whereNotNull('staff_id');
        if ($supplier) {
            $expenses->where('staff_id', $supplier);
        }
        $expenses = $expenses->get();
        $data['expenses'] = $expenses->groupBy('staff_id');
        $data['total'] = $expenses->sum('amount');
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @return array
     */
    public function expReportDetails()
    {
        $request = request();
        $this->setModel(new ExpenseReport());
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $businessType = $request->input('type');
        $reports = $this->model->where(function ($query) use ($fromDate, $toDate) {
            $query->where('report_from', '>=', $fromDate)
                ->where('report_to', '<=', $toDate);
        });
        if ($businessType) {
            $reports->where('business_type_id', $businessType);
        }
        $reports->with(['submittedBy', 'approvedBy']);
        $data = [];
        $data['reports'] = $reports->get();
        $data['total'] = $reports->get()->sum('amount');
        $data['request'] = $request->toArray();
        return $data;
    }

    public function reimbursements()
    {
        $request = request();
        $this->setModel(new ExpenseReport());
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $businessType = $request->input('type');
        $reports = $this->model->where(function ($query) use ($fromDate, $toDate) {
            $query->where('report_from', '>=', $fromDate)
                ->where('report_to', '<=', $toDate);
        });
        if ($businessType) {
            $reports->where('business_type_id', $businessType);
        }
        $reports = $reports->with(['submittedBy', 'approvedBy', 'reimburses.report'])->get();

        $reports = $reports->pluck('reimburses')->collapse();
        $data = [];
        $total = $reports->sum('amount');
        $reportTotal = $reports->pluck('report')->sum('amount');
        $data['reimburses'] = $reports->groupBy('report.prepared_by');
        $data['reimburses_total'] = $total;
        $data['report_total'] = $reportTotal;
        $data['balance'] = $reportTotal - $total;
        $data['request'] = $request->toArray();
        return $data;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getExpenses($request)
    {
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $expenses = $this->model->whereBetween('expense_date', [$fromDate, $toDate]);
        return $expenses;
    }
}