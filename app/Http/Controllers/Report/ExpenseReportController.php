<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Repositories\Report\ExpenseReportRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use PDF;

class ExpenseReportController extends Controller
{
    protected $report;

    /**
     * SalesReportController constructor.
     * @param ExpenseReportRepository $report
     */
    public function __construct(ExpenseReportRepository $report)
    {
        $this->report = $report;
    }

    /** Receipts */
    public function expenseDetails()
    {
        $breadcrumb = $this->breadcrumbs('expenseDetails');
        if (request()->ajax()) {
            $data = $this->report->expenseDetails();
            return response()->json($data);
        }
        return view('report.expense.expense-details', compact('breadcrumb'));
    }

    public function expenseByType()
    {
        $breadcrumb = $this->breadcrumbs('expenseByType');
        if (request()->ajax()) {
            $data = $this->report->expenseByType();
            return response()->json($data);
        }
        return view('report.expense.expense-by-type', compact('breadcrumb'));
    }

    public function expenseByRep()
    {
        $breadcrumb = $this->breadcrumbs('expenseByRep');
        if (request()->ajax()) {
            $data = $this->report->expenseByRep();
            return response()->json($data);
        }
        return view('report.expense.expense-by-rep', compact('breadcrumb'));
    }

    public function officeExpByCompany()
    {
        $breadcrumb = $this->breadcrumbs('officeExpByCompany');
        if (request()->ajax()) {
            $data = $this->report->officeExpByCompany();
            return response()->json($data);
        }
        return view('report.expense.office-exp-by-company', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function expenseDetailsExport()
    {
        $data = $this->report->expenseDetails();
        $pdf = PDF::loadView('report.expense.export.expense-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Expense Details.pdf');
    }

    /**
     * @return Factory|View
     */
    public function unSubExpenses()
    {
        $breadcrumb = $this->breadcrumbs('unSubExpenses');
        return view('report.expense.unsubmitted-expenses', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function unSubExpensesExport()
    {
        $data = $this->report->expenseDetails();
        $pdf = PDF::loadView('report.expense.export.unsubmitted-expenses', $data);
        return $pdf->download(env('APP_NAME') . ' - UnSubmitted Expenses.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function expenseByCat()
    {
        $breadcrumb = $this->breadcrumbs('expenseByCat');
        if (request()->ajax()) {
            $data = $this->report->expenseByCat();
            return response()->json($data);
        }
        return view('report.expense.expenses-by-category', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function expenseByCatExport()
    {
        $data = $this->report->expenseByCat();
        $pdf = PDF::loadView('report.expense.export.expenses-by-category', $data);
        return $pdf->download(env('APP_NAME') . ' - Expenses By Category.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function expenseByCus()
    {
        $breadcrumb = $this->breadcrumbs('expenseByCus');
        if (request()->ajax()) {
            $data = $this->report->expenseByCus();
            return response()->json($data);
        }
        return view('report.expense.expenses-by-customer', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function expenseByCusExport()
    {
        $data = $this->report->expenseByCus();
        $pdf = PDF::loadView('report.expense.export.expenses-by-customer', $data);
        return $pdf->download(env('APP_NAME') . ' - Expenses By Customer.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function expenseBySup()
    {
        $breadcrumb = $this->breadcrumbs('expenseBySup');
        if (request()->ajax()) {
            $data = $this->report->expenseBySup();
            return response()->json($data);
        }
        return view('report.expense.expenses-by-supplier', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function expenseBySupExport()
    {
        $data = $this->report->expenseBySup();
        $pdf = PDF::loadView('report.expense.export.expenses-by-supplier', $data);
        return $pdf->download(env('APP_NAME') . ' - Expenses By Supplier.pdf');
    }

    /**
     * @return Factory|View
     */
    public function expenseByEmp()
    {
        $breadcrumb = $this->breadcrumbs('expenseByEmp');
        return view('report.expense.expenses-by-employee', compact('breadcrumb'));
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function mileageExpByEmp()
    {
        $breadcrumb = $this->breadcrumbs('mileageExpByEmp');
        if (request()->ajax()) {
            $data = $this->report->mileageExpByEmp();
            return response()->json($data);
        }
        return view('report.expense.mileage-exp-by-employee', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function mileageExpByEmpExport()
    {
        $data = $this->report->mileageExpByEmp();
        $pdf = PDF::loadView('report.expense.export.mileage-exp-by-employee', $data);
        return $pdf->download(env('APP_NAME') . ' - Mileage Exp By Employee.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     * Reports
     */
    public function expReportDetails()
    {
        $breadcrumb = $this->breadcrumbs('expReportDetails');
        if (request()->ajax()) {
            $data = $this->report->expReportDetails();
            return response()->json($data);
        }
        return view('report.expense.expense-report-details', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function expReportDetailsExport()
    {
        $data = $this->report->expReportDetails();
        $pdf = PDF::loadView('report.expense.export.expense-report-details', $data);
        return $pdf->download(env('APP_NAME') . ' - Expense Report Details.pdf');
    }

    /**
     * @return Factory|JsonResponse|View
     * Reimbursements
     */
    public function reimbursements()
    {
        $breadcrumb = $this->breadcrumbs('reimbursements');
        $data = $this->report->reimbursements();
        if (request()->ajax()) {

            return response()->json($data);
        }
        return view('report.expense.reimbursements', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function reimbursementsExport()
    {
        $data = $this->report->reimbursements();
        $pdf = PDF::loadView('report.expense.export.reimbursements', $data);
        return $pdf->download(env('APP_NAME') . ' - Reimbursements.pdf');
    }

    /**
     * @param string $method
     * @return array
     */
    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'expenseDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Expense by Company'],
            ],
            'unSubExpenses' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Unsubmitted Expenses'],
            ],
            'expenseByCat' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Expenses by Category'],
            ],
            'expenseByRep' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Expenses by Rep'],
            ],
            'expenseByShop' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Expenses by Shop'],
            ],
            'expenseByType' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Expenses by Type'],
            ],
            'expenseByCus' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Expenses by Customer'],
            ],
            'expenseBySup' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Expenses by Supplier'],
            ],
            'expenseByEmp' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Expenses by Employee'],
            ],
            'mileageExpByEmp' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Mileage Expenses by Employee'],
            ],
            'expReportDetails' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Expense Report Details'],
            ],
            'reimbursements' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Reimbursements'],
            ],
            'officeExpByCompany' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Reports', 'route' => 'report.index'],
                ['text' => 'Office Expenses by Company'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
