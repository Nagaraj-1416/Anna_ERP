<?php

namespace App\Http\Controllers\Finance;

use App\Account;
use App\Http\Controllers\Controller;
use App\Repositories\Finance\GeneralLedgerRepository;

class GeneralLedgerController extends Controller
{
    protected $generalLedger;

    /**
     * GeneralLedgerController constructor.
     * @param GeneralLedgerRepository $generalLedger
     */
    public function __construct(GeneralLedgerRepository $generalLedger)
    {
        $this->generalLedger = $generalLedger;
    }

    public function index()
    {
        $breadcrumb = $this->breadcrumbs('index');
        if (request()->ajax()) {
            $data = $this->generalLedger->generalLedger();
            return response()->json($data);
        }
        return view('finance/statements/general-ledger/ledger', compact('breadcrumb'));
    }

    public function getCustomerAndSuppliers()
    {
        return response()->json($this->generalLedger->getCustomersAndSuppliers());
    }

    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'General Ledger'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
