<?php

namespace App\Repositories\Finance;

use App\Account;
use App\AccountType;
use App\ChequeInHand;
use App\DailySale;
use App\Http\Requests\Finance\AccountStoreRequest;
use App\Http\Requests\Finance\ChequeStoreRequest;
use App\Rep;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class ChequesInHandRepository
 * @package App\Repositories\Finance
 */
class ChequesInHandRepository extends BaseRepository
{

    /**
     * ChequesInHandRepository constructor.
     * @param ChequeInHand|null $cheque
     */
    public function __construct(ChequeInHand $cheque = null)
    {
        $this->setModel($cheque ?? new ChequeInHand());
    }

    public function grid()
    {
        $request = request();
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $status = $request->input('chequeStatus');
        $type = $request->input('chequeType');
        $transferred = $request->input('chequeTransferred');
        $company = $request->input('company');
        $customer = $request->input('customer');
        $route = $request->input('route');
        $rep = $request->input('rep');

        $cheques = ChequeInHand::where('cheque_date', '>=', $fromDate)->where('cheque_date', '<=', $toDate);

        if($status){
            $cheques = $cheques->where('status', $status);
        }else{
            $cheques = $cheques->whereNotIn('status', ['Bounced','Canceled']);
        }

        if($type){
            $cheques = $cheques->where('cheque_type', $type);
        }

        if($transferred){
            $cheques = $cheques->where('is_transferred', $transferred);
        }

        if($company){
            $cheques = $cheques->where('company_id', $company);
        }

        if($customer){
            $cheques = $cheques->where('customer_id', $customer);
        }

        if($rep){
            $allocations = DailySale::where('rep_id', $rep)
                ->where('to_date', '<=', $toDate)
                ->get();
            $allocationIds = $allocations->pluck('id')->toArray();
            $cheques = $cheques->whereIn('daily_sale_id', $allocationIds);
        }

        if($route){
            $allocations = DailySale::where('route_id', $route)
                ->where('to_date', '<=', $toDate)
                ->get();
            $allocationIds = $allocations->pluck('id')->toArray();
            $cheques = $cheques->whereIn('daily_sale_id', $allocationIds);
        }

        $chequesTotal = $cheques->sum('amount');

        $cheques = groupByCallbackForCheque(
            $cheques->with('bank', 'chequeable', 'chequeable.invoice', 'chequeable.invoice.customer')
            ->get());

        $data = [];
        $data['cheques'] = $cheques;
        $data['request'] = $request->toArray();
        $data['chequesTotal'] = $chequesTotal;
        return $data;
    }

    public function gridRegisteredBy()
    {
        $request = request();
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $status = $request->input('chequeStatus');
        $type = $request->input('chequeType');
        $company = $request->input('company');
        $customer = $request->input('customer');
        $route = $request->input('route');
        $rep = $request->input('rep');

        $cheques = ChequeInHand::where('registered_date', '>=', $fromDate)->where('registered_date', '<=', $toDate);

        if($status){
            $cheques = $cheques->where('status', $status);
        }else{
            $cheques = $cheques->whereNotIn('status', ['Bounced','Canceled']);
        }

        if($type){
            $cheques = $cheques->where('cheque_type', $type);
        }

        if($company){
            $cheques = $cheques->where('company_id', $company);
        }

        if($customer){
            $cheques = $cheques->where('customer_id', $customer);
        }

        if($rep){
            $allocations = DailySale::where('rep_id', $rep)
                ->where('to_date', '<=', $toDate)
                ->get();
            $allocationIds = $allocations->pluck('id')->toArray();
            $cheques = $cheques->whereIn('daily_sale_id', $allocationIds);
        }

        if($route){
            $allocations = DailySale::where('route_id', $route)
                ->where('to_date', '<=', $toDate)
                ->get();
            $allocationIds = $allocations->pluck('id')->toArray();
            $cheques = $cheques->whereIn('daily_sale_id', $allocationIds);
        }

        $chequesTotal = $cheques->sum('amount');

        $cheques = $cheques->with('bank', 'chequeable', 'chequeable.invoice', 'chequeable.invoice.customer')
            ->get();
        $cheques = groupByCallbackForCheque($cheques);

        $data = [];
        $data['cheques'] = $cheques;
        $data['request'] = $request->toArray();
        $data['chequesTotal'] = $chequesTotal;
        return $data;
    }

    public function gridByDeposited()
    {
        $request = request();
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $status = $request->input('chequeStatus');
        $type = $request->input('chequeType');
        $customer = $request->input('customer');

        $cheques = ChequeInHand::where('status', 'Deposited')
            ->whereBetween('cheque_date', [$fromDate, $toDate]);

        if($status){
            $cheques = $cheques->where('status', $status);
        }

        if($type){
            $cheques = $cheques->where('cheque_type', $type);
        }

        if($customer){
            $cheques = $cheques->where('customer_id', $customer);
        }

        $chequesTotal = $cheques->sum('amount');

        $cheques = groupByCallbackForCheque($cheques->with('bank', 'chequeable', 'chequeable.invoice', 'chequeable.invoice.customer')
            ->get());

        $data = [];
        $data['cheques'] = $cheques;
        $data['request'] = $request->toArray();
        $data['chequesTotal'] = $chequesTotal;
        return $data;
    }

    public function save(ChequeStoreRequest $request)
    {
        $this->model->setAttribute('type', 'Manual');
        $this->model->setAttribute('prepared_by', auth()->id());

        $cheque = $this->model->fill($request->toArray());
        $cheque->save();

        /** add accumulated opening to deposited account */
        $depositedTo = $request->input('deposited_to');
        $depositedToAcc = Account::where('id', $depositedTo)->first();
        $depositedToAcc->opening_balance = ($depositedToAcc->opening_balance + $request->input('amount'));
        $depositedToAcc->save();

        /** add transaction data to table */
        $debitAccount = Account::find($depositedTo);
        $creditAccount = Account::find(3);
        recordTransaction($cheque, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $cheque->amount,
            'auto_narration' => 'Cheque registered manually for '.number_format($cheque->amount).' and deposited to '.$cheque->depositedTo->name,
            'manual_narration' => 'Cheque registered manually for '.number_format($cheque->amount).' and deposited to '.$cheque->depositedTo->name,
            'tx_type_id' => 43,
            'customer_id' => $cheque->customer_id,
            'company_id' => $cheque->company_id,
        ], 'ManualChequeRegistered', false);

        return $cheque;
    }

    /**
     * Get the breadcrumbs of the ChequesInHand module
     * @param string $method
     * @param Account|null $account
     * @return array|mixed
     */
    public function breadcrumbs(string $method, Account $account = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Banking Overview', 'route' => 'finance.banking.index'],
                ['text' => 'Cheques in Hand'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Banking Overview', 'route' => 'finance.banking.index'],
                ['text' => 'Cheques in Hand', 'route' => 'finance.cheques.hand.index'],
                ['text' => 'Create Cheque'],
            ],
            'realise' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Banking Overview', 'route' => 'finance.banking.index'],
                ['text' => 'Cheques in Hand', 'route' => 'finance.cheques.hand.index'],
                ['text' => 'Mark as Realised'],
            ],
            'bounce' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Banking Overview', 'route' => 'finance.banking.index'],
                ['text' => 'Cheques in Hand', 'route' => 'finance.cheques.hand.index'],
                ['text' => 'Mark as Bounced'],
            ],
            'registered-by' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Banking Overview', 'route' => 'finance.banking.index'],
                ['text' => 'Cheques in Hand (By Registered Date)'],
            ],
            'deposited' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Banking Overview', 'route' => 'finance.banking.index'],
                ['text' => 'Deposited Cheques'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}