<?php

namespace App\Http\Controllers\Finance;

use App\Account;
use App\AccountCategory;
use App\Company;
use App\Exports\AccountExport;
use App\Exports\AccTransExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\AccountStoreRequest;
use App\Http\Requests\Finance\OpeningBalanceRequest;
use App\OpeningBalanceReference;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Repositories\Finance\AccountRepository;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

class AccountController extends Controller
{
    /**
     * @var AccountRepository
     */
    protected $account;

    /**
     * AccountController constructor.
     * @param AccountRepository $account
     */
    public function __construct(AccountRepository $account)
    {
        $this->account = $account;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //$categories = AccountCategory::get();

        if (\request()->ajax()) {
            $accounts = $this->account->grid();
            return response()->json($accounts);
        }
        $breadcrumb = $this->account->breadcrumbs('index');
        return view('finance.account.index', compact('breadcrumb'));
    }

    public function getGroups()
    {
        $groups = $this->account->getGroups();
        return response()->json($groups);
    }

    public function create()
    {
        $breadcrumb = $this->account->breadcrumbs('create');
        return view('finance.account.create', compact('breadcrumb'));
    }

    public function store(AccountStoreRequest $request)
    {
        $this->account->save($request);
        alert()->success('Account created successfully', 'Success')->persistent();
        return redirect()->route('finance.account.index');
    }

    public function show(Account $account)
    {
        $breadcrumb = $this->account->breadcrumbs('show', $account);
        $transactions = $account->transactions;
        $from = request()->input('from');
        $to = request()->input('to');
        $from = $from ? carbon($from) : now()->startOfMonth();
        $to = $to ? carbon($to) : now()->endOfMonth();
        $runningBalance = accRunningBal_($account, $from, $to);
        $button = $this->account->getNavigationButtonData($account, $from, $to);
        return view('finance.account.show',
            compact('breadcrumb', 'account', 'transactions', 'runningBalance', 'button', 'from', 'to'));
    }

    public function export(Account $account)
    {
        $account->load('transactions');
        $transactions = $account->transactions;

        $from = request()->input('from');
        $to = request()->input('to');
        $from = $from ? carbon($from) : now()->startOfMonth();
        $to = $to ? carbon($to) : now()->endOfMonth();
        $runningBalance = accRunningBal_($account, $from, $to);

        $data = [];
        $data['accName'] = $account->getAttribute('name');
        $data['dateFrom'] = $from->toDateString();
        $data['dateTo'] = $to->toDateString();
        $data['transactions'] = $transactions;
        $data['runningBalance'] = $runningBalance;

        if(\request()->input('type') == 'pdf'){
            ini_set("pcre.backtrack_limit", "2000000");
            ini_set('memory_limit', '256M');
            $pdf = PDF::loadView('finance.account.export', $data);
            return $pdf->download('Account Transactions (' . $account->getAttribute('name') . ')' . '.pdf');
        }
        else if(\request()->input('type') == 'excel'){
            return $this->exportTransExcel($account, $data);
        }
    }

    public function exportTransExcel(Account $account, $data)
    {
        return Excel::download(new AccTransExport($account, $data), 'Account Transactions ('. $account->getAttribute('name') . ')' . '.xlsx', 'Xlsx');
    }

    public function edit(Account $account)
    {
        $breadcrumb = $this->account->breadcrumbs('edit', $account);
        return view('finance.account.edit', compact('breadcrumb', 'account'));
    }

    public function update(AccountStoreRequest $request, Account $account)
    {
        $this->account->update($request, $account);
        alert()->success('Account updated successfully', 'Success')->persistent();
        return redirect()->route('finance.account.show', [$account]);
    }

    /**
     * Search expense accounts
     * @param null|string $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchExpenseAccount($q = null)
    {
        return response()->json($this->account->searchExpenseAccount($q));
    }

    /**
     * search paid through account
     * @param null|string $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPaidThroughAccount($q = null)
    {
        return response()->json($this->account->searchPaidThroughAccount($q));
    }

    /**
     * @param OpeningBalanceRequest $request
     * @param Account $account
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveOpening(OpeningBalanceRequest $request, Account $account)
    {
        $account->setAttribute('opening_balance', $request->input('opening'));
        $account->setAttribute('opening_balance_at', carbon($request->input('opening_at')));
        $account->setAttribute('opening_balance_type', $request->input('balance_type'));
        $account->save();

        $references = $request->input('references');
        if ($references) {
            foreach ($references as $reference) {
                $ref = new OpeningBalanceReference();
                $ref->setAttribute('date', carbon()->toDateString());
                $ref->setAttribute('reference_type', 'Account');
                $ref->setAttribute('account_id', $account->id);
                $ref->setAttribute('updated_by', auth()->id());
                $ref->setAttribute('reference_no', array_get($reference, 'reference_no', null));
                $ref->setAttribute('amount', array_get($reference, 'amount', null));
                $ref->save();
            }
        }
        return response()->json(['success' => true]);
    }

    public function searchByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $accounts = $company->accounts()->get(['id', 'name', 'code'])->toArray();
        } else {
            $accounts = $company->accounts()->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $accounts = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $accounts);
        return response()->json(["success" => true, "results" => $accounts]);
    }

    public function searchExpAccountByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $accounts = $company->accounts()
                ->get(['id', 'name', 'code'])->toArray();
        } else {
            $accounts = $company->accounts()
                ->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $accounts = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $accounts);
        return response()->json(["success" => true, "results" => $accounts]);
    }

    public function searchCashPaidThroughByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $accounts = $company->accounts()->where('account_type_id', 1)
                ->where('accountable_type', 'App\Company')
                ->get(['id', 'name', 'code'])->toArray();
        } else {
            $accounts = $company->accounts()->where('account_type_id', 1)
                ->where('accountable_type', 'App\Company')
                ->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $accounts = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $accounts);
        return response()->json(["success" => true, "results" => $accounts]);
    }

    public function searchCihPaidThroughByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $accounts = $company->accounts()->where('account_type_id', 19)
                ->where('accountable_type', 'App\Company')
                ->get(['id', 'name', 'code'])->toArray();
        } else {
            $accounts = $company->accounts()->where('account_type_id', 19)
                ->where('accountable_type', 'App\Company')
                ->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $accounts = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $accounts);
        return response()->json(["success" => true, "results" => $accounts]);
    }

    public function searchOthersPaidThroughByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $accounts = $company->accounts()->where('account_type_id', 2)
                ->get(['id', 'name', 'code'])->toArray();
        } else {
            $accounts = $company->accounts()->where('account_type_id', 2)
                ->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $accounts = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $accounts);
        return response()->json(["success" => true, "results" => $accounts]);
    }
}
