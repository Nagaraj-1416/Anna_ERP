<?php

namespace App\Repositories\Finance;

use App\Account;
use App\AccountType;
use App\ChequeInHand;
use App\Http\Requests\Finance\AccountStoreRequest;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class ChequesInHandRepository
 * @package App\Repositories\Finance
 */
class ReturnedChequesRepository extends BaseRepository
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
        $type = $request->input('chequeType');
        $company = $request->input('company');
        $customer = $request->input('customer');

        $cheques = ChequeInHand::where('status', 'Bounced')
            ->where('cheque_date', '>=', $fromDate)
            ->where('cheque_date', '<=', $toDate);

        if($type){
            $cheques = $cheques->where('cheque_type', $type);
        }

        if($company){
            $cheques = $cheques->where('company_id', $company);
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
                ['text' => 'Returned Cheques'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Returned Cheques'],
                ['text' => 'Cheque Details'],
            ],
            'create-payment' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Returned Cheques'],
                ['text' => 'Make Payment'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}