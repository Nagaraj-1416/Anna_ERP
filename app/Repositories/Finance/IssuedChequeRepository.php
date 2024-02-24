<?php

namespace App\Repositories\Finance;

use App\Account;
use App\IssuedCheque;
use App\Repositories\BaseRepository;

/**
 * Class IssuedChequeRepository
 * @package App\Repositories\Finance
 */
class IssuedChequeRepository extends BaseRepository
{

    /**
     * ChequesInHandRepository constructor.
     * @param IssuedCheque|null $cheque
     */
    public function __construct(IssuedCheque $cheque = null)
    {
        $this->setModel($cheque ?? new IssuedCheque());
    }

    public function grid()
    {
        $request = request();
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $status = $request->input('chequeStatus');
        $company = $request->input('company');
        $supplier = $request->input('supplier');

        $cheques = IssuedCheque::where('cheque_date', '>=', $fromDate)->where('cheque_date', '<=', $toDate);

        if($status){
            $cheques = $cheques->where('status', $status);
        }else{
            $cheques = $cheques->whereNotIn('status', ['Bounced','Canceled']);
        }

        if($company){
            $cheques = $cheques->where('company_id', $company);
        }

        if($supplier){
            $cheques = $cheques->where('supplier_id', $supplier);
        }

        $chequesTotal = $cheques->sum('amount');

        $cheques = groupByCallbackForCheque($cheques->with('bank', 'chequeable')
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
                ['text' => 'Banking Overview', 'route' => 'finance.banking.index'],
                ['text' => 'Issued Cheques'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Banking Overview', 'route' => 'finance.banking.index'],
                ['text' => 'Issued Cheques', 'route' => 'finance.issued.cheque.index'],
                ['text' => 'Create Cheque'],
            ],
            'realise' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Banking Overview', 'route' => 'finance.banking.index'],
                ['text' => 'Issued Cheques', 'route' => 'finance.issued.cheque.index'],
                ['text' => 'Mark as Realised'],
            ],
            'bounce' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Banking Overview', 'route' => 'finance.banking.index'],
                ['text' => 'Issued Cheques', 'route' => 'finance.issued.cheque.index'],
                ['text' => 'Mark as Bounced'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}