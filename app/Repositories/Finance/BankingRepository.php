<?php

namespace App\Repositories\Finance;

use App\Account;
use App\AccountType;
use App\Http\Requests\Finance\AccountStoreRequest;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class BankingRepository
 * @package App\Repositories\Finance
 */
class BankingRepository extends BaseRepository
{
    /**
     * Get the breadcrumbs of the account module
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
                ['text' => 'Banking Overview'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}