<?php

namespace App\Repositories\Sales;

use App\Account;
use App\ChequeInHand;
use App\ChequePayment;
use App\Customer;
use App\DailySale;
use App\Http\Resources\ChequePaymentResource;
use App\Invoice;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class PaymentRepository
 * @package App\Repositories\Sales
 */
class ChequePaymentRepository extends BaseRepository
{
    /**
     * ChequePaymentRepository constructor.
     * @param ChequePayment|null $payment
     */
    public function __construct(ChequePayment $payment = null)
    {
        $this->setModel($payment ?? new ChequePayment());
    }

    /**
     * @return mixed
     */
    public function apiIndex()
    {
        $todayAllocatedCustomers = todayAllocatedCustomers();
        $todayAllocatedCustomersId = $todayAllocatedCustomers->pluck('id')->toArray();
        $bouncedCheque = groupByCallbackForCheque(ChequeInHand::where('settled', 'No')
            ->where('status', 'Bounced')
            ->whereIn('customer_id', $todayAllocatedCustomersId)
            ->get());
        return $bouncedCheque->map(function ($item){
            $firstItem = $item->first();
            return [
                'cheque_date' => $firstItem->cheque_date,
                'cheque_no' => $firstItem->cheque_no,
                'bank' => $firstItem->bank->name ?? "N/A",
                'bank_id' => $firstItem->bank->id ?? null,
                'total' => $item->sum('amount'),
                'customer' => $firstItem->customer->display_name,
                'invoices' => $item->map(function ($invoicePayment){
                    $invoiceCode = "N/A";
                    if ($invoicePayment->chequeable_type === 'App\InvoicePayment'){
                        $invoiceCode = $invoicePayment->chequeable->invoice->ref ?? "N/A";
                    }
                    return [
                        'customer' => $invoicePayment->customer->display_name ?? "N/A",
                        'invoice' => $invoiceCode,
                        'amount' => $invoicePayment->amount,
                    ];
                }),
                'payments' => ChequePaymentResource::collection(
                    ChequePayment::where('cheque', $firstItem->cheque_no)
                        ->where('bank_id', $firstItem->bank_id)
                        ->with(['customer'])->get()
                )
            ];
        });
    }

    /**
     * @param Request $request
     * @param $chequeKey
     * @param bool $isApi
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(Request $request, $chequeKey, $isApi = false)
    {
        [$cheque, $bankId] = chequeKeyToArray($chequeKey);
        if($isApi){
            $allocation = getRepAllocation()->first();
        }else{
            $allocation = DailySale::where('id', $request->input('daily_sale_id'))->first();
        }

        $this->model->setAttribute('cheque', $cheque);

        $this->setPaymentAttributes($request, $isApi);

        if ($isApi) {
            $dailySale = getRepAllocation()->first();
            $this->model->setAttribute('daily_sale_id', $dailySale ? $dailySale->id : null);
            if ($request->input('created_at')){
                $createdAt = carbon($request->input('created_at'));
                $this->model->setAttribute('created_at', $createdAt->toDateTimeString());
            }
        }else{
            $this->model->setAttribute('daily_sale_id', $request->input('daily_sale_id'));
        }

        $this->model->setAttribute('customer_id', getChequeDataByNo($cheque)['customerId']);
        $this->model->setAttribute('rep_id', $allocation ? $allocation->rep_id : null);
        $this->model->setAttribute('route_id', $allocation ? $allocation->route_id : null);
        $this->model->setAttribute('company_id', $allocation ? $allocation->company_id : null);

        $this->model->save();

        if ($isApi){
            $this->updateVisited(getChequeDataByNo($chequeKey)['customerData'], $request);
        }

        /** update cheques in hand table settled status to Yes, if balance zero */
        $this->updateChequeSettlement($cheque, $bankId);

        /** add related transaction */
        $this->recordTransaction($this->model);

        return $this->model->refresh();
    }

    /**
     * @param Request $request
     * @param ChequePayment $payment
     * @param bool $isApi
     * @return ChequePayment
     */
    public function update(Request $request, ChequePayment $payment, $isApi = false)
    {
        $this->setModel($payment);

        $this->setPaymentAttributes($request, $isApi);

        /** remove payment related transaction */
        $transaction = $payment->transaction;
        $transaction->records()->delete();
        $transaction->delete();
        /** end */

        /** update cheques in hand table settled status to Yes, if balance zero */
        $this->updateChequeSettlement($payment->getAttribute('cheque'), $payment->getAttribute('bank_id'));

        /** add related transaction */
        $this->recordTransaction($payment);

        return $payment;
    }

    protected function updateChequeSettlement($cheque, $bankId)
    {
        /** get cheques in hand total */
        $chequesInHand = ChequeInHand::where('cheque_no', $cheque)->where('bank_id', $bankId)->get();
        $chequeTotal = $chequesInHand->sum('amount');

        $chequePayments = ChequePayment::where('cheque', $cheque)->where('bank_id', $bankId)->get();
        $totalPayments = $chequePayments->sum('payment');

        if($totalPayments == $chequeTotal){
            if($chequesInHand){
                $chequesInHand->each(function (ChequeInHand $chequeInHand) {
                    $chequeInHand->setAttribute('settled', 'Yes');
                    $chequeInHand->save();
                });
            }
        }
    }

    protected function recordTransaction(ChequePayment $payment, $isEdit = false)
    {
        $debitAccount = Account::find($payment->deposited_to);
        $creditAccount = Account::find(3);
        recordTransaction($payment, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $payment->payment,
            'auto_narration' => 'Returned cheque payment '.number_format($payment->payment).' received from '.
                $payment->customer->display_name.' and deposited to '.$payment->depositedTo->name,
            'manual_narration' => 'Returned cheque payment '.number_format($payment->payment).' received from '.
                $payment->customer->display_name.' and deposited to '.$payment->depositedTo->name,
            'tx_type_id' => 19,
            'customer_id' => $payment->customer_id,
            'company_id' => $payment->company_id,
        ], 'ReturnedChequePayment', $isEdit);
    }

    protected function recordTransactionForCancel(ChequePayment $payment, $isEdit = false)
    {
        $debitAccount = Account::find(3);
        $creditAccount = Account::find($payment->deposited_to);
        $bankName = $payment->bank->name ?? '';
        recordTransaction($payment, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $payment->payment,
            'auto_narration' => 'Payment '.number_format($payment->payment).' is canceled from Cheque# '.
                $payment->cheque . ' ' . $bankName,
            'manual_narration' => 'Payment '.number_format($payment->payment).' is canceled from Cheque# '.
                $payment->cheque . ' ' . $bankName,
            'tx_type_id' => 19,
            'customer_id' => $payment->customer_id,
            'company_id' => $payment->company_id,
        ], 'ReturnedChequePaymentCancel', $isEdit);
    }

    /**
     * @param ChequePayment $payment
     * @return array
     */
    public function delete(ChequePayment $payment): array
    {
        try {
            //$payment->transaction()->delete();
            $payment->delete();
            return ['success' => true, 'message' => 'Payment deleted success'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Payment deleted failed'];
        }

    }

    public function updateVisited(Customer $customer, Request $request)
    {
        $allocations = getRepAllocation()->first();
        $dailySalesCustomers = $allocations ? $allocations->customers : null;
        if (!$dailySalesCustomers) return;
        $dailySalesCustomer = $dailySalesCustomers->where('customer_id', $customer->id)->first();
        if ($dailySalesCustomer && $dailySalesCustomer->is_visited == 'No') {
            $dailySalesCustomer->update([
                'is_visited' => 'Yes',
                'reason' => 'System - Returned cheque payment collected',
                'gps_lat' => $request->input('gps_lat'),
                'gps_long' => $request->input('gps_long'),
            ]);
        }
    }

    public function isPrinted(ChequePayment $payment)
    {
        $payment->setAttribute('is_printed', 'Yes');
        $payment->save();
        return $payment->refresh();
    }

    public function cancel($chequeKey, ChequePayment $payment): ChequePayment
    {
        $payment->setAttribute('status', 'Canceled');
        $payment->save();

        /** update all cheques to not settled */
        $chequesInHand = ChequeInHand::where(chequeKeyToArray($chequeKey, 'query'))->get();
        if($chequesInHand){
            $chequesInHand->each(function (ChequeInHand $chequeInHand) {
                $chequeInHand->setAttribute('settled', 'No');
                $chequeInHand->save();
            });
        }

        /** add cancel related transaction */
        $this->recordTransactionForCancel($payment);

        return $payment;
    }

    /**
     * @param Request $request
     * @param bool $isApi
     * @return void
     */
    public function setPaymentAttributes(Request $request, bool $isApi)
    {
        $this->model->setAttribute('payment', $request->input('payment'));
        $this->model->setAttribute('payment_date', $request->input('payment_date'));
        $this->model->setAttribute('payment_type', $request->input('payment_type'));
        $this->model->setAttribute('payment_mode', $request->input('payment_mode'));

        $this->model->setAttribute('cheque_type', $request->input('cheque_type'));
        $this->model->setAttribute('cheque_no', $request->input('cheque_no'));
        $this->model->setAttribute('cheque_date', $request->input('cheque_date'));

        $this->model->setAttribute('account_no', $request->input('account_no'));
        $this->model->setAttribute('deposited_date', $request->input('deposited_date'));

        $this->model->setAttribute('bank_id', $request->input('bank_id'));
        $this->model->setAttribute('card_holder_name', $request->input('card_holder_name'));
        $this->model->setAttribute('card_no', $request->input('card_no'));
        $this->model->setAttribute('expiry_date', $request->input('expiry_date'));

        if (!$isApi) {
            if ($request->input('payment_mode') == 'Cheque') {
                $this->model->setAttribute('bank_id', $request->input('cheque_bank_id'));
            } else {
                $this->model->setAttribute('bank_id', $request->input('dd_bank_id'));
            }
        }

        $this->model->setAttribute('status', 'Paid');
        $this->model->setAttribute('notes', 'Returned cheque payment created');

        $this->model->setAttribute('gps_lat', $request->input('gps_lat'));
        $this->model->setAttribute('gps_long', $request->input('gps_long'));

        $this->model->setAttribute('prepared_by', auth()->id());

        if ($isApi) {

            switch ($request->input('payment_mode')) {
                case 'Cash':
                    $depositedTo = Account::where('accountable_id', getRepIdFromAuth(auth()->id()))
                        ->where('accountable_type', 'App\Rep')
                        ->where('account_type_id', 1)
                        ->first();
                    if ($depositedTo) {
                        $this->model->setAttribute('deposited_to', $depositedTo->id);
                    } else {
                        $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
                    }
                    break;
                case 'Cheque':
                    $depositedTo = Account::where('accountable_id', getRepIdFromAuth(auth()->id()))
                        ->where('accountable_type', 'App\Rep')
                        ->where('account_type_id', 19)
                        ->first();
                    if ($depositedTo) {
                        $this->model->setAttribute('deposited_to', $depositedTo->id);
                    } else {
                        $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
                    }
                    break;
                default:
                    $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
                    break;
            }
        } else {
            $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
        }

        $paymentMode = request()->input('payment_mode');
        if ($paymentMode == 'Cash') {
            $this->model->setAttribute('cheque_type', null);
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);

            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
            $this->model->setAttribute('bank_id', null);

            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);

        } elseif ($paymentMode == 'Cheque') {
            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);

            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);

        } elseif ($paymentMode == 'Direct Deposit') {
            $this->model->setAttribute('cheque_type', null);
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);

            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);

        } elseif ($paymentMode == 'Credit Card') {
            $this->model->setAttribute('cheque_type', null);
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);

            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
        }

    }

}
