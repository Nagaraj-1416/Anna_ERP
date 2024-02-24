<?php

namespace App\Http\Controllers\Finance;

use App\Account;
use App\Http\Controllers\Controller;

class AccountBalanceController extends Controller
{
    public function index()
    {
        $breadcrumb = $this->breadcrumbs('index');
        $accounts = Account::get()->groupBy('account_category_id');
        return view('finance.statements.account-balance.index', compact('breadcrumb', 'accounts'));
    }

    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Account Balances'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
