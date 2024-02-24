<?php

namespace App\Repositories\Sales;

use App\Account;
use App\DailySale;
use App\Repositories\BaseRepository;
use App\SalesExpense;
use App\Transaction;
use App\TransactionRecord;
use Illuminate\Http\Request;

/**
 * Class InquiryRepository
 * @package App\Repositories\Sales
 */
class SalesExpenseRepository extends BaseRepository
{
    /**
     * SalesExpenseRepository constructor.
     * @param SalesExpense|null $expense
     */
    public function __construct(SalesExpense $expense = null)
    {
        $this->setModel($expense ?? new SalesExpense());
        $this->setCodePrefix('SE', 'code');
    }

    /**
     * @return mixed
     */
    public function todayIndex()
    {
        $allocations = getRepAllocation();
        $allocation = $allocations->first();
        $expenses =  SalesExpense::where(function ($q) use ( $allocation) {
            $q->where(function ($q) use ($allocation) {
                $q->whereBetween('expense_date', [$allocation->from_date, $allocation->to_date])->where('prepared_by', auth()->id());
            });
        })->with(['company', 'type'])->get();

        $expenses = $expenses->transform(function (SalesExpense $expense) {
            $expense->type_name = $expense->type->name;
            return $expense;
        });

        return $expenses;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(Request $request)
    {
        $expense = $this->storeData($request);
        return $expense;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function storeData(Request $request)
    {
        $allocation = getRepAllocation()->first();

        $expense = new SalesExpense();
        $expense->setAttribute('code', $this->getCode());
        $expense->setAttribute('expense_date', $request->input('expense_date'));
        $expense->setAttribute('expense_time', $request->input('expense_time'));
        $expense->setAttribute('notes', $request->input('notes'));
        $expense->setAttribute('amount', $request->input('amount'));
        $expense->setAttribute('prepared_by', auth()->id());
        $expense->setAttribute('staff_id', $allocation->rep->staff->id);
        $expense->setAttribute('company_id', $allocation->company_id);
        $expense->setAttribute('daily_sale_id', $allocation->id);
        $expense->setAttribute('gps_lat', $request->input('gps_lat'));
        $expense->setAttribute('gps_long', $request->input('gps_long'));
        $expense->setAttribute('liter', $request->input('liter'));
        $expense->setAttribute('odometer', $request->input('odometer'));
        $expense->setAttribute('type_id', $request->input('type_id'));
        $expense->save();

        //$this->recordExpenseTransaction($allocation, $expense);

        return $expense;
    }

    /**
     * @param Request $request
     * @param DailySale $allocation
     */
    public function store(Request $request, DailySale $allocation)
    {
        $expense = new SalesExpense();
        $expense->setAttribute('code', $this->getCode());
        $expense->setAttribute('expense_date', $request->input('expense_date'));
        $expense->setAttribute('notes', $request->input('notes'));
        $expense->setAttribute('amount', $request->input('amount'));
        $expense->setAttribute('prepared_by', auth()->user()->id);
        $expense->setAttribute('staff_id', $allocation->rep->staff->id);
        $expense->setAttribute('company_id', $allocation->company_id);
        $expense->setAttribute('daily_sale_id', $allocation->id);
        $expense->setAttribute('sales_handover_id', $allocation->salesHandover->id);
        $expense->setAttribute('liter', $request->input('liter'));
        $expense->setAttribute('odometer', $request->input('odometer'));
        $expense->setAttribute('type_id', $request->input('type_id'));
        $expense->save();

        //$this->recordExpenseTransaction($allocation, $expense);
    }

    public function recordExpenseTransaction(DailySale $allocation, SalesExpense $expense)
    {
        /** get company related cash account */
        $creditAccount = Account::where('account_type_id', 1)->where('accountable_id', $allocation->rep_id)
            ->where('accountable_type', 'App\Rep')->first();

        $debitAccount = Account::find(27);
        recordTransaction($expense, $debitAccount, $creditAccount, [
            'date' => $expense->getAttribute('expense_date'),
            'type' => 'Deposit',
            'amount' => $expense->getAttribute('amount'),
            'auto_narration' => 'Expense amount paid for '.$expense->getAttribute('notes'),
            'manual_narration' => 'Expense amount paid for '.$expense->getAttribute('notes'),
            'tx_type_id' => 1,
            'company_id' => $expense->getAttribute('company_id'),
        ], 'Expense');
        /** END */
    }

    /**
     * @param SalesExpense $expense
     * @return array
     */
    public function delete(SalesExpense $expense): array
    {
        try {
            $transaction = $expense->transaction;
            $transaction->records()->delete();
            $transaction->delete();
            $expense->delete();
            return ['success' => true, 'message' => 'Deleted success'];
        } catch (\Exception $e) {
            return ['success' => true, 'message' => 'Deleted failed'];
        }

    }

}