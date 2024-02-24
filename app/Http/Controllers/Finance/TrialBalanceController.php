<?php

namespace App\Http\Controllers\Finance;

use App\Account;
use App\Http\Controllers\Controller;
use App\Repositories\Finance\TrialBalanceRepository;

class TrialBalanceController extends Controller
{
    protected $trialBalance;

    /**
     * TrialBalanceController constructor.
     * @param TrialBalanceRepository $trialBalance
     */
    public function __construct(TrialBalanceRepository $trialBalance)
    {
        $this->trialBalance = $trialBalance;
    }

    public function index()
    {
        $breadcrumb = $this->breadcrumbs('index');
        if (request()->ajax()) {
            $data = $this->trialBalance->trialBalance();
            return response()->json($data);
        }
        return view('finance/statements/trial-balance/index', compact('breadcrumb'));
    }

    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Trial Balance'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
