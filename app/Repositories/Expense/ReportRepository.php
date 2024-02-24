<?php

namespace App\Repositories\Expense;

use App\Expense;
use App\ExpenseReport;
use App\ExpenseReportReimburse;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use PDF;

/**
 * Class ReportRepository
 * @package App\Repositories\Expense
 */
class ReportRepository extends BaseRepository
{
    /**
     * ReportRepository constructor.
     * @param ExpenseReport|null $report
     */
    public function __construct(ExpenseReport $report = null)
    {
        $this->setModel($this ? $report : new ExpenseReport());
        $this->setCodePrefix('EXR', 'report_no');
    }

    /**
     * index page filter functions
     * @return mixed
     */
    public function index()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $userId = \request()->input('user_id');
        $lastWeek = carbon()->subWeek();
        $reports = ExpenseReport::orderBy('id', 'desc');
        if ($search) {
            $reports->where(function ($q) use ($search) {
                $q->where('report_no', 'LIKE', '%' . $search . '%')
                    ->orWhere('status', 'LIKE', '%' . $search . '%')
                    ->orWhere('report_from', 'LIKE', '%' . $search . '%')
                    ->orWhere('report_to', 'LIKE', '%' . $search . '%')
                    ->orwhere(function ($query) use ($search) {
                        $query->whereHas('businessType', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        });
                    });
            });
        }
        switch ($filter) {
            case 'Draft':
                $reports->where('status', 'Draft');
                break;
            case 'Submitted':
                $reports->where('status', 'Submitted');
                break;
            case 'Approved':
                $reports->where('status', 'Approved');
                break;
            case 'Rejected':
                $reports->where('status', 'Rejected');
                break;
            case 'Partially Reimbursed':
                $reports->where('status', 'Partially Reimbursed');
                break;
            case 'Reimbursed':
                $reports->where('status', 'Reimbursed');
                break;
            case 'recentlyCreated':
                $reports->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $reports->where('updated_at', '>', $lastWeek);
                break;
        }

        if ($userId) {
            $reports->where('prepared_by', $userId);
        }
        return $reports->paginate(12)->toArray();
    }

    public function approvalIndex(Request $request)
    {
        $columns = ['title', 'report_no', 'report_from', 'report_to', 'status'];
        $searchingColumns = ['title', 'report_no', 'report_from', 'report_to', 'status'];
        $data = $this->getTableData($request, $columns, $searchingColumns, [], true, null, null, [
            ['approved_by', auth()->id()],
        ]);
        /** The Current User Can do the action */
        $data['data'] = array_map(function ($item) {
            $item['title'] = '<a target="_blank" href="' . route('expense.reports.show', $item['id']) . '">' . $item['title'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Show', null, ['expense.reports.show', [$item['id']]], ['class' => 'btn-success']);
            if ($item['status'] == 'Submitted') {
                $item['action'] .= actionBtn('Approve', null, [], ['data-id' => $item['id'], 'class' => 'btn-primary approve-report']);
                $item['action'] .= actionBtn('Reject', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger reject-report']);
            }
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    /**
     * report store function
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Request $request)
    {
        return $this->storeData($request);
    }

    /**
     * update report
     * @param ExpenseReport $report
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(ExpenseReport $report, Request $request)
    {
        $report->expenses()->update(['report_id' => null]);
        $this->setModel($report);
        return $this->storeData($request);
    }

    /**
     * @param ExpenseReport $report
     * @param $status
     */
    public function approve(ExpenseReport $report, $status)
    {
        $report->status = $status;
        $report->save();
        $this->updateExpenses($report, $status);
    }

    /**
     * Delete report
     * @param ExpenseReport $report
     * @return array
     */
    public function delete(ExpenseReport $report)
    {
        try {
            $report->delete();
            return ['success' => true, 'message' => 'Deleted success'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Deleted failed'];
        }
    }

    /**
     * @param ExpenseReport $report
     * @param ExpenseReportReimburse $reimburse
     * @return array
     */
    public function reimbursementDelete(ExpenseReport $report, ExpenseReportReimburse $reimburse)
    {
        try {
            $reimburse->delete();
            $pendingAmount = reportReimbursementPendingAmount($report);
            if ($pendingAmount <= 0) {
                $report->status = 'Reimbursed';
                $report->save();
                $this->updateExpenses($report, 'Reimbursed');
            } else {
                if ($report->status != 'Partially Reimbursed') {
                    $report->status = 'Partially Reimbursed';
                    $report->save();
                }else{
                    $report->status = 'Approved';
                    $report->save();
                }
                $this->updateExpenses($report, 'Approved');
            }
            return ['success' => true, 'message' => 'Deleted success'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Deleted failed'];
        }
    }

    /**
     * @param Request $request
     * @param ExpenseReport $report
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function submitToApproval(Request $request, ExpenseReport $report)
    {
        $this->setModel($report);
        if ($request->input('approved_by')) {
            $this->model->setAttribute('approved_by', $request->input('approved_by'));
            $this->model->setAttribute('status', 'Submitted');
            $this->model->setAttribute('submitted_on', now());
            $this->model->setAttribute('submitted_by', auth()->id());
            $this->model->save();
            $this->updateExpenses($report, 'Submitted');
        }
        return $this->model;
    }

    /**
     * reimbursement store
     * @param ExpenseReport $report
     * @param Request $request
     * @return ExpenseReportReimburse
     */
    public function reimbursementStore(ExpenseReport $report, Request $request)
    {
        $reimburse = new ExpenseReportReimburse();
        return $this->reimbursementSave($reimburse, $report, $request);
    }

    /**
     * reimbursement update
     * @param ExpenseReport $report
     * @param ExpenseReportReimburse $reimburse
     * @param Request $request
     * @return ExpenseReportReimburse
     */
    public function reimbursementUpdate(ExpenseReport $report, ExpenseReportReimburse $reimburse, Request $request)
    {
        return $this->reimbursementSave($reimburse, $report, $request);
    }

    /**
     * reimbursement save
     * @param ExpenseReportReimburse $reimburse
     * @param ExpenseReport $report
     * @param Request $request
     * @return ExpenseReportReimburse
     */
    public function reimbursementSave(ExpenseReportReimburse $reimburse, ExpenseReport $report, Request $request)
    {
        $reimburse->setAttribute('reimbursed_on', $request->input('reimbursed_on'));
        $reimburse->setAttribute('amount', $request->input('reimbursed_amount'));
        $reimburse->setAttribute('paid_through', $request->input('reimbursed_paid_through'));
        $reimburse->setAttribute('notes', $request->input('reimbursed_notes'));
        $reimburse->setAttribute('report_id', $report->id);
        $reimburse->save();
        $pendingAmount = reportReimbursementPendingAmount($report);
        if ($pendingAmount <= 0) {
            $report->status = 'Reimbursed';
            $report->save();
            $this->updateExpenses($report, 'Reimbursed');
        } else {
            if ($report->status != 'Partially Reimbursed') {
                $report->status = 'Partially Reimbursed';
                $report->save();
                $this->updateExpenses($report, 'Approved');
            }
        }
        return $reimburse;
    }

    /**
     * report store
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function storeData(Request $request)
    {
        $reportsId = explode(',', $request->input('expenses_id'));
        $reports = Expense::whereIn('id', $reportsId);
        $isNew = false;
        if (!$this->model->getAttribute('report_no')) {
            $isNew = true;
            $this->model->setAttribute('report_no', $this->getCode());
        }
        $amount = $reports->sum('amount');
        $company = userCompany();
        $this->model->setAttribute('business_type_id', $request->input('business_type_id'));
        $this->model->setAttribute('company_id', $company ? $company->id : 1);
        $this->model->setAttribute('report_from', $request->input('report_from'));
        $this->model->setAttribute('report_to', $request->input('report_to'));
        $this->model->setAttribute('notes', $request->input('notes'));
        $this->model->setAttribute('title', $request->input('title'));
        $this->model->setAttribute('amount', $amount);
        $this->model->setAttribute('prepared_by', auth()->id());
        if ($request->input('approved_by')) {
            $this->model->setAttribute('approved_by', $request->input('approved_by'));
            $this->model->setAttribute('status', 'Submitted');
            $this->model->setAttribute('submitted_on', now());
            $this->model->setAttribute('submitted_by', auth()->id());
        }

        $this->model->save();

        $reports->update(['report_id' => $this->model->id]);

        if ($isNew && $this->model->status == 'Submitted') {
            $this->updateExpenses($this->model, 'Submitted');
        }
        if ($isNew && $this->model->status == 'Draft') {
            $this->updateExpenses($this->model, 'Unsubmitted');
        }
        return $this->model;
    }

    /**
     * @param ExpenseReport $report
     * @param string $status
     */
    public function updateExpenses(ExpenseReport $report, $status = 'Submitted')
    {
        $report->expenses()->update(['status' => $status]);
    }

    /**
     * @param ExpenseReport $report
     * @return mixed
     */
    public function pdfExport(ExpenseReport $report)
    {
        $data = [];
        $company = $report->company;
        $companyAddress = $company ? $company->addresses()->first() : null;

        $data['company'] = $company;
        $data['companyAddress'] = $companyAddress;
        $data['report'] = $report;
        $pdf = PDF::loadView('expense.reports.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Expense Report (' . $report->report_no . ')' . '.pdf');
    }


    /**
     * @param ExpenseReport|null $report
     * @param string|null $method
     * @return array
     */
    public function breadcrumbs(ExpenseReport $report = null, string $method = null): array
    {
        if (!$method) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $method = $backtrace[1]['function'] ?? null;
        }
        $base = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Expense', 'route' => 'expense.index'],
        ];
        $breadcrumbs = [
            'index' => array_merge($base, [
                ['text' => 'Reports'],
            ]),
            'create' => array_merge($base, [
                ['text' => 'Reports', 'route' => 'expense.reports.index'],
                ['text' => 'Create']
            ]),
            'approval-index' => array_merge($base, [
                ['text' => 'Reports', 'route' => 'expense.reports.index'],
                ['text' => 'Approval']
            ]),
            'show' => array_merge($base, [
                ['text' => 'Reports', 'route' => 'expense.reports.index'],
                ['text' => $report->report_no ?? ''],
            ]),
            'printPdf' => array_merge($base, [
                ['text' => 'Reports', 'route' => 'expense.reports.index'],
                ['text' => $report->report_no ?? ''],
                ['text' => 'Print'],
            ]),
            'edit' => array_merge($base, [
                ['text' => 'Reports', 'route' => 'expense.reports.index'],
                ['text' => $report->report_no ?? ''],
                ['text' => 'Edit'],
            ])
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}