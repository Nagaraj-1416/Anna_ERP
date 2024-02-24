<?php

namespace App\Http\Controllers\Finance;

use App\Account;
use App\DailySale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\CommissionStoreRequest;
use App\InvoicePayment;
use App\Rep;
use App\Repositories\Finance\CommissionRepository;
use App\SalesCommission;
use App\SalesHandoverShortage;
use App\SalesOrder;
use App\StockShortage;
use App\StockShortageItem;
use App\Transaction;
use Illuminate\Http\JsonResponse;
use PDF;

class CommissionController extends Controller
{
    protected $commission;

    /**
     * CommissionController constructor.
     * @param CommissionRepository $commission
     */
    public function __construct(CommissionRepository $commission)
    {
        $this->commission = $commission;
    }

    /**
     * @param $year
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($year)
    {
        $nextYear = $year + 1;
        $preYear = $year - 1;

        //$year = carbon()->now()->year;
        $breadcrumb = $this->commission->breadcrumbs('index', $year);
        return view('finance.commission.index', compact('breadcrumb', 'year', 'nextYear', 'preYear'));
    }

    /**
     * @param Rep $rep
     * @param $year
     * @param $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('create', null);

        $nextYear = $year + 1;
        $preYear = $year - 1;

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($year, $month, 1);
        $prevMonth = carbon()->setDate($year, $month, 1)->subMonth();

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        /** last month start and end date */
        $lmStartDate = $prevMonth->copy()->startOfMonth()->toDateString();
        $lmEndDate = $prevMonth->copy()->endOfMonth()->toDateString();

        /** get credit sales */
        //$creditSales = $this->commission->getCreditSales($rep, $startDate, $endDate)['creditSales'];

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $lastOfMonth = $currentMonth->copy()->endOfMonth();

        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($startOfMonth, $interval, $lastOfMonth->copy());

        $creditSalesArray = [];

        foreach ($dateRange as $date) {
            $monthDate = $date->copy()->format('Y-m-d');

            $creditOrders = SalesOrder::where('rep_id', $rep->id)
                ->where('order_date', $monthDate)
                ->where('status', '!=', 'Canceled')
                ->with('customer')->get();
            $creditOrders = $creditOrders->map(function (SalesOrder $order) use ($monthDate){
                $order->balance = soOutstandingByDate($order->id, $monthDate)['balance'];
                return $order;
            });
            $creditOrders = $creditOrders->reject(function ($item) {
                return $item->balance == 0;
            });

            array_push($creditSalesArray, $creditOrders->sum('balance'));
        }

        $creditSales = array_sum($creditSalesArray);

        /** get total sales */
        $totalSales = $this->commission->getTotalSales($rep, $startDate, $endDate);

        /** get total cheques received */
        $chequeReceived = $this->commission->getChequeReceived($rep, $startDate, $endDate)['chequeReceived'];

        /** get total cash collection */
        $cashCollection = $this->commission->getCashCollection($rep, $startDate, $endDate)['cashCollection'];

        /** get total cheque collection */
        $chequeCollection = $this->commission->getChequeCollection($rep, $startDate, $endDate)['chequeCollection'];

        /** get total sales return */
        $salesReturn = $this->commission->getSalesReturn($rep, $startDate, $endDate);

        /** get total cheques bounced in current month */
        $chequeReturned = $this->commission->getChequeReturned($rep, $startDate, $endDate);

        /** get total cheques realized in last month */
        $chequeRealized = $this->commission->getChequeRealized($rep, $lmStartDate, $lmEndDate);

        /** get total visited customers*/
        $visitedCustomers = $this->commission->getVisitedCustomers($rep, $startDate, $endDate);

        /** get debit balance */
        $debitBalance = ($creditSales + $chequeReceived + $chequeCollection + $salesReturn + $chequeReturned);

        /** get credit balance */
        $creditBalance = ($totalSales + $cashCollection + $chequeCollection + $chequeRealized);

        /** get balance */
        if($debitBalance > $creditBalance){
            $balance = ($debitBalance - $creditBalance);
            $balanceType = 'Debit';
        }else{
            $balance = ($creditBalance - $debitBalance);
            $balanceType = 'Credit';
        }

        return view('finance.commission.create',
            compact(
                'breadcrumb',
                'rep',
                'month',
                'year',
                'nextYear',
                'preYear',
                'creditSales',
                'totalSales',
                'chequeReceived',
                'cashCollection',
                'salesReturn',
                'chequeReturned',
                'chequeRealized',
                'chequeCollection',
                'debitBalance',
                'creditBalance',
                'balance',
                'balanceType',
                'visitedCustomers'
            )
        );
    }

    /**
     * @param CommissionStoreRequest $request
     * @param Rep $rep
     * @param $year
     * @param $month
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CommissionStoreRequest $request, Rep $rep, $year, $month)
    {
        $this->commission->store($request, $rep, $year, $month);
        alert()->success('Sales commission created successfully', 'Success')->persistent();
        return redirect()->route('finance.commission.index', $year);
    }

    public function show(SalesCommission $commission)
    {
        $commission->with('rep');
        $rep = $commission->rep;

        $drAccount = Account::where('prefix', 'Commission')
            ->where('accountable_id', $commission->getAttribute('company_id'))
            ->where('accountable_type', 'App\Company')
            ->first();

        $crAccount = Account::where('prefix', 'Commission')
            ->where('accountable_id', $rep->id)
            ->where('accountable_type', 'App\Rep')
            ->first();

        if($commission->getAttribute('debit_balance') > $commission->getAttribute('credit_balance')){
            $commissionAmount = ($commission->getAttribute('debit_balance') - $commission->getAttribute('credit_balance'));
        }else{
            $commissionAmount = ($commission->getAttribute('credit_balance') - $commission->getAttribute('debit_balance'));
        }

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($commission->year, $commission->month, 1);

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        /** get total sales return */
        $expiredSalesReturn = $this->commission->getExpiredSalesReturn($rep, $startDate, $endDate);

        $generatedCommission = ($commissionAmount * 0.01);
        $awardingCommission = (($commissionAmount * 0.01) - $expiredSalesReturn);

        $allocations = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('to_date', [$startDate, $endDate])
            ->whereIn('status', ['Progress','Completed'])
            ->get();

        $driverIds = array_unique(array_filter($allocations->pluck('driver_id')->toArray()));
        $labourIds = array_unique(array_filter($allocations->pluck('labour_id')->toArray()));

        $allocationDriverAlone = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('to_date', [$startDate, $endDate])
            ->where('labour_id', null)
            ->whereIn('status', ['Progress','Completed'])
            ->get();

        $allocationDriverAloneCount = $allocationDriverAlone->count();

        $driverAloneIds = array_unique(array_filter($allocationDriverAlone->pluck('driver_id')->toArray()));

        $driverAndLabourCommission = ($awardingCommission * 0.08);

        $eligibleDriverCommission = (($driverAndLabourCommission * 3) / 8);
        $eligibleLabourCommission = (($driverAndLabourCommission * 5) / 8);

        $repTotalWorkingDays = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('to_date', [$startDate, $endDate])
            ->whereIn('status', ['Progress','Completed'])
            ->count();

        $driversTotalWorkingDays = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereIn('driver_id', $driverIds)
            ->whereBetween('to_date', [$startDate, $endDate])
            ->whereIn('status', ['Progress','Completed'])
            ->count();

        $laboursTotalWorkingDays = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereIn('labour_id', $labourIds)
            ->whereBetween('to_date', [$startDate, $endDate])
            ->whereIn('status', ['Progress','Completed'])
            ->count();

        $trans = Transaction::where('transactionable_id', $commission->id)
            ->where('transactionable_type', 'App\SalesCommission')->get();

        $awardedDriversCommission = awardedDriversCommission($rep, $driverIds, $startDate, $endDate, $repTotalWorkingDays, $eligibleDriverCommission);
        $awardedLaboursCommission = awardedLaboursCommission($rep, $labourIds, $startDate, $endDate, $repTotalWorkingDays, $eligibleLabourCommission);

        /** get cash shortages & total */
        $cashShortages = SalesHandoverShortage::whereBetween('date', [$startDate, $endDate])->where('rep_id', $rep->id)
            ->sum('amount');

        /** get stock shortages & total */
        $stockShortages = StockShortage::whereBetween('date', [$startDate, $endDate])->where('rep_id', $rep->id)
            ->pluck('id')->toArray();
        $stockShortagesItems = StockShortageItem::where('status', 'Approved')->whereIn('stock_shortage_id', $stockShortages)->sum('amount');

        $breadcrumb = $this->commission->breadcrumbs('show', null);
        return view('finance.commission.show',
            compact(
                'allocationDriverAloneCount',
                'driverAloneIds',
                'awardedLaboursCommission',
                'awardedDriversCommission',
                'repTotalWorkingDays',
                'rep',
                'trans',
                'driverAndLabourCommission',
                'eligibleDriverCommission',
                'eligibleLabourCommission',
                'driversTotalWorkingDays',
                'laboursTotalWorkingDays',
                'startDate',
                'endDate',
                'driverIds',
                'labourIds',
                'breadcrumb',
                'commission',
                'drAccount',
                'commissionAmount',
                'crAccount',
                'generatedCommission',
                'awardingCommission',
                'expiredSalesReturn',
                'cashShortages',
                'stockShortagesItems'
            ));
    }

    public function confirm(SalesCommission $commission)
    {
        $request = request();
        $request->validate([
            'debit_account' => 'required',
            'credit_account' => 'required'
        ]);

        $driverCommissions = $request->input('driver_commission_amount');
        $labourCommissions = $request->input('labour_commission_amount');

        $commission->setAttribute('debit_account', $request->input('debit_account'));
        $commission->setAttribute('credit_account', $request->input('credit_account'));
        $commission->setAttribute('approved_by', auth()->id());
        $commission->setAttribute('approved_on', carbon()->now()->toDateTimeString());
        $commission->setAttribute('status', 'Approved');
        $commission->save();

        $creditAccount = Account::where('id', $request->input('credit_account'))->first();
        $debitAccount = Account::where('id', $request->input('debit_account'))->first();

        recordTransaction($commission, $debitAccount, $creditAccount, [
            'date' => carbon()->now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $request->input('commission_amount'),
            'auto_narration' => 'The sales commission amount of '.$request->input('commission_amount').' was awarded to '.$creditAccount->name,
            'manual_narration' => 'The sales commission amount of '.$request->input('commission_amount').' was awarded to '.$creditAccount->name,
            'tx_type_id' => 51,
            'company_id' => $commission->getAttribute('company_id'),
        ], 'SalesCommission');
        /** END */

        /** get transaction for drivers commission */
        foreach($driverCommissions as $driverCommissionKey => $driverCommission){

            $driverCreditAccount = Account::where('accountable_id', $driverCommissionKey)
                ->where('accountable_type', 'App\Staff')
                ->where('prefix', 'Commission')
                ->first();
            $debitAccount = Account::where('id', $request->input('debit_account'))->first();

            recordTransaction($commission, $debitAccount, $creditAccount, [
                'date' => carbon()->now()->toDateString(),
                'type' => 'Deposit',
                'amount' => $driverCommission,
                'auto_narration' => 'The sales commission amount of '.$driverCommission.' was awarded to '.$driverCreditAccount->name,
                'manual_narration' => 'The sales commission amount of '.$driverCommission.' was awarded to '.$driverCreditAccount->name,
                'tx_type_id' => 51,
                'company_id' => $commission->getAttribute('company_id'),
            ], 'SalesCommission');
            /** END */

        }

        /** get transaction for labours commission */
        foreach($labourCommissions as $labourCommissionKey => $labourCommission){

            $labourCreditAccount = Account::where('accountable_id', $labourCommissionKey)
                ->where('accountable_type', 'App\Staff')
                ->where('prefix', 'Commission')
                ->first();
            $debitAccount = Account::where('id', $request->input('debit_account'))->first();

            recordTransaction($commission, $debitAccount, $creditAccount, [
                'date' => carbon()->now()->toDateString(),
                'type' => 'Deposit',
                'amount' => $labourCommission,
                'auto_narration' => 'The sales commission amount of '.$labourCommission.' was awarded to '.$labourCreditAccount->name,
                'manual_narration' => 'The sales commission amount of '.$labourCommission.' was awarded to '.$labourCreditAccount->name,
                'tx_type_id' => 51,
                'company_id' => $commission->getAttribute('company_id'),
            ], 'SalesCommission');
            /** END */

        }

        alert()->success('Sales commission confirmed successfully', 'Success')->persistent();
        return redirect()->route('finance.commission.show', $commission);
    }

    public function creditSales(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('credit-sales');

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($year, $month, 1);

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        /** get credit sales */
        $creditOrders = $this->commission->getCreditSales($rep, $startDate, $endDate)['creditOrders'];

        return view('finance.commission._inc.credit-sales', compact('breadcrumb', 'creditOrders', 'rep'));
    }

    public function totalSales(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('total-sales');

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($year, $month, 1);

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        /** get total sales */
        $totalSales = $this->commission->getTotalSalesOrders($rep, $startDate, $endDate);

        return view('finance.commission._inc.total-sales', compact('breadcrumb', 'totalSales', 'rep'));
    }

    public function chequeReceived(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('cheque-received');

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($year, $month, 1);

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        $chequeReceivedData = $this->commission->getChequeReceived($rep, $startDate, $endDate);
        /** get total cheques received */
        $chequesReceived = $chequeReceivedData['payments'];

        /** get total cheques received */
        $chequeReceivedAmount = $chequeReceivedData['chequeReceived'];

        return view('finance.commission._inc.cheque-received', compact('breadcrumb', 'chequesReceived', 'chequeReceivedAmount', 'rep'));
    }

    public function cashCollection(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('cash-collection');

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($year, $month, 1);

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        /** get total cash collection */
        $cashCollection = $this->commission->getCashCollection($rep, $startDate, $endDate)['payments'];

        return view('finance.commission._inc.cash-collection', compact('breadcrumb', 'cashCollection', 'rep'));
    }

    public function chequeCollection(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('cheque-collection');

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($year, $month, 1);

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        $chequeCollectionData = $this->commission->getChequeCollection($rep, $startDate, $endDate);

        /** get total cheque collection */
        $chequeCollection = $chequeCollectionData['payments'];

        /** get total cheque collection */
        $chequeCollectionAmount = $chequeCollectionData['chequeCollection'];

        return view('finance.commission._inc.cheque-collection', compact('breadcrumb', 'chequeCollection', 'chequeCollectionAmount', 'rep'));
    }

    public function salesReturns(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('sales-return');

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($year, $month, 1);

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        /** get sales returns */
        $salesReturns = $this->commission->getSalesReturnItems($rep, $startDate, $endDate);

        return view('finance.commission._inc.sales-return', compact('breadcrumb', 'salesReturns', 'rep'));
    }

    public function expiredSalesReturns(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('sales-return');

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($year, $month, 1);

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        /** get expired sales returns */
        $expiredSalesReturns = $this->commission->getExpiredSalesReturnItems($rep, $startDate, $endDate);

        return view('finance.commission._inc.sales-return-expired', compact('breadcrumb', 'expiredSalesReturns', 'rep'));
    }

    public function chequeReturned(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('cheque-returned');

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($year, $month, 1);

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        /** get total cheques returned in current month */
        $chequeReturned = $this->commission->getChequeReturnedItems($rep, $startDate, $endDate);

        /** get total cheques returned in current month */
        $chequeReturnedAmount = $this->commission->getChequeReturned($rep, $startDate, $endDate);

        return view('finance.commission._inc.cheques-returned', compact('breadcrumb', 'chequeReturned', 'chequeReturnedAmount', 'rep'));
    }

    public function chequeRealized(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('cheque-realized');

        /** prepare start and end dates */
        $prevMonth = carbon()->setDate($year, $month, 1)->subMonth();

        /** last month start and end date */
        $lmStartDate = $prevMonth->copy()->startOfMonth()->toDateString();
        $lmEndDate = $prevMonth->copy()->endOfMonth()->toDateString();

        /** get total cheques realized in last month */
        $chequeRealized = $this->commission->getChequeRealizedItems($rep, $lmStartDate, $lmEndDate);

        /** get total cheques realized in last month */
        $chequeRealizedAmount = $this->commission->getChequeRealized($rep, $lmStartDate, $lmEndDate);

        return view('finance.commission._inc.cheque-realized', compact('breadcrumb', 'chequeRealized', 'chequeRealizedAmount', 'rep'));
    }

    public function cashShortages(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('cash-shortages');

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($year, $month, 1);

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        /** get cash shortages & total */
        $cashShortages = SalesHandoverShortage::whereBetween('date', [$startDate, $endDate])->where('rep_id', $rep->getAttribute('id'))->get();
        $cashShortagesTotal = $cashShortages->sum('amount');

        return view('finance.commission._inc.cash-shortages', compact('breadcrumb', 'cashShortages', 'cashShortagesTotal', 'rep'));
    }

    public function stockShortages(Rep $rep, $year, $month)
    {
        $breadcrumb = $this->commission->breadcrumbs('stock-shortages');

        /** prepare start and end dates */
        $currentMonth = carbon()->setDate($year, $month, 1);

        /** current month start and end date */
        $startDate = $currentMonth->copy()->startOfMonth()->toDateString();
        $endDate = $currentMonth->copy()->endOfMonth()->toDateString();

        /** get stock shortages & total */
        $stockShortages = StockShortage::whereBetween('date', [$startDate, $endDate])->where('rep_id', $rep->getAttribute('id'))
            ->pluck('id')->toArray();

        $stockShortagesItems = StockShortageItem::where('status', 'Approved')
            ->whereIn('stock_shortage_id', $stockShortages)->get();

        $stockShortagesTotal = StockShortageItem::where('status', 'Approved')
            ->whereIn('stock_shortage_id', $stockShortages)->sum('amount');

        return view('finance.commission._inc.stock-shortages', compact('breadcrumb', 'stockShortagesItems', 'stockShortagesTotal', 'rep'));
    }

}
