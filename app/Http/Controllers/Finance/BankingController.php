<?php

namespace App\Http\Controllers\Finance;

use App\Account;
use App\ChequeInHand;
use App\Http\Controllers\Controller;
use App\Repositories\Finance\BankingRepository;

class BankingController extends Controller
{
    /**
     * @var BankingRepository
     */
    protected $banking;

    /**
     * BankingController constructor.
     * @param BankingRepository $banking
     */
    public function __construct(BankingRepository $banking)
    {
        $this->banking = $banking;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $now = carbon()->now()->toDateString();

        $breadcrumb = $this->banking->breadcrumbs('index');

        $cashAccounts = Account::where('account_type_id', 1)->get();
        $bankAccounts = Account::whereIn('account_type_id', [2, 19])->get();

        $todayCIHs = groupByCallbackForCheque(ChequeInHand::where('cheque_date', $now)->get());

        $totalCIHs = groupByCallbackForCheque(ChequeInHand::get());
        $notRealisedCIHs = groupByCallbackForCheque(ChequeInHand::where('status', 'Not Realised')->get());
        $depositedCIHs = groupByCallbackForCheque(ChequeInHand::where('status', 'Deposited')->get());
        $realisedCIHs = groupByCallbackForCheque(ChequeInHand::where('status', 'Realised')->get());

        return view('finance.banking.index',
            compact('breadcrumb', 'cashAccounts', 'bankAccounts', 'todayCIHs', 'totalCIHs', 'notRealisedCIHs', 'depositedCIHs', 'realisedCIHs'));
    }

}
