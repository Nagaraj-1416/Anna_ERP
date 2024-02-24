<?php

namespace App\Repositories\Finance;

use App\Repositories\BaseRepository;
use App\Transaction;
use Illuminate\Http\Request;

/**
 * Class AccountRepository
 * @package App\Repositories\Finance
 */
class TransactionRepository extends BaseRepository
{
    /**
     * TransactionRepository constructor.
     * @param Transaction|null $trans
     */
    public function __construct(Transaction $trans = null)
    {
        $this->setModel($trans ?? new Transaction());
        $this->setCodePrefix('TX', 'code');
    }

    /**
     * index grid backend
     * @return mixed
     */
    public function index()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $trans = Transaction::where('category', 'Manual')
            ->whereIn('company_id', userCompanyIds(loggedUser()))
            ->orderBy('id', 'desc')->with('txType');
        if ($search) {
            $trans->where('code', 'LIKE', '%' . $search . '%')
                ->orWhere('date', 'LIKE', '%' . $search . '%')
                ->orWhere('amount', 'LIKE', '%' . $search . '%')
                ->orWhere('type', 'LIKE', '%' . $search . '%');
        }
        switch ($filter) {
            case 'recentlyCreated':
                $trans->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $trans->where('updated_at', '>', $lastWeek);
                break;
        }

        return $trans->paginate(15)->toArray();
    }

    public function manualTrans()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $trans = Transaction::where('category', 'Manual')
            ->whereIn('company_id', userCompanyIds(loggedUser()))
            ->orderBy('id', 'desc')->with('txType');
        if ($search) {
            $trans->where('code', 'LIKE', '%' . $search . '%')
                ->orWhere('date', 'LIKE', '%' . $search . '%')
                ->orWhere('amount', 'LIKE', '%' . $search . '%')
                ->orWhere('type', 'LIKE', '%' . $search . '%');
        }
        switch ($filter) {
            case 'recentlyCreated':
                $trans->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $trans->where('updated_at', '>', $lastWeek);
                break;
        }

        return $trans->paginate(15)->toArray();
    }

    /**
     * create new transaction
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(Request $request)
    {
        return $this->storeData($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(Request $request)
    {
        return $this->storeData($request);
    }

    /**
     * Delete transaction
     * @return array
     */
    public function delete()
    {
        try {
            $this->model->records()->delete();
            $this->model->delete();
            return ['success' => true, 'message' => 'Transaction deleted success!'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Transaction deleted failed!'];
        }
    }

    /**
     * Store or update transaction
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function storeData(Request $request)
    {
        if (!$this->model->id) {
            $request->merge(['code' => $this->getCode()]);
            $request->merge(['prepared_by' => auth()->id()]);
            $request->merge(['category' => 'Manual']);
            if($request->input('tx_type_id') == '7'){
                $request->merge(['action' => 'SalesReturn']);
            }
            $request->merge(['auto_narration' => $request->input('manual_narration')]);
            $request->merge(['company_id' => $request->input('company_id')]);
        }

        $request->merge(['amount' => array_sum($request->input('debit'))]);
        $this->model->fill($request->all());
        $this->model->save();
        $records = $this->mapRecords($request, $this->model);
        if ($this->model->id) {
            $this->model->records()->delete();
        }
        $this->model->records()->createMany($records);
        return $this->model;
    }

    /**
     * Map transaction records
     * @param Request $request
     * @param Transaction|null $transaction
     * @return array
     */
    public function mapRecords(Request $request, Transaction $transaction = null): array
    {
        $records = [];
        $debits = $request->input('debit');
        $credits = $request->input('credit');
        foreach ($request->input('account_id') as $key => $account) {
            $debit = isset($debits[$key]) ? $debits[$key] : 0;
            $credit = isset($credits[$key]) ? $credits[$key] : 0;
            $amount = ($debit == 0) ? $credit : $debit;
            $type = ($debit == 0) ? 'Credit' : 'Debit';
            array_push($records, [
                'account_id' => $account,
                'date' => $request->input('date'),
                'amount' => $amount,
                'type' => $type,
                'transaction_id' => $transaction->id ?? null
            ]);
        }
        return $records;
    }

    /**
     * @param string $method
     * @param Transaction|null $trans
     * @return array
     */
    public function breadcrumbs(string $method, Transaction $trans = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Transactions'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Transactions', 'route' => 'finance.trans.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Transactions', 'route' => 'finance.trans.index'],
                ['text' => $trans->code ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Transactions', 'route' => 'finance.trans.index'],
                ['text' => $trans->code ?? ''],
                ['text' => 'Edit'],
            ],
            'clone' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Transactions', 'route' => 'finance.trans.index'],
                ['text' => $trans->code ?? ''],
                ['text' => 'Clone'],
            ],
            'print' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Transactions', 'route' => 'finance.trans.index'],
                ['text' => $trans->code ?? ''],
                ['text' => 'Print'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}