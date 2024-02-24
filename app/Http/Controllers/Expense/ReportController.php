<?php

namespace App\Http\Controllers\Expense;

use App\{
    ExpenseReport, ExpenseReportReimburse
};
use App\Http\Requests\Expense\{
    ReimbursementStoreRequest, ReportStoreRequest
};
use App\Repositories\Expense\ReportRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class ReportController
 * @package App\Http\Controllers\Expense
 */
class ReportController extends Controller
{
    /** @var ReportRepository */
    protected $report;

    /**
     * ReportController constructor.
     * @param ReportRepository $report
     */
    public function __construct(ReportRepository $report)
    {
        $this->report = $report;
    }

    /**
     * load report index view and index filters
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->report->getModel());
        $breadcrumb = $this->report->breadcrumbs();
        if (request()->ajax()) {
            $reports = $this->report->index();
            return response()->json($reports);
        }
        return view('expense.reports.index', compact('breadcrumb'));
    }

    /**
     * Load report create view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->report->getModel());
        $breadcrumb = $this->report->breadcrumbs();
        return view('expense.reports.create', compact('breadcrumb'));
    }

    /**
     * Create new report
     * @param ReportStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(ReportStoreRequest $request)
    {
        $this->authorize('store', $this->report->getModel());
        $this->report->store($request);
        alert()->success('Expense report created successfully!', 'Success')->persistent();
        return redirect()->route('expense.reports.index');
    }

    /**
     * @param Request $request
     * @param ExpenseReport $report
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function submitToApproval(Request $request, ExpenseReport $report)
    {
        $this->authorize('approval', $this->report->getModel());
        $request->validate([
            'approved_by' => 'required|exists:users,id',
        ]);
        $this->report->submitToApproval($request, $report);
        alert()->success('Report Submitted to approvals!', 'Success')->persistent();
        return redirect()->route('expense.reports.show', $report);
    }

    /**
     * Load report show view
     * @param ExpenseReport $report
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(ExpenseReport $report)
    {
        $this->authorize('show', $this->report->getModel());
        $breadcrumb = $this->report->breadcrumbs($report);
        $report->load('expenses.category', 'reimburses.paidThroughAccount');
        return view('expense.reports.show', compact('breadcrumb', 'report'));
    }

    /**
     * Load the report edit view
     * @param ExpenseReport $report
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(ExpenseReport $report)
    {
        $this->authorize('edit', $this->report->getModel());
        $breadcrumb = $this->report->breadcrumbs($report);
        $businessType = $report->businessType;
        $approvedBy = $report->approvedBy;
        $report->setAttribute('business_type_name', $businessType ? $businessType->name : null);
        $report->setAttribute('approved_by_name', $approvedBy ? $approvedBy->name : null);
        $report->load('expenses.category');
        return view('expense.reports.edit', compact('breadcrumb', 'report'));
    }

    /**
     * Update existing report
     * @param ExpenseReport $report
     * @param ReportStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ExpenseReport $report, ReportStoreRequest $request)
    {
        $this->authorize('update', $this->report->getModel());
        $this->report->update($report, $request);
        alert()->success('Expense report updated successfully!', 'Success')->persistent();
        return redirect()->route('expense.reports.index');
    }

    /**
     * @param ExpenseReport $report
     * @param ExpenseReportReimburse $reimburse
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reimbursementShow(ExpenseReport $report, ExpenseReportReimburse $reimburse)
    {
        $this->authorize('show', $reimburse);
        $reimburse->load('paidThroughAccount');
        return response()->json($reimburse->toArray());
    }

    /**
     * create a Reimburse
     * @param ExpenseReport $report
     * @param ReimbursementStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reimbursementStore(ExpenseReport $report, ReimbursementStoreRequest $request)
    {
        $this->authorize('store', new ExpenseReportReimburse());
        $this->report->reimbursementStore($report, $request);
        alert()->success('Reimbursement added successfully!', 'Success')->persistent();
        return redirect()->route('expense.reports.show', $report);
    }

    /**
     * update Reimburse
     * @param ExpenseReport $report
     * @param ExpenseReportReimburse $reimburse
     * @param ReimbursementStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reimbursementUpdate(ExpenseReport $report, ExpenseReportReimburse $reimburse, ReimbursementStoreRequest $request)
    {
        $this->authorize('update', $reimburse);
        $this->report->reimbursementUpdate($report, $reimburse, $request);
        alert()->success('Reimbursement updated successfully!', 'Success')->persistent();
        return redirect()->route('expense.reports.show', $report);
    }

    /**
     * delete Reimburse
     * @param ExpenseReport $report
     * @param ExpenseReportReimburse $reimburse
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reimbursementDelete(ExpenseReport $report, ExpenseReportReimburse $reimburse)
    {
        $this->authorize('delete', $reimburse);
        return response()->json(
            $this->report->reimbursementDelete($report, $reimburse)
        );
    }

    /**
     * Delete a report
     * @param ExpenseReport $report
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(ExpenseReport $report)
    {
        $this->authorize('delete', $this->report->getModel());
        return response()->json(
            $this->report->delete($report)
        );
    }

    /**
     * Search report
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($q = null)
    {
        return response()->json(
            $this->report->search($q, 'title', ['report_no', 'title'])
        );
    }

    /**
     * @param ExpenseReport $report
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export(ExpenseReport $report)
    {
        $this->authorize('export', $this->report->getModel());
        return $this->report->pdfExport($report);
    }

    /**
     * @param ExpenseReport $report
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function printPdf(ExpenseReport $report)
    {
        $this->authorize('print', $this->report->getModel());
        $breadcrumb = $this->report->breadcrumbs($report);
        $company = $report->company;
        $companyAddress = $company ? $company->addresses()->first() : null;
        return view('expense.reports.print', compact('breadcrumb', 'report', 'company', 'companyAddress'));
    }
}
