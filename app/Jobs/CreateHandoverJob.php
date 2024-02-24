<?php

namespace App\Jobs;

use App\DailySale;
use App\InvoicePayment;
use App\Repositories\Sales\HandOverRepository;
use App\Repositories\Sales\SalesExpenseRepository;
use App\SalesExpense;
use App\SalesHandover;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateHandoverJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $allocation;

    public function __construct(DailySale $allocation)
    {
        $this->allocation = $allocation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $allocation = $this->allocation;
        $handover = $allocation->salesHandover;
        $handoverRepo = new HandOverRepository();
        if ($handover) return;
        $orders = $allocation->orders()->with('products')->get();
        $invoices = $allocation->invoices;
        $payments = $allocation->payments;
        $paymentIDs = $payments->pluck('id')->toArray();

        $today = $this->todayCollectedPayments($paymentIDs);
        $todayPayments = $handoverRepo->paymentGroupByPaymentMode($today);
        $todayCollection = $handoverRepo->getPaymentsCollectedAmounts($today);

        $old = $this->oldCollectedPayments($paymentIDs);
        $oldPayments = $handoverRepo->paymentGroupByPaymentMode($old);
        $oldCollection = $handoverRepo->getPaymentsCollectedAmounts($old);

        $totalCollect = $todayCollection->sum() + $oldCollection->sum();
        $noOfChequeCollected = $todayPayments->get('cheque')->count() + $oldPayments->get('cheque')->count();

        $handover = new SalesHandover();
        $handover->code = $handoverRepo->getCode();
        $handover->date = carbon()->toDateString();
        $handover->daily_sale_id = $allocation->id;

        //Today Sales
        $handover->setAttribute('sales', $todayCollection->sum());
        $handover->setAttribute('cash_sales', $todayCollection->get('cash'));
        $handover->setAttribute('cheque_sales', $todayCollection->get('cheque'));
        $handover->setAttribute('deposit_sales', $todayCollection->get('direct_deposit'));
        $handover->setAttribute('card_sales', $todayCollection->get('card_sales'));
        $handover->setAttribute('credit_sales', 0.00);

        //Old Sales
        $handover->setAttribute('old_sales', $oldCollection->sum());
        $handover->setAttribute('old_cash_sales', $oldCollection->get('cash'));
        $handover->setAttribute('old_cheque_sales', $oldCollection->get('cheque'));
        $handover->setAttribute('old_deposit_sales', $oldCollection->get('direct_deposit'));
        $handover->setAttribute('old_card_sales', $oldCollection->get('card_sales'));
        $handover->setAttribute('old_credit_sales', 0.00);

        //Common
        $user = auth()->user();
        $handover->setAttribute('total_collect', $totalCollect);
        $handover->setAttribute('cheques_count', $noOfChequeCollected);
        $handover->setAttribute('allowance', $allocation->allowance);
        $handover->setAttribute('rep_id', $allocation->rep_id ?? null);
        $handover->setAttribute('notes', $user ? 'Complete by ' . $user->name : 'System');
        $handover->setAttribute('prepared_by', $user->id ?? null);
        $handover->setAttribute('company_id', $allocation->company_id ?? null);

        $products = $orders->pluck('products')->collapse();
        foreach ($products as $product) {
            $item = $allocation->items->where('product_id', $product->id)->first();
            if ($item) {
                $item->sold_qty = getSoldQty($allocation, $item);
                $item->save();
            }
        }

        $handover->save();

        /** create default allowance as sales expenses */
        $this->createSalesExpense($handover);
    }

    public function createSalesExpense(SalesHandover $handover)
    {
        $salesExpRepo = new SalesExpenseRepository();

        $allocation = $handover->dailySale;
        $allowance = $allocation->allowance;

        $user = auth()->user();
        $authId = $user->id;
        $staff = $user->staffs->first();

        SalesExpense::create([
            'code' => $salesExpRepo->getCode(),
            'amount' => $allowance,
            'expense_date' => now()->toDateString(),
            'prepared_by' => $authId,
            'staff_id' => $staff->id,
            'company_id' => $handover->getAttribute('company_id'),
            'sales_handover_id' => $handover->getAttribute('id'),
            'daily_sale_id' => $handover->getAttribute('daily_sale_id'),
            'type_id' => allowanceTypeId(),
            'notes' => 'Sales Allowance'
        ]);

        return true;
    }

    /**
     * @param $payments
     * @param null $now
     * @return mixed
     */
    public function todayCollectedPayments($payments, $now = null)
    {
        $startDate = $this->allocation->from_date;
        $endDate = $this->allocation->to_date;
        return InvoicePayment::whereIn('id', $payments)
            ->where('status', 'Paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->whereHas('invoice', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('invoice_date', [$startDate, $endDate]);
            })
            ->get();
    }

    /**
     * @param $payments
     * @param null $now
     * @return \Illuminate\Support\Collection
     */
    public function oldCollectedPayments($payments, $now = null)
    {
        $allocation = $this->allocation;
        $startDate = $allocation->from_date;
        $endDate = $allocation->to_date;
        return $allocation->dailySaleCreditOrders()->with(['order.payments' => function ($q) use ($startDate, $endDate) {
            $q->where('status', 'Paid')->whereBetween('payment_date', [$startDate, $endDate]);
        }])->get()->pluck('order')->pluck('payments')->collapse()
            ->where('daily_sale_id', $allocation->id);
    }

}
