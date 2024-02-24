<?php

namespace App\Http\Controllers\Finance;

use App\Account;
use App\ChequeInHand;
use App\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\ChequeStoreRequest;
use App\Repositories\Finance\ChequesInHandRepository;
use Illuminate\Http\Request;

class ChequesInHandController extends Controller
{
    /**
     * @var ChequesInHandRepository
     */
    protected $cheque;

    /**
     * BankingController constructor.
     * @param ChequesInHandRepository $cheque
     */
    public function __construct(ChequesInHandRepository $cheque)
    {
        $this->cheque = $cheque;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumb = $this->cheque->breadcrumbs('index');

        if (\request()->ajax()) {
            $cheques = $this->cheque->grid();
            return response()->json($cheques);
        }
        return view('finance.banking.cheques-in-hand.index', compact('breadcrumb'));
    }

    public function create()
    {
        $breadcrumb = $this->cheque->breadcrumbs('create');
        return view('finance.banking.cheques-in-hand.create', compact('breadcrumb'));
    }

    public function store(ChequeStoreRequest $request)
    {
        $this->cheque->save($request);
        alert()->success('Cheque created successfully', 'Success')->persistent();
        return redirect()->route('finance.banking.index');
    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function registeredBy()
    {
        $breadcrumb = $this->cheque->breadcrumbs('registered-by');

        if (\request()->ajax()) {
            $cheques = $this->cheque->gridRegisteredBy();
            return response()->json($cheques);
        }
        return view('finance.banking.cheques-in-hand.registered-by', compact('breadcrumb'));
    }

    public function realise($chequeKey)
    {
        $breadcrumb = $this->cheque->breadcrumbs('realise');
        return view('finance/banking/cheques-in-hand/realise', compact('breadcrumb', 'chequeKey'));
    }

    public function doRealise($chequeKey)
    {
        /** get cheques in hand data */
        $cheques = ChequeInHand::where(chequeKeyToArray($chequeKey, 'query'))->get();

        /** update deposited to column and status */
        foreach ($cheques as $cheque){
            $cheque->status = 'Realised';
            $cheque->save();
        }

        alert()->success('The cheque marked as realised successfully!', 'Success')->persistent();
        return redirect()->route('finance.cheques.hand.index');
    }

    protected function recordRealiseTransaction(Request $request)
    {
        $debitAccount = Account::find($request->input('deposited_to'));
        $creditAccount = Account::find($request->input('credited_to'));
        recordTransaction($debitAccount, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $request->input('cheque_amount'),
            'auto_narration' => 'Cheque (#'.$request->input('cheque_no').') amount of '.number_format($request->input('cheque_amount')).' was deposited and realised to '.$debitAccount->name,
            'manual_narration' => 'Cheque (#'.$request->input('cheque_no').') amount of '.number_format($request->input('cheque_amount')).' was deposited and realised to '.$debitAccount->name,
            'tx_type_id' => 34,
            'company_id' => $debitAccount->company->id,
            'customer_id' => $request->input('cheque_customer'),
        ], 'ChequeRealised');
    }

    public function bounce($chequeKey)
    {
        $breadcrumb = $this->cheque->breadcrumbs('bounce');
        return view('finance/banking/cheques-in-hand/bounce', compact('breadcrumb', 'chequeKey'));
    }

    public function doBounce($chequeKey)
    {
        $request = request();

        $request->validate([
            'bounced_date' => 'required|after_or_equal:'.$request->input('cheque_date')
        ]);

        /** get cheques in hand data */
        $cheques = ChequeInHand::where(chequeKeyToArray($chequeKey, 'query'))->get();
        foreach ($cheques as $cheque){
            $cheque->bounced_date = $request->input('bounced_date');
            $cheque->status = 'Bounced';
            $cheque->save();
        }

        /** record transaction */
        $this->recordBounceTransaction($request);

        alert()->success('The cheque marked as bounced successfully!', 'Success')->persistent();
        return redirect()->route('finance.cheques.deposited');
    }

    protected function recordBounceTransaction(Request $request)
    {
        /** transaction one */
        $debitAccount = Account::find($request->input('credited_to'));
        $creditAccount = Account::find($request->input('deposited_to'));
        recordTransaction($debitAccount, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $request->input('cheque_amount'),
            'auto_narration' => 'Cheque (#'.$request->input('cheque_no').') amount of '.number_format($request->input('cheque_amount')).' was bounced',
            'manual_narration' => 'Cheque (#'.$request->input('cheque_no').') amount of '.number_format($request->input('cheque_amount')).' was bounced',
            'tx_type_id' => 35,
            'company_id' => $debitAccount->company->id,
            'customer_id' => null,
        ], 'ChequeBounced');

        /** transaction two */
        $debitAccount = Account::find($request->input('transferred_from'));
        $creditAccount = Account::find($request->input('transferred_to'));
        recordTransaction($debitAccount, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $request->input('cheque_amount'),
            'auto_narration' => 'Cheque (#'.$request->input('cheque_no').') amount of '.number_format($request->input('cheque_amount')).' was bounced and debited back to '.$debitAccount->name,
            'manual_narration' => 'Cheque (#'.$request->input('cheque_no').') amount of '.number_format($request->input('cheque_amount')).' was bounced and debited back to '.$debitAccount->name,
            'tx_type_id' => 35,
            'company_id' => $debitAccount->company->id,
            'customer_id' => null,
        ], 'ChequeBounced');

        /** transaction three */
        $debitAccount = Account::find(3);
        $creditAccount = Account::find($request->input('transferred_from'));
        $customerAccount = Customer::find($request->input('cheque_customer'));
        recordTransaction($debitAccount, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $request->input('cheque_amount'),
            'auto_narration' => 'Cheque (#'.$request->input('cheque_no').') amount of '.number_format($request->input('cheque_amount')).' was bounced and debited back to '.$customerAccount->display_name,
            'manual_narration' => 'Cheque (#'.$request->input('cheque_no').') amount of '.number_format($request->input('cheque_amount')).' was bounced and debited back to '.$customerAccount->display_name,
            'tx_type_id' => 35,
            'company_id' => $debitAccount->company->id,
            'customer_id' => $request->input('cheque_customer'),
        ], 'ChequeBounced');
    }

    public function deposit($chequeKey)
    {
        $breadcrumb = $this->cheque->breadcrumbs('bounce');
        return view('finance/banking/cheques-in-hand/deposit', compact('breadcrumb', 'chequeKey'));
    }

    public function doDeposit($chequeKey)
    {
        $request = request();
        $this->validate($request, [
            'credited_to' => 'required',
            'deposited_to' => 'required'
        ]);

        /** get cheques in hand data */
        $cheques = ChequeInHand::where(chequeKeyToArray($chequeKey, 'query'))->get();

        /** update deposited to column and status */
        foreach ($cheques as $cheque){
            $cheque->credited_to = $request->input('credited_to');
            $cheque->deposited_to = $request->input('deposited_to');
            $cheque->status = 'Deposited';
            $cheque->save();
        }

        /** record transaction */
        $this->recordDepositTransaction($request);

        alert()->success('Cheque deposited successfully!', 'Success')->persistent();
        return redirect()->route('finance.cheques.hand.index');
    }

    protected function recordDepositTransaction(Request $request)
    {
        $debitAccount = Account::find($request->input('deposited_to'));
        $creditAccount = Account::find($request->input('credited_to'));
        recordTransaction($debitAccount, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $request->input('cheque_amount'),
            'auto_narration' => 'Cheque (#'.$request->input('cheque_no').') amount of '.number_format($request->input('cheque_amount')).' was deposited',
            'manual_narration' => 'Cheque (#'.$request->input('cheque_no').') amount of '.number_format($request->input('cheque_amount')).' was deposited',
            'tx_type_id' => 36,
            'company_id' => $debitAccount->company->id,
            'customer_id' => null,
        ], 'ChequeDeposited');
    }

    public function deposited()
    {
        $breadcrumb = $this->cheque->breadcrumbs('deposited');

        if (\request()->ajax()) {
            $cheques = $this->cheque->gridByDeposited();
            return response()->json($cheques);
        }
        return view('finance/banking/cheques-in-hand/deposited', compact('breadcrumb'));
    }

}
