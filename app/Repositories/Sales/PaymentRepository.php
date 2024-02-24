<?php

namespace App\Repositories\Sales;

use App\Account;
use App\Customer;
use App\Http\Requests\Sales\CancelRequest;
use App\Http\Requests\Sales\InvoiceCreditUpdateRequest;
use App\Http\Requests\Sales\RefundRequest;
use App\InvoicePayment;
use App\Http\Requests\Sales\PaymentStoreRequest;
use App\Invoice;
use App\Repositories\BaseRepository;
use App\SalesHandover;
use Illuminate\Http\Request;

/**
 * Class PaymentRepository
 * @package App\Repositories\Sales
 */
class PaymentRepository extends BaseRepository
{
    /**
     * PaymentRepository constructor.
     * @param InvoicePayment|null $payment
     */
    public function __construct(InvoicePayment $payment = null)
    {
        $this->setModel($payment ?? new InvoicePayment());
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['payment', 'payment_date', 'payment_type', 'payment_mode', 'payment_from', 'cheque_no',
            'cheque_date', 'account_no', 'deposited_date', 'bank_id', 'status', 'notes', 'prepared_by', 'invoice_id'];

        $searchingColumns = ['payment', 'payment_date', 'payment_type', 'payment_mode', 'payment_from', 'cheque_no',
            'cheque_date', 'account_no', 'deposited_date', 'bank_id', 'status', 'notes', 'prepared_by', 'invoice_id'];

        $relation = [
            'invoice' => [
                ['as' => 'related_invoice', 'column' => 'invoice_no']
            ]
        ];

        $data = $this->getTableData($request, $columns, $searchingColumns, $relation);
        $data['data'] = array_map(function ($item) {
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Show', null, ['sales.payment.show', [$item['id']]], ['class' => 'btn-success']);
            $item['action'] .= actionBtn('Edit', null, ['sales.payment.edit', [$item['id']]]);
            $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-inv-payment']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    /**
     * @return mixed
     */
    public function apiIndex()
    {
        return InvoicePayment::whereHas('invoice', function ($query) {
            $query->whereHas('order', function ($query) {
                $query->where('prepared_by', auth()->id());
            })->where('prepared_by', auth()->id());
        })
            ->where('prepared_by', auth()->id())
            ->with('customer')
            ->get();
    }

    /**
     * @return mixed
     */
    public function todayIndexOld()
    {
        $customers = getAllAllocatedCustomers();
        $status = ['Scheduled', 'Draft', 'Awaiting Approval', 'Open'];
        $customerIds = $customers->pluck('id')->toArray();
        $invoices = Invoice::where(function ($query) use ($customerIds, $status) {
            $query->where(function ($query) use ($customerIds, $status) {
                $query->whereHas('order', function ($query) use ($customerIds, $status) {
                    $query->whereIn('status', $status)
                        ->orWhere('order_date', now()->toDateString());
                });
            })->orWhere(function ($query) {
                $query->whereHas('payments', function ($query) {
                    $query->where('payment_date', now()->toDateString())->where('prepared_by', auth()->id());
                });
            })->orWhere('invoice_date', now()->toDateString());
        })->whereHas('order', function ($query) use ($customerIds, $status) {
            $query->where('prepared_by', auth()->id());
        })->where('prepared_by', auth()->id())
            ->whereIn('customer_id', $customerIds)
            ->with(['company', 'customer', 'payments'])
            ->get()->pluck('id')->toArray();
        return InvoicePayment::whereIn('invoice_id', $invoices)->with(['invoice', 'customer', 'order'])->get();
    }

    /**
     * @return mixed
     */
    public function todayIndex()
    {
        $allocations = getRepAllocation();
        $allocation = $allocations->first();
        $customers = getAllAllocatedCustomers($allocations);
        $customerIds = $customers->pluck('id')->toArray();
        $orderIds = getAllocationCreditOrdersId();
        $invoices = Invoice::whereIn('customer_id', $customerIds)->where(function ($q) use ($orderIds, $allocation) {
            $q->where(function ($q) use ($orderIds, $allocation) {
                $q->whereBetween('invoice_date', [$allocation->from_date, $allocation->to_date])->where('prepared_by', auth()->id());
            })->orWhereHas('order', function ($orders) use ($orderIds) {
                $orders->whereIn('id', $orderIds);
            });
        })->with(['company', 'customer', 'payments'])
            ->get()->pluck('id')->toArray();
        return InvoicePayment::whereIn('invoice_id', $invoices)->where('status', 'Paid')->with(['invoice', 'customer', 'order'])->get();
    }

    /**
     * @param Request $request
     * @param Invoice $invoice
     * @param bool $isApi
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(Request $request, Invoice $invoice, $isApi = false)
    {
        // Check Duplication
        $uuid = $request->input('uuid');
        if ($isApi && !$this->model->id && $uuid){
            $duplicateItem = InvoicePayment::where('uuid', $uuid)->first();
            if ($duplicateItem){
                return $duplicateItem->load(['customer', 'depositedTo']);
            }
            $this->model->setAttribute('uuid', $uuid);
        }


        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('invoice_id', $invoice->getAttribute('id'));
        $this->model->setAttribute('sales_order_id', $invoice->getAttribute('sales_order_id'));
        $this->model->setAttribute('customer_id', $invoice->getAttribute('customer_id'));
        $this->model->setAttribute('route_id', $invoice->getAttribute('route_id'));
        $this->model->setAttribute('business_type_id', $invoice->getAttribute('business_type_id'));
        $this->model->setAttribute('company_id', $invoice->getAttribute('company_id'));
        $this->model->setAttribute('notes', $request->input('payment_notes'));
        $this->model->setAttribute('sales_location_id', $invoice->sales_location_id);
        $this->model->setAttribute('payment_date', $request->input('payment_date'));
        $this->model->setAttribute('payment', $request->input('payment'));
        $this->model->setAttribute('payment_mode', $request->input('payment_mode'));
        $this->model->setAttribute('payment_type', $request->input('payment_type'));

        $this->model->setAttribute('cheque_type', $request->input('cheque_type'));
        $this->model->setAttribute('cheque_no', $request->input('cheque_no'));
        $this->model->setAttribute('cheque_date', $request->input('cheque_date'));
        $this->model->setAttribute('deposited_date', $request->input('deposited_date'));
        $this->model->setAttribute('account_no', $request->input('account_no'));

        if($isApi){
            if($request->input('payment_mode') == 'Cash'){
                $depositedTo = Account::where('accountable_id', getRepIdFromAuth(auth()->id()))
                    ->where('accountable_type', 'App\Rep')
                    ->where('account_type_id', 1)
                    ->first();
                if($depositedTo){
                    $this->model->setAttribute('deposited_to', $depositedTo->id);
                }else{
                    $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
                }
            }elseif ($request->input('payment_mode') == 'Cheque'){
                $depositedTo = Account::where('accountable_id', getRepIdFromAuth(auth()->id()))
                    ->where('accountable_type', 'App\Rep')
                    ->where('account_type_id', 19)
                    ->first();
                if($depositedTo){
                    $this->model->setAttribute('deposited_to', $depositedTo->id);
                }else{
                    $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
                }
            }else{
                $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
            }
        }else{
            $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
        }

        $this->model->setAttribute('bank_id', $request->input('bank_id'));
        $this->model->setAttribute('card_holder_name', $request->input('card_holder_name'));
        $this->model->setAttribute('card_no', $request->input('card_no'));
        $this->model->setAttribute('expiry_date', $request->input('expiry_date'));
        $this->model->setAttribute('cheque_type', $request->input('cheque_type'));

        if($request->input('daily_sale_id')){
            $this->model->setAttribute('daily_sale_id', $request->input('daily_sale_id'));
        }else{
            $this->model->setAttribute('daily_sale_id', $invoice->getAttribute('daily_sale_id'));
        }

        if ($isApi) {
            $dailySale = getRepAllocation()->first();
            $this->model->setAttribute('daily_sale_id', $dailySale ? $dailySale->id : null);
            if ($request->input('created_at')){
                $createdAt = carbon($request->input('created_at'));
                $this->model->setAttribute('created_at', $createdAt->toDateTimeString());
            }
        }
        $paymentMode = $request->input('payment_mode');
        if (!$isApi) {
            if ($paymentMode == 'Cheque') {
                $this->model->setAttribute('bank_id', $request->input('cheque_bank_id'));
            } else {
                $this->model->setAttribute('bank_id', $request->input('dd_bank_id'));
            }
        }
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
            $this->model->setAttribute('cheque_type', null);
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
            $this->model->setAttribute('cheque_type', null);
        } elseif ($paymentMode == 'Credit Card') {
            $this->model->setAttribute('cheque_type', null);
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);
            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
            $this->model->setAttribute('cheque_type', null);
        }

        $this->model->setAttribute('gps_lat', $request->input('gps_lat'));
        $this->model->setAttribute('gps_long', $request->input('gps_long'));

        $this->model->save();
        $invoice = $invoice->refresh();
        /** update invoice status as per the payment */
        if ($invoice->getAttribute('amount') == $invoice->payments->sum('payment')) {
            $invoice->setAttribute('status', 'Paid');
        } else {
            $invoice->setAttribute('status', 'Partially Paid');
        }

        $invoice->save();

        $order = $invoice->order;
        if ($order && $order->total == $order->payments->sum('payment')) {
            $order->setAttribute('status', 'Closed');
            $order->save();
        }

        if ($isApi){
            $this->updateVisited($invoice->customer, $request);
        }

        if($this->model->payment_mode != 'Customer Credit'){
            $this->recordTransaction($this->model);
        }

        if (!$isApi) {
            /** get sales hand over details and update */
            $handOver = SalesHandover::where('daily_sale_id', $this->model->daily_sale_id)->first();
            if($handOver){
                if($this->model->payment_mode == 'Cash'){
                    $handOver->cash_sales = ($handOver->cash_sales + $this->model->payment);
                }
                if($this->model->payment_mode == 'Cheque'){
                    $handOver->cheque_sales = ($handOver->cheque_sales + $this->model->payment);
                }
                if($this->model->payment_mode == 'Direct Deposit'){
                    $handOver->deposit_sales = ($handOver->deposit_sales + $this->model->payment);
                }
                if($this->model->payment_mode == 'Credit Card'){
                    $handOver->card_sales = ($handOver->card_sales + $this->model->payment);
                }
                $handOver->sales = ($handOver->sales + $this->model->payment);
                $handOver->total_collect = ($handOver->total_collect + $this->model->payment);
                $handOver->save();
            }
        }

        return $this->model->refresh();
    }

    /**
     * @param Request $request
     * @param InvoicePayment $payment
     * @param bool $isApi
     * @return InvoicePayment
     */
    public function update(Request $request, InvoicePayment $payment, $isApi = false)
    {
        $this->setModel($payment);
        $this->model->setAttribute('payment_date', $request->input('payment_date'));
        $this->model->setAttribute('payment', $request->input('payment'));
        $this->model->setAttribute('payment_mode', $request->input('payment_mode'));
        $this->model->setAttribute('payment_type', $request->input('payment_type'));

        $this->model->setAttribute('cheque_type', $request->input('cheque_type'));
        $this->model->setAttribute('cheque_no', $request->input('cheque_no'));
        $this->model->setAttribute('cheque_date', $request->input('cheque_date'));
        $this->model->setAttribute('deposited_date', $request->input('deposited_date'));
        $this->model->setAttribute('account_no', $request->input('account_no'));
        $this->model->setAttribute('bank_id', $request->input('bank_id'));
        $this->model->setAttribute('card_holder_name', $request->input('card_holder_name'));
        $this->model->setAttribute('card_no', $request->input('card_no'));
        $this->model->setAttribute('expiry_date', $request->input('expiry_date'));

        if($isApi){
            if($request->input('payment_mode') == 'Cash'){
                $depositedTo = Account::where('accountable_id', getRepIdFromAuth(auth()->id()))
                    ->where('accountable_type', 'App\Rep')
                    ->where('account_type_id', 1)
                    ->first();
                if($depositedTo){
                    $this->model->setAttribute('deposited_to', $depositedTo->id);
                }else{
                    $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
                }
            }elseif ($request->input('payment_mode') == 'Cheque'){
                $depositedTo = Account::where('accountable_id', getRepIdFromAuth(auth()->id()))
                    ->where('accountable_type', 'App\Rep')
                    ->where('account_type_id', 19)
                    ->first();
                if($depositedTo){
                    $this->model->setAttribute('deposited_to', $depositedTo->id);
                }else{
                    $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
                }
            }else{
                $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
            }
        }else{
            $this->model->setAttribute('deposited_to', $request->input('deposited_to'));
        }

        $this->model->setAttribute('cheque_type', $request->input('cheque_type'));
        $paymentMode = $request->input('payment_mode');
        if (!$isApi) {
            if ($paymentMode == 'Cheque' && !$request->input('bank_id')) {
                $this->model->setAttribute('bank_id', $request->input('cheque_bank_id'));
            } else {
                $this->model->setAttribute('bank_id', $request->input('dd_bank_id'));
            }
        }
        if ($paymentMode == 'Cash') {
            $this->model->setAttribute('cheque_type', null);
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);
            $this->model->setAttribute('cheque_date', null);
            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
            $this->model->setAttribute('bank_id', null);
            $this->model->setAttribute('cheque_type', null);
        } elseif ($paymentMode == 'Cheque') {
            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);
            $this->model->setAttribute('cheque_type', null);
        } elseif ($paymentMode == 'Direct Deposit') {
            $this->model->setAttribute('cheque_type', null);
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);
            $this->model->setAttribute('card_holder_name', null);
            $this->model->setAttribute('card_no', null);
            $this->model->setAttribute('expiry_date', null);
            $this->model->setAttribute('cheque_type', null);
        } elseif ($paymentMode == 'Credit Card') {
            $this->model->setAttribute('cheque_type', null);
            $this->model->setAttribute('cheque_no', null);
            $this->model->setAttribute('cheque_date', null);
            $this->model->setAttribute('account_no', null);
            $this->model->setAttribute('deposited_date', null);
            $this->model->setAttribute('cheque_type', null);
        }

        /** @var Invoice $invoice */
        $invoice = $payment->invoice;
        $request->merge(['sales_location_id', $invoice ? $invoice->sales_location_id : null]);
        $this->model->update($request->toArray());
        $invoice = $invoice->refresh();
        /** update invoice status as per the payment */
        if ($invoice->getAttribute('amount') == $invoice->payments->sum('payment')) {
            $invoice->setAttribute('status', 'Paid');
        } else {
            $invoice->setAttribute('status', 'Partially Paid');
        }
        $invoice->save();
        $order = $invoice->order;
        if ($order) {
            $order->refresh();
            if ($order->total == $order->payments->sum('payment')) {
                $order->setAttribute('status', 'Closed');
                $order->save();
            }
        }

        if($payment != 'Customer Credit') {
            /** remove payment related transaction */
            $transaction = $payment->transaction;
            $transaction->records()->delete();
            $transaction->delete();
            /** end */

            /** generate new transaction */
            $this->recordTransaction($payment);
        }

        if ($isApi){
            $this->updateVisited($invoice->customer, $request);
        }

        /** TODO - Need to update handover stats when edit the payment */

        return $payment;
    }

    protected function recordTransaction(InvoicePayment $payment, $isEdit = false)
    {
        $debitAccount = Account::find($payment->deposited_to);
        $creditAccount = Account::find(3);
        recordTransaction($payment, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $payment->payment,
            'auto_narration' => 'Payment '.number_format($payment->payment).' received from '.
                $payment->customer->display_name.' and deposited to '.$payment->depositedTo->name,
            'manual_narration' => 'Payment '.number_format($payment->payment).' received from '.
                $payment->customer->display_name.' and deposited to '.$payment->depositedTo->name,
            'tx_type_id' => 19,
            'customer_id' => $payment->customer_id,
            'company_id' => $payment->company_id,
        ], 'PaymentCreation', $isEdit);
    }

    /**
     * @param InvoicePayment $payment
     * @return array
     */
    public function delete(InvoicePayment $payment): array
    {
        try {
            $payment->delete();
            return ['success' => true, 'message' => 'Payment deleted success'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Payment deleted failed'];
        }

    }

    /**
     * @param string $method
     * @param InvoicePayment|null $payment
     * @return array
     */
    public function breadcrumbs(string $method, InvoicePayment $payment = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Payments'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @param InvoiceCreditUpdateRequest $request
     * @param InvoicePayment $payment
     * @return InvoicePayment
     */
    public function updateFromCredit(InvoiceCreditUpdateRequest $request, InvoicePayment $payment)
    {
        $this->setModel($payment);
        $this->model->update($request->toArray());
        return $payment;
    }

    /**
     * @param InvoicePayment $payment
     * @param Request $request
     * @return InvoicePayment
     */
    public function cancelPayment(InvoicePayment $payment, Request $request)
    {
        $comment = $request->input('cancel_notes_payment');
        $payment->setAttribute('status', 'Canceled');
        $payment->save();
        createComment($request, $payment, $comment);

        /**
         * if payment mode cheque and cheque in hand related
         * Update cheque in hand status to Cancel
         * Remove cheque hand related to transaction
         */
        if($payment->chequeInHand && $payment->payment_mode == 'Cheque'){
            $chequeInHand = $payment->chequeInHand;
            $chequeInHand->status = 'Canceled';
            $chequeInHand->save();
        }

        /**
         * BEGIN
         * update transaction record when invoice payment cancel
         *  Transaction Type - Customer Payment Cancel
         *      DR - Account Receivable
         *      CR - Rep Accounts
         */
        $debitAccount = Account::find(3);
        $creditAccount = Account::find($payment->deposited_to);
        recordTransaction($payment, $debitAccount, $creditAccount, [
            'date' => now()->toDateString(),
            'type' => 'Deposit',
            'amount' => $payment->payment,
            'auto_narration' => 'Payment '.number_format($payment->payment).' is canceled from '.
                $payment->order->ref,
            'manual_narration' => 'Payment '.number_format($payment->payment).' is canceled from '.
                $payment->order->ref,
            'tx_type_id' => 4,
            'customer_id' => $payment->customer_id,
            'company_id' => $payment->company_id,
        ], 'PaymentCancel', false);

        /** update order as Cash / Credit */
        $order = $payment->order;
        if(soOutstanding($order)['balance']){
            $order->is_credit_sales = 'Yes';
            $order->status = 'Open';
            $order->save();
        }

        /** get sales hand over details and update */
        $handOver = SalesHandover::where('daily_sale_id', $payment->daily_sale_id)->first();
        if($handOver){
            if($payment->payment_mode == 'Cash'){
                $handOver->cash_sales = ($handOver->cash_sales - $payment->payment);
            }
            if($payment->payment_mode == 'Cheque'){
                $handOver->cheque_sales = ($handOver->cheque_sales - $payment->payment);
            }
            if($payment->payment_mode == 'Direct Deposit'){
                $handOver->deposit_sales = ($handOver->deposit_sales - $payment->payment);
            }
            if($payment->payment_mode == 'Credit Card'){
                $handOver->card_sales = ($handOver->card_sales - $payment->payment);
            }
            $handOver->sales = ($handOver->sales - $payment->payment);
            $handOver->total_collect = ($handOver->total_collect - $payment->payment);
            $handOver->save();
        }

        return $payment;
    }

    /**
     * @param InvoicePayment $payment
     * @param Request $request
     * @return InvoicePayment
     */
    public function refundPayment(InvoicePayment $payment, Request $request)
    {
        $comment = $request->input('refund_notes_payment');
        $payment->setAttribute('status', 'Refunded');
        $payment->save();
        createComment($request, $payment, $comment);
        return $payment;
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
                'reason' => 'System - Payment created',
                'gps_lat' => $request->input('gps_lat'),
                'gps_long' => $request->input('gps_long'),
            ]);
        }
    }

}
