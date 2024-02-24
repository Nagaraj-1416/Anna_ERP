<?php

namespace App\Http\Controllers\Sales;

use App\Account;
use App\DailySale;
use App\Http\Requests\Sales\HandoverRequest;
use App\Http\Requests\Sales\HandoverUpdateRequest;
use App\Jobs\NotifyAllocationToStore;
use App\Rep;
use App\Repositories\Sales\HandOverRepository;
use App\SalesHandover;
use App\SalesHandoverExcess;
use App\SalesHandoverShortage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HandOverController extends Controller
{
    public $handover;

    public function __construct(HandOverRepository $handover)
    {
        $this->handover = $handover;
    }

    /**
     * @param DailySale $allocation
     * @param SalesHandover $handover
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function approval(DailySale $allocation, SalesHandover $handover)
    {
        if (isCashierLevelStaff() && $handover->is_cashier_approved == 'Yes') {
            alert()->warning('Sales handover already approved by cashier!', 'Waring')->persistent();
            return redirect()->route('sales.allocation.show', [$allocation]);
        }

        if (isStoreLevelStaff() && $handover->is_sk_approved == 'Yes') {
            alert()->warning('Sales handover already approved by Store Keeper!', 'Waring')->persistent();
            return redirect()->route('sales.allocation.show', [$allocation]);
        }

        if ($handover->status == 'Confirmed') {
            alert()->warning('Sales handover already approved!', 'Waring')->persistent();
            return redirect()->route('sales.allocation.show', [$allocation]);
        }

        $breadcrumb = $this->handover->breadcrumbs('show', $handover);
        $cheques = $this->handover->getCheques($allocation);
        $returns = $allocation->returns()->with('resolutions')->get() ?? collect();
        $refundedAmount = $returns->pluck('resolutions')->collapse()->where('resolution', 'Refund')->sum('amount');
        $expenses = $this->handover->getExpenses($handover);
        $products = $allocation->items;
        $returnItems = $allocation->returns()->with('items')->get()->pluck('items')->collapse();

        if ($expenses) {
            $handover->total_expense = $expenses->sum('amount');
        }

        return view('sales.allocation.handover.approval', compact('handover',
            'breadcrumb', 'allocation', 'cheques', 'products', 'expenses', 'refundedAmount', 'returnItems'));
    }

    public function save(DailySale $allocation, SalesHandover $handover, HandoverRequest $request)
    {
        $this->handover->approve($allocation, $handover, $request);

        /** create daily stock allocation request and notify to store keeper / manager / administrator */

        /** send email notification  */
        //dispatch(new NotifyAllocationToStore($allocation));

        alert()->success('Sales handover confirmed successfully.', 'Success')->persistent();
        return redirect()->route('sales.allocation.show', [$allocation]);
    }

    /**
     * @param DailySale $allocation
     * @param SalesHandover $handover
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(DailySale $allocation, SalesHandover $handover)
    {
        $returns = $allocation->returns()->with('resolutions')->get() ?? collect();
        $refundedAmount = $returns->pluck('resolutions')->collapse()->where('resolution', 'Refund')->sum('amount');
        $expenses = $handover->salesExpenses()->with('expense')->get() ?? collect();
        $expensesNew = $expenses->pluck('expense');
        $handover->total_expense = $expenses->sum('amount');
        $handover->total_new_expense = $expensesNew->sum('amount');
        $breadcrumb = $this->handover->breadcrumbs('edit', $handover);
        return view('sales.allocation.handover.edit', compact('handover',
            'breadcrumb', 'allocation', 'refundedAmount'));
    }

    /**
     * @param HandoverUpdateRequest $request
     * @param DailySale $allocation
     * @param SalesHandover $handover
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(HandoverUpdateRequest $request, DailySale $allocation, SalesHandover $handover)
    {
        $cashSales = $request->input('cash_sales');
        $chequeSales = $request->input('cheque_sales');
        $depositSales = $request->input('deposit_sales');
        $cardSales = $request->input('card_sales');
        $creditSales = $request->input('credit_sales');

        $sales = ($cashSales + $chequeSales + $depositSales + $cardSales + $creditSales);
        $handover->setAttribute('sales', $sales);

        $handover->setAttribute('cash_sales', $cashSales);
        $handover->setAttribute('cheque_sales', $chequeSales);
        $handover->setAttribute('deposit_sales', $depositSales);
        $handover->setAttribute('card_sales', $cardSales);
        $handover->setAttribute('credit_sales', $creditSales);

        $oldCashSales = $request->input('old_cash_sales');
        $oldChequeSales = $request->input('old_cheque_sales');
        $oldDepositSales = $request->input('old_deposit_sales');
        $oldCardSales = $request->input('old_card_sales');
        $oldCreditSales = $request->input('old_credit_sales');

        $oldSales = ($oldCashSales + $oldChequeSales + $oldDepositSales + $oldCardSales + $oldCreditSales);
        $handover->setAttribute('old_sales', $oldSales);

        $handover->setAttribute('old_cash_sales', $oldCashSales);
        $handover->setAttribute('old_cheque_sales', $oldChequeSales);
        $handover->setAttribute('old_deposit_sales', $oldDepositSales);
        $handover->setAttribute('old_card_sales', $oldCardSales);
        $handover->setAttribute('old_credit_sales', $oldCreditSales);

        $shortage = $request->input('shortage');
        $excess = $request->input('excess');

        $handover->setAttribute('shortage', $shortage);
        $handover->setAttribute('excess', $excess);

        $handover->setAttribute('total_collect', ($sales + $oldSales));
        $handover->save();

        /** update shortage amount  */
        $repShortage = SalesHandoverShortage::where('daily_sale_id', $allocation->getAttribute('id'))
            ->where('sales_handover_id', $handover->getAttribute('id'))
            ->where('rep_id', $allocation->getAttribute('rep_id'))
            ->first();
        if($repShortage){
            /** need to remove the relevant transaction */
            $transaction = $repShortage->transaction;
            if($transaction){
                $transaction->records()->delete();
                $transaction->delete();
            }
            /** end */

            /** remove available shortage */
            $repShortage->delete();
        }

        if($shortage > 0){
            /** need to create new shortage record and transaction */
            $handoverShortage = new SalesHandoverShortage();
            $handoverShortage->daily_sale_id = $allocation->id;
            $handoverShortage->sales_handover_id = $handover->id;
            $handoverShortage->rep_id = $allocation->rep_id;
            $handoverShortage->date = carbon()->toDateString();
            $handoverShortage->amount = (float)$shortage;
            $handoverShortage->submitted_by = auth()->user()->id;
            $handoverShortage->save();

            /** ADD TRANSACTION */
            /** get staff & user details */
            $rep = Rep::where('id', $handoverShortage->getAttribute('rep_id'))->first();
            if($rep){
                $staff = $rep->staff;
                if($staff){
                    /** get rep related cash account */
                    $creditAccount = Account::where('account_type_id', 1)->where('accountable_id', $handoverShortage->getAttribute('rep_id'))
                        ->where('accountable_type', 'App\Rep')->first();

                    /** get staff related account */
                    $debitAccount = Account::where('accountable_id', $staff->id)
                        ->where('accountable_type', 'App\Staff')->first();

                    recordTransaction($handoverShortage, $debitAccount, $creditAccount, [
                        'date' => $handoverShortage->getAttribute('date'),
                        'type' => 'Deposit',
                        'amount' => $handoverShortage->getAttribute('amount'),
                        'auto_narration' => 'The shortage amount of '.$handoverShortage->getAttribute('amount').' was identified during the sales',
                        'manual_narration' => 'The shortage amount of '.$handoverShortage->getAttribute('amount').' was identified during the sales',
                        'tx_type_id' => 37,
                        'company_id' => $handover->getAttribute('company_id'),
                    ], 'CashShortage');
                    /** END */
                }
            }
            /** END */
        }

        /** update excess amount  */
        $repExcess = SalesHandoverExcess::where('daily_sale_id', $allocation->getAttribute('id'))
            ->where('sales_handover_id', $handover->getAttribute('id'))
            ->where('rep_id', $allocation->getAttribute('rep_id'))
            ->first();
        if($repExcess){
            /** need to remove the relevant transaction */
            $transaction = $repExcess->transaction;
            if($transaction){
                $transaction->records()->delete();
                $transaction->delete();
            }
            /** end */

            /** remove available excess */
            $repExcess->delete();
        }

        if($excess > 0){
            /** need to create new excess record and transaction */
            $handoverExcess = new SalesHandoverExcess();
            $handoverExcess->daily_sale_id = $allocation->id;
            $handoverExcess->sales_handover_id = $handover->id;
            $handoverExcess->rep_id = $allocation->rep_id;
            $handoverExcess->date = carbon()->toDateString();
            $handoverExcess->amount = (float)$excess;
            $handoverExcess->submitted_by = auth()->user()->id;
            $handoverExcess->save();

            /** ADD TRANSACTION */
            /** get rep related cash account */
            $debitAccount = Account::where('account_type_id', 1)->where('accountable_id', $handoverExcess->getAttribute('rep_id'))
                ->where('accountable_type', 'App\Rep')->first();

            /** get general excess related account */
            $creditAccount = Account::find(105);

            recordTransaction($handoverExcess, $debitAccount, $creditAccount, [
                'date' => $handoverExcess->getAttribute('date'),
                'type' => 'Deposit',
                'amount' => $handoverExcess->getAttribute('amount'),
                'auto_narration' => 'The excess amount of '.$handoverExcess->getAttribute('amount').' was identified during the sales',
                'manual_narration' => 'The excess amount of '.$handoverExcess->getAttribute('amount').' was identified during the sales',
                'tx_type_id' => 39,
                'company_id' => $handover->getAttribute('company_id'),
            ], 'CashExcess');
            /** END */
        }

        alert()->success('Sales handover details updated successfully.', 'Success')->persistent();
        return redirect()->route('sales.allocation.show', [$allocation]);
    }

}
