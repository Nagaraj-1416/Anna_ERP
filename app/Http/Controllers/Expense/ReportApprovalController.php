<?php

namespace App\Http\Controllers\Expense;

use App\ExpenseReport;
use App\Repositories\Expense\ReportRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportApprovalController extends Controller
{
    protected $report;

    public function __construct(ReportRepository $report)
    {
        $this->report = $report;
    }

    public function index()
    {
        $breadcrumb = $this->report->breadcrumbs(null, 'approval-index');
        if (request()->ajax()) {
            if (\request()->ajax()) {
                return response()->json($this->report->approvalIndex(request()));
            }
        }
        return view('expense.reports.approve.index', compact('breadcrumb'));
    }

    public function approve(Request $request, ExpenseReport $report)
    {
        $request->validate([
            'status' => 'required|in:"Approved","Rejected"',
        ]);
        $this->report->approve($report, $request->input('status'));
        return response()->json(['success' => true, 'message' => 'success']);
    }
}
