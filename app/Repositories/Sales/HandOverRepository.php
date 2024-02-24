<?php

namespace App\Repositories\Sales;

use App\Account;
use App\{CashBreakdown,
    ChequeInHand,
    ChequePayment,
    Customer,
    DailySale,
    DailySaleItem,
    DailySalesOdoReading,
    DailyStockItem,
    Expense,
    Invoice,
    InvoicePayment,
    Jobs\NextDayAllocationCreateJob,
    MileageRate,
    Rep,
    SalesExpense,
    SalesHandover,
    SalesHandoverExcess,
    SalesHandoverShortage,
    SalesOrder,
    Stock,
    StockExcess,
    StockExcessItem,
    StockHistory,
    StockShortage,
    StockShortageItem,
    Store};
use App\Jobs\StockUpdateJob;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

/**
 * Class HandOverRepository
 * @package App\Repositories\Sales
 */
class HandOverRepository extends BaseRepository
{
    /**
     * HandOverRepository constructor.
     * @param SalesHandover|null $handover
     */
    public function __construct(SalesHandover $handover = null)
    {
        $this->setModel($handover ?? new SalesHandover());
        $this->setCodePrefix('SHO', 'code');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function details()
    {
        $allocation = getRepAllocation()->first();
        $odoMeterReading = $allocation ? $allocation->odoMeterReading : null;
        $todayCollections = $this->todayCollectedPayments($allocation);
        $returnedChequeCollections = $this->returnChequesPayments($allocation);
        $todayInvoices = $this->todayInvoices($allocation);
        $todayTotalPaid = $todayCollections->sum('payment');
        $todayInvoiced = $todayInvoices->sum('amount');
        $collectedAmounts = collect([
            'today_collections' => $this->getPaymentsCollectedAmounts($todayCollections),
            'old_collections' => $this->getPaymentsCollectedAmounts($this->oldCollectedPayments($allocation)),
            'returned_cheque_collections' => $this->getReturnedChequeAmounts($returnedChequeCollections),
        ]);
        $allowance = $this->allocatedAllowance();
        $todayAllocatedCustomers = $this->todayAllocatedCustomers();
        $todayVisitedCustomers = $this->todayVisitedCustomers();
        $todayNotVisitedCustomers = array_values(array_diff($todayAllocatedCustomers, $todayVisitedCustomers));
        return collect([
            'start_odo_meter_reading' => $odoMeterReading ? $odoMeterReading->starts_at : 0,
            'collections' => $collectedAmounts,
            'allowance' => $allowance,
            'mileage_rate' => $this->getMileageRate(),
            'today_allocated_customers' => $todayAllocatedCustomers,
            'today_visited_customers' => $todayVisitedCustomers,
            'today_not_visited_customers' => $todayNotVisitedCustomers,
            'today_credit_bill_amount' => (float)($todayInvoiced - $todayTotalPaid),
            'tomorrow_route' => $allocation->nxt_day_al_route ? true : false,
            'all_actual_stock_confirmed' => $this->isAllActualStockConfirmed($allocation)
        ]);
    }

    public function todayAllocatedCustomers()
    {
        return todayAllocatedCustomers()->pluck('id')->toArray();
    }

    public function todayVisitedCustomers()
    {
        $allocations = getRepAllocation();
        $allocation = $allocations->first();
        return $allocation->customers->where('reason', '!=', null)->pluck('customer_id')->toArray();
    }

    public function todayNotVisitedCustomers()
    {
        $allocations = getRepAllocation();
        $allocation = $allocations->first();
        return $allocation->customers->where('reason', null)->pluck('customer_id')->toArray();
    }

    public function store(Request $request, $allocation)
    {
        $today = $this->todayCollectedPayments($allocation);
        $todayChequeCollection = $this->todayCollectedChequePayments($allocation);
        $todayPayments = $this->paymentGroupByPaymentMode($today);
        $todayCollection = $this->getPaymentsCollectedAmounts($today);
        $chequeCollection = $this->getPaymentsCollectedAmounts($todayChequeCollection);

        $old = $this->oldCollectedPayments($allocation);
        $oldPayments = $this->paymentGroupByPaymentMode($old);
        $oldCollection = $this->getPaymentsCollectedAmounts($old);

        $totalCollect = $todayCollection->sum() + $oldCollection->sum() + $chequeCollection->sum();
        $noOfChequeCollected = $todayPayments->get('cheque')->count() + $oldPayments->get('cheque')->count();

        $rep = getRep();
        $company = userCompany();

        $this->model->setAttribute('code', $this->getCode());
        $this->model->setAttribute('date', now()->toDateString());
        $this->model->setAttribute('daily_sale_id', $allocation ? $allocation->id : null);

        $this->model->setAttribute('sales', $todayCollection->sum());
        $this->model->setAttribute('cash_sales', $todayCollection->get('cash'));
        $this->model->setAttribute('cheque_sales', $todayCollection->get('cheque'));
        $this->model->setAttribute('deposit_sales', $todayCollection->get('direct_deposit'));
        $this->model->setAttribute('card_sales', $todayCollection->get('card_sales'));
        $this->model->setAttribute('credit_sales', 0.00);

        $this->model->setAttribute('old_sales', $oldCollection->sum());
        $this->model->setAttribute('old_cash_sales', $oldCollection->get('cash'));
        $this->model->setAttribute('old_cheque_sales', $oldCollection->get('cheque'));
        $this->model->setAttribute('old_deposit_sales', $oldCollection->get('direct_deposit'));
        $this->model->setAttribute('old_card_sales', $oldCollection->get('card_sales'));
        $this->model->setAttribute('old_credit_sales', 0.00);

        $this->model->setAttribute('rc_collection', $chequeCollection->sum());
        $this->model->setAttribute('rc_cash', $chequeCollection->get('cash'));
        $this->model->setAttribute('rc_cheque', $chequeCollection->get('cheque'));
        $this->model->setAttribute('rc_deposit', $chequeCollection->get('direct_deposit'));
        $this->model->setAttribute('rc_card', $chequeCollection->get('card_sales'));
        $this->model->setAttribute('rc_credit', 0.00);

        $allowance = $request->input('allowance') ? $this->allocatedAllowance() : null;
        $this->model->setAttribute('total_collect', $totalCollect);
        $this->model->setAttribute('cheques_count', $noOfChequeCollected);
        $this->model->setAttribute('allowance', $allowance);
        $this->model->setAttribute('rep_id', $rep->id ?? null);
        $this->model->setAttribute('notes', $request->input('notes'));
        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('company_id', $company->id ?? null);
        $this->model->save();
        return $this->model->refresh();
    }

    public function storeAllowanceAsExpense(Request $request, SalesHandover $handover)
    {
        $this->setModel(new SalesExpense());
        $this->setCodePrefix('SE');

        $user = auth()->user();
        $authId = $user->id;
        $staff = $user->staffs->first();
        $company = userCompany();

        $expense = new SalesExpense();
        $expense->setAttribute('code', $this->getCode());
        $expense->setAttribute('amount', $request->input('allowance'));
        $expense->setAttribute('expense_date', now()->toDateString());
        $expense->setAttribute('prepared_by', $authId);
        $expense->setAttribute('staff_id', $staff->id);
        $expense->setAttribute('company_id', $company ? $company->id : '');
        $expense->setAttribute('sales_handover_id', $handover->id);
        $expense->setAttribute('daily_sale_id', $handover->daily_sale_id);
        $expense->setAttribute('type_id', allowanceTypeId());
        $expense->setAttribute('notes', 'Sales allowance');
        $expense->save();
    }

    public function updateSoldQty(Request $request, SalesHandover $handover)
    {
        $dailySales = $handover->dailySale;
        if (!$dailySales) return false;
        $dailySalesItems = $dailySales->items;

        $soldItems = $request->input('sold_qty');
        foreach ($soldItems as $id => $qty) {
            $item = $dailySalesItems->firstWhere('product_id', $id);
            if ($item) {
                $item->sold_qty = $qty;
                $item->save();
            }
        }
    }

    public function storeSalesExpense(Request $request, SalesHandover $handover)
    {
        $saleExpenses = $this->mapSalesExpense($request, $handover);
        $this->setModel(new SalesExpense());
        $this->setCodePrefix('SE');
        foreach ($saleExpenses as $expense) {
            $expense['code'] = $this->getCode();
            SalesExpense::create($expense);
        }
        $user = auth()->user();
        $authId = $user->id;
        $staff = $user->staffs->first();
        $company = userCompany();
        SalesExpense::create([
            'code' => $this->getCode(),
            'amount' => $request->input('allowance'),
            'expense_date' => now()->toDateString(),
            'prepared_by' => $authId,
            'staff_id' => $staff->id,
            'company_id' => $company ? $company->id : '',
            'sales_handover_id' => $handover->id,
            'daily_sale_id' => $handover->daily_sale_id,
            'type_id' => allowanceTypeId(),
            'notes' => 'Sales allowance'
        ]);
        $dailySales = $handover->dailySale;
        if (!$dailySales) return false;
        $dailySalesItems = $dailySales->items;
        $soldItems = $request->input('sold_qty');
        foreach ($soldItems as $id => $qty) {
            $item = $dailySalesItems->firstWhere('product_id', $id);
            if ($item) {
                $item->sold_qty = $qty;
                $item->save();
            }
        }

        $replacedQty = $request->input('replaced_qty');
        if (is_array($replacedQty)) {
            foreach ($replacedQty as $id => $qty) {
                $item = $dailySalesItems->firstWhere('product_id', $id);
                if ($item) {
                    $item->replaced_qty = $qty;
                    $item->save();
                }
            }
        }

        $handover->refresh();
        $salesExpenses = $handover->salesExpenses;
        $handover->total_expense = $salesExpenses->sum('amount');
        $handover->save();
        return true;
    }

    public function updateHandOverIdToExpense(DailySale $allocation, SalesHandover $handover)
    {
        $expenses = $allocation->salesExpenses;
        $expenses->each(function (SalesExpense $expense) use ($handover){
            $expense->setAttribute('sales_handover_id', $handover->id);
            $expense->save();
        });
        return true;
    }

    public function storeNotVisitedCustomers(Request $request, SalesHandover $handover)
    {
        $notVisitedCustomer = $request->input('not_visit_customer_notes');
        $todayAllocatedCustomers = $this->todayAllocatedCustomers();
        $todayVisitedCustomers = $this->todayVisitedCustomers();
        $dailySales = $handover->dailySale;
        $dailySalesCustomers = $dailySales->customers;
        if (!$handover) return false;
        foreach ($todayAllocatedCustomers as $customerId) {
            $reason = [];
            $isVisited = true;
            if (!in_array($customerId, $todayVisitedCustomers)) {
                $reason = array_get($notVisitedCustomer, $customerId);
                $isVisited = false;
            }
            $dailySalesCustomer = $dailySalesCustomers->firstWhere('customer_id', $customerId);
            if (!$dailySalesCustomer) continue;
            if(!$dailySalesCustomer->reason){
                $dailySalesCustomer->update([
                    'reason' => array_get($reason, 'reason'),
                    'gps_lat' => array_get($reason, 'gps_lat'),
                    'gps_long' => array_get($reason, 'gps_long'),
                    'is_visited' => $isVisited ? 'Yes' : 'No',
                ]);
            }
        }
        return true;
    }

    public function checkCreditSales()
    {
        foreach ($this->handoverOrders() as $order) {
            $payments = $order->payments;
            $totalPayment = $payments->where('status', 'Paid')->sum('payment');
            $order->is_credit_sales = $totalPayment < $order->total ? 'Yes' : 'No';
            $order->save();
        }
    }

    public function mapSalesExpense(Request $request, SalesHandover $handover)
    {
        $now = now();
        $user = auth()->user();
        $authId = $user->id;
        $staff = $user->staffs->first();
        $company = userCompany();
        $expenses = $request->input('expenses');
        if (!$expenses || !is_array($expenses)) return [];
        $expenseRate = $this->getMileageRate();
        foreach ($expenses as $key => $expense) {
            if (!isset($expenses[$key]['distance'])) $expenses[$key]['distance'] = 0;
            $expenses[$key]['expense_date'] = $now->toDateString();
            if (isset($expense['calculate_mileage_using']) && $expense['calculate_mileage_using'] == 'Odometer') {
                $start = isset($expense['start_reading']) ? $expense['start_reading'] : 0;
                $end = isset($expense['end_reading']) ? $expense['end_reading'] : 0;
                $expenses[$key]['distance'] = $end - $start;
            }
            $expenses[$key]['prepared_by'] = $authId;
            $expenses[$key]['gps_lat'] = isset($expense['gps_lat']) ? array_get($expense, 'gps_lat') : '';
            $expenses[$key]['gps_long'] = isset($expense['gps_long']) ? array_get($expense, 'gps_long') : '';
            $expenses[$key]['liter'] = isset($expense['liter']) ? array_get($expense, 'liter') : '';
            $expenses[$key]['staff_id'] = $staff ? $staff->id : null;
            $expenses[$key]['company_id'] = $company ? $company->id : null;
            $expenses[$key]['sales_handover_id'] = $handover ? $handover->id : null;
            $expenses[$key]['daily_sale_id'] = $handover ? $handover->daily_sale_id : null;
            if (isset($expenses[$key]['type_id']) && ($expenses[$key]['type_id'] == generalTypeId() || $expenses[$key]['type_id'] == mileageTypeId())) {
                //$expenses[$key]['odometer'] = 0;
                //$expenses[$key]['liter'] = 0;
            }
            if (isset($expenses[$key]['type_id']) && ($expenses[$key]['type_id'] == mileageTypeId())) {
                $expenses[$key]['amount'] = $this->calculateMileageExpense($expenses[$key]['distance'], $expenseRate);
            }
            if (isset($expenses[$key]['type_id']) && ($expenses[$key]['type_id'] == generalTypeId() || $expenses[$key]['type_id'] == fuelTypeId())) {
//                $expenses[$key]['calculate_mileage_using']  = null;
                $expenses[$key]['start_reading'] = 0;
                $expenses[$key]['end_reading'] = 0;
                $expenses[$key]['distance'] = 0;
            }
        }
        return $expenses;
    }

    public function calculateMileageExpense($distance = 1, $expenseRate = null)
    {
        if (!$expenseRate) {
            $expenseRate = $this->getMileageRate();
        }
        return $distance * $expenseRate;
    }

    public function getMileageRate()
    {
        $date = now()->toDateString();
        $expenseRate = MileageRate::whereDate('date', '<=', $date)->orderBy('date', 'desc')->first();
        return $expenseRate->rate ? $expenseRate->rate : 0;
    }

    public function todayHandOver()
    {
        $allocation = getRepAllocation()->first();
        if (!$allocation) {
            return null;
        }
        return $this->model->whereDate('date', now()->toDateString())
            ->where('daily_sale_id', $allocation ? $allocation->id : null)
            ->first();
    }

    /**
     * @param DailySale $allocation
     * @return mixed
     */
    public function todayCollectedPayments(DailySale $allocation)
    {
        return InvoicePayment::where('status', 'Paid')->where('prepared_by', auth()->id())
            ->whereBetween('payment_date', [$allocation->from_date, $allocation->to_date])
            ->whereHas('invoice', function ($q) use ($allocation) {
                $q->whereBetween('invoice_date', [$allocation->from_date, $allocation->to_date])->whereHas('order', function ($q) use ($allocation){
                    $q->whereBetween('order_date', [$allocation->from_date, $allocation->to_date]);
                });
            })
            ->get();
    }

    public function todayCollectedChequePayments(DailySale $allocation)
    {
        return ChequePayment::where('status', 'Paid')->where('prepared_by', auth()->id())
            ->whereBetween('payment_date', [$allocation->from_date, $allocation->to_date])
            ->get();
    }

    /**
     * @param DailySale $allocation
     * @return mixed
     */
    public function todayInvoices(DailySale $allocation)
    {
        return Invoice::whereBetween('invoice_date', [$allocation->from_date, $allocation->to_date])
            ->whereHas('order', function ($q) use ($allocation){
                $q->whereBetween('order_date', [$allocation->from_date, $allocation->to_date]);
            })->where('prepared_by', auth()->id())
            ->get();
    }

    /**
     * @param DailySale $allocation
     * @return mixed
     */
    public function oldCollectedPayments(DailySale $allocation)
    {
        return InvoicePayment::where('status', 'Paid')->where('prepared_by', auth()->id())
            ->whereBetween('payment_date', [$allocation->from_date, $allocation->to_date])
            ->whereHas('order', function ($q) use ($allocation) {
                $q->whereDate('order_date', '<', $allocation->from_date);
            })
            ->get();
    }

    /**
     * @return mixed
     */
    public function advanceCollectedPayments()
    {
        $now = now();
        return InvoicePayment::where('prepared_by', auth()->id())
            ->whereDate('payment_date', '>', $now->toDateString())
            ->whereHas('invoice', function ($q) use ($now) {
                $q->whereDate('invoice_date', '<=', $now->toDateString());
            })
            ->get();
    }

    /**
     * @param null $payments
     * @return \Illuminate\Support\Collection
     */
    public function getPaymentsCollectedAmounts($payments = null)
    {
        $paymentsByPaymentMode = $this->paymentGroupByPaymentMode($payments);
        $amountByPaymentMode = [];
        $amountByPaymentMode['cash'] = $paymentsByPaymentMode->get('cash')->sum('payment');
        $amountByPaymentMode['cheque'] = $paymentsByPaymentMode->get('cheque')->sum('payment');
        $amountByPaymentMode['direct_deposit'] = $paymentsByPaymentMode->get('direct_deposit')->sum('payment');
        $amountByPaymentMode['credit_card'] = $paymentsByPaymentMode->get('credit_card')->sum('payment');
        return collect($amountByPaymentMode);
    }

    /**
     * @param $payments
     * @return \Illuminate\Support\Collection
     */
    public function paymentGroupByPaymentMode($payments)
    {
        $paymentsByPaymentMode = [];
        $paymentsByPaymentMode['cash'] = $payments->where('status', 'Paid')->where('payment_mode', 'Cash');
        $paymentsByPaymentMode['cheque'] = $payments->where('status', 'Paid')->where('payment_mode', 'Cheque');
        $paymentsByPaymentMode['direct_deposit'] = $payments->where('status', 'Paid')->where('payment_mode', 'Direct Deposit');
        $paymentsByPaymentMode['credit_card'] = $payments->where('status', 'Paid')->where('payment_mode', 'Credit Card');
        return collect($paymentsByPaymentMode);
    }

    /**
     * @return float
     */
    public function allocatedAllowance()
    {
        $allocations = getRepAllocation()->first();
        return $allocations->allowance ?? 0.00;
    }

    /**
     * @param string $method
     * @param SalesHandover|null $handover
     * @return array
     */
    public function breadcrumbs(string $method, SalesHandover $handover = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations']
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => 'Create']
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $handover->dailySale->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => $handover->daily_sale_id],
                ['text' => 'Confirm Sales Handover' ?? '']
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Sales', 'route' => 'sales.index'],
                ['text' => 'Allocations', 'route' => 'sales.allocation.index'],
                ['text' => $handover->dailySale->code ?? '', 'route' => 'sales.allocation.show', 'parameters' => $handover->daily_sale_id],
                ['text' => 'Edit Sales Handover' ?? '']
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @param DailySale $allocation
     * @return mixed
     */
    public function getCheques(DailySale $allocation)
    {
        if ($allocation->sales_location == 'Van') {
            $fromData = $allocation->from_date;
            $toDate = $allocation->to_date;
            $customers = $allocation->customers()->with('customer.payments')->get();
            $payments = $customers->pluck('customer')->pluck('payments')->collapse()->pluck('id')->toArray() ?? [];
            $payments = InvoicePayment::whereIn('id', $payments)->wherebetween('payment_date', [$fromData, $toDate])->where('payment_mode', 'Cheque')->where('status', 'Paid')->get();
        } else {
            $payments = $allocation->payments->where('payment_mode', 'Cheque')->where('status', 'Paid');
        }

        return $payments;
    }

    /**
     * @param SalesHandover $handover
     * @return array
     */
    public function getExpenses(SalesHandover $handover)
    {
        return $handover->salesExpenses;
    }

    /**
     * @param DailySale $allocation
     * @param SalesHandover $handover
     * @param Request $request
     * @return SalesHandover
     */
    public function approve(DailySale $allocation, SalesHandover $handover, Request $request)
    {
        $this->setModel($handover);
        //next day allocation data
        $data = [];
        $data['route'] = $request->input('route_id');
        $data['store'] = $request->input('store_id');
        $data['allocation'] = $allocation;
        $data['handover'] = $handover;
        $data['user'] = auth()->user();

        $cashCollection = $request->input('cashCollection');
        $products = $request->input('products');
        $cheques = $this->getCheques($allocation);
        $expenses = $this->getExpenses($handover);
        $chequesData = $request->input('cheques');
        $expensesData = $request->input('expenses');
        $ids = array_get($products, 'id');

        if (isCashierLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff()) {

            /** process and store cash breakdown details */
            if ($cashCollection) {
                foreach ($cashCollection as $key => $cash) {
                    if (array_get($cash, 'type') && array_get($cash, 'count')) {
                        $newCashBreakDown = new CashBreakdown();
                        $newCashBreakDown->date = carbon();
                        $newCashBreakDown->rupee_type = array_get($cash, 'type');
                        $newCashBreakDown->count = array_get($cash, 'count');
                        $newCashBreakDown->sales_handover_id = $handover->id;
                        $newCashBreakDown->prepared_by = auth()->user()->id;
                        $newCashBreakDown->save();
                    }
                }
            }

            /** process and store cheques in hand details */
            if ($cheques) {
                $chequeShortage = [];
                $payments = [];
                $chequeBank = [];
                $chequeDate = [];
                $chequeNo = [];
                if ($chequesData) {
                    $chequeShortage = array_get($chequesData, 'shortage');
                    $payments = array_get($chequesData, 'payment');
                    $chequeBank = array_get($chequesData, 'cheque_bank');
                    $chequeDate = array_get($chequesData, 'cheque_date');
                    $chequeNo = array_get($chequesData, 'cheque_no');
                    $chequeType = array_get($chequesData, 'cheque_type');
                }
                foreach ($cheques as $key => $payment) {
                    $hand = new ChequeInHand();
                    $hand->registered_date = carbon();
                    $hand->amount = array_get($payments, $payment->id, $payment->payment);
                    $hand->cheque_date = array_get($chequeDate, $payment->id, $payment->cheque_date);
                    $hand->cheque_no = array_get($chequeNo, $payment->id, $payment->cheque_no);
                    $hand->bank_id = array_get($chequeBank, $payment->id, $payment->bank_id);
                    $hand->chequeable_id = $payment->id;
                    $hand->chequeable_type = 'App\\' . class_basename($payment);
                    $hand->customer_id = $payment->customer_id;
                    $hand->daily_sale_id = $allocation->id;
                    $hand->sales_handover_id = $handover->id;
                    $hand->prepared_by = auth()->id();
                    $hand->business_type_id = $payment->business_type_id;
                    $hand->company_id = $payment->company_id;
                    $hand->shortage = array_get($chequeShortage, $payment->id) != 'None' ? array_get($chequeShortage, $payment->id) : null;
                    $hand->cheque_type = array_get($chequeType, $payment->id);
                    $hand->rep_id = $allocation->rep_id;
                    $hand->save();
                }
            }

            /** process and store expense details */
            if ($expenses) {
                $amounts = [];
                if ($expensesData) {
                    $amounts = array_get($expensesData, 'amount');
                }
                $this->setModel(new Expense());
                $this->setCodePrefix('EX', 'expense_no');
                foreach ($expenses as $key => $expense) {
                    $newExpense = new Expense();
                    $newExpense->expense_no = $this->getCode();
                    $newExpense->expense_date = $expense->expense_date;
                    $newExpense->type_id = $expense->type_id;
                    $newExpense->calculate_mileage_using = $expense->calculate_mileage_using ? $expense->calculate_mileage_using : 'Odometer';
                    $newExpense->notes = $expense->notes;
                    $newExpense->amount = array_get($amounts, $expense->id, $expense->amount);
                    $newExpense->company_id = $expense->company_id;
                    $newExpense->prepared_by = $expense->prepared_by;
                    $newExpense->approved_by = $expense->approved_by;
                    $newExpense->staff_id = $expense->staff_id;
                    $newExpense->company_id = $expense->company_id;
                    $newExpense->distance = $expense->distance;
                    $newExpense->start_reading = $expense->start_reading;
                    $newExpense->end_reading = $expense->end_reading;
                    $newExpense->gps_lat = $expense->gps_lat;
                    $newExpense->gps_long = $expense->gps_long;
                    $newExpense->liter = $expense->liter;
                    $newExpense->odometer = $expense->odometer;
                    $newExpense->sales_expense_id = $expense->id;
                    $newExpense->save();

                    /** expense related transaction */

                    /** get rep related cash account */
                    $creditAccount = Account::where('account_type_id', 1)->where('accountable_id', $allocation->rep_id)
                        ->where('accountable_type', 'App\Rep')->first();

                    $debitAccount = Account::find(27);
                    recordTransaction($newExpense, $debitAccount, $creditAccount, [
                        'date' => $newExpense->getAttribute('expense_date'),
                        'type' => 'Deposit',
                        'amount' => $newExpense->getAttribute('amount'),
                        'auto_narration' => 'Expense amount paid for '.$newExpense->getAttribute('notes'),
                        'manual_narration' => 'Expense amount paid for '.$newExpense->getAttribute('notes'),
                        'tx_type_id' => 1,
                        'company_id' => $newExpense->getAttribute('company_id'),
                    ], 'Expense');
                    /** END */
                }
                $this->setModel($handover);
                $this->setCodePrefix('SHO', 'code');
            }

            /** process and store shortage details */
            $shortage = $request->input('shortage');
            if ($shortage) {

                $handover->shortage = $shortage;

                // save shortage
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

            $total_collect = $request->input('total_collect');
            $total_expense = $request->input('total_expense');
            if ($total_collect) {
                $handover->total_collect = (float)$total_collect;
            }

            /** process and store excess details */
            $excess = $request->input('excess');
            if ($excess) {

                $handover->excess = (float)$excess;

                // save excess
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

            $handover->setAttribute('is_cashier_approved', 'Yes');
            $handover->setAttribute('cashier_id', auth()->id());
            $handover->save();
        }

        if (isStoreLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff()) {
            //productData
            $restoreQty = array_get($products, 'restore_qty');
            $returned_qty = array_get($products, 'returned_qty');
            $shortage_qty = array_get($products, 'shortage_qty');
            $damaged_qty = array_get($products, 'damaged_qty');
            if ($ids) {
                foreach ($ids as $key => $val) {
                    $item = $allocation->items->where('id', $key)->first();
                    $item->restored_qty = (int)array_get($restoreQty, $key);
                    $item->returned_qty = (int)array_get($returned_qty, $key);
                    $item->shortage_qty = (int)array_get($shortage_qty, $key);
                    $item->damaged_qty = (int)array_get($damaged_qty, $key);
                    $item->save();
                }
            }
            $handover->setAttribute('is_sk_approved', 'Yes');
            $handover->setAttribute('sk_id', auth()->id());
            $handover->save();
        }

        // update stock when store level staff approve the handover
        if ($ids && (isStoreLevelStaff() || isDirectorLevelStaff() || isAccountLevelStaff())) {
            $this->stockUpdateJob($allocation->items->whereIn('id', array_keys($ids)), $allocation);
        }

        if ($handover->getAttribute('is_cashier_approved') == 'Yes' && $handover->getAttribute('is_sk_approved') == 'Yes') {
            $handover->setAttribute('status', 'Confirmed');
            $handover->save();

            /** update allocation data status to - Completed */
            $allocation->setAttribute('status', 'Completed');
            $allocation->save();

            /** dispatch the job to create next date stock allocation */
            dispatch(new NextDayAllocationCreateJob($data));
        }

        /** get rep's cash and CIH accounts balance and transfer to rep's company cash and CIH accounts */
        $repCashAccount = Account::where('accountable_id', $allocation->rep_id)
            ->where('accountable_type', 'App\Rep')
            ->where('account_type_id', 1)
            ->first();

        $allocationCompanyCashAccount = Account::where('accountable_id', $allocation->company_id)
            ->where('accountable_type', 'App\Company')
            ->where('account_type_id', 1)
            ->first();

        $repCihAccount = Account::where('accountable_id', $allocation->rep_id)
            ->where('accountable_type', 'App\Rep')
            ->where('account_type_id', 19)
            ->first();

        $allocationCompanyCihAccount = Account::where('accountable_id', $allocation->company_id)
            ->where('accountable_type', 'App\Company')
            ->where('account_type_id', 19)
            ->first();

        /** get rep accounts running balances */
        //$repCashAccountBal = accBalance($repCashAccount)['balance'];
        $repCashAccountBal = getBreakDownTotal($handover->breakdowns);
        $repCihAccountBal = accBalance($repCihAccount)['balance'];

        /** transfer rep cash balance */
        if($repCashAccountBal && $repCashAccountBal > 0){
            $this->recordHandOverTransaction($handover, $repCashAccountBal, $allocationCompanyCashAccount, $repCashAccount);
        }

        /** transfer rep CIH balance */
        if($repCihAccountBal && $repCihAccountBal > 0){
            $this->recordHandOverTransaction($handover, $repCihAccountBal, $allocationCompanyCihAccount, $repCihAccount);
        }

        /** get rep and staff data */
        $repData = Rep::where('id', $allocation->getAttribute('rep_id'))->first();
        $staffData = $repData->staff;

        /** create stock excess records if available */
        $this->recordExcessStocks($allocation, $handover, $repData, $staffData);
        /** END */

        /** create stock shortage records if available */
        $this->recordShortageStocks($allocation, $handover, $repData, $staffData);
        /** END */

        /** create stock damaged records if available */
        $this->recordDamagedStocks($allocation);
        /** END */

        return $handover;
    }

    protected function recordHandOverTransaction(SalesHandover $handover, $balance, $debitAccount, $creditAccount)
    {
        recordTransaction($handover, $debitAccount, $creditAccount, [
            'date' => $handover->getAttribute('date'),
            'type' => 'Deposit',
            'amount' => $balance,
            'auto_narration' => 'Cash amount of '.number_format($balance).' was transferred to '.$debitAccount->name.' from '.$creditAccount->name,
            'manual_narration' => 'Cash amount of '.number_format($balance).' was transferred to '.$debitAccount->name.' from '.$creditAccount->name,
            'tx_type_id' => 6,
            'company_id' => $handover->getAttribute('company_id'),
        ], 'Transfer');
    }

    /**
     * @param $items
     * @param $allocation
     */
    public function stockUpdateJob($items, $allocation)
    {
        $data = [];
        foreach ($items as $item) {
            $data[$item->id] = [];
            $productId = $item->product_id;
            $storeId = $item->store_id;
            $quantity = $item->restored_qty;
            $stock = Stock::where('product_id', $productId)->where('store_id', $storeId)->first();
            $data[$item->id]['quantity'] = $quantity;
            $data[$item->id]['stock'] = $stock;
            $data[$item->id]['transable'] = $this->model;
            $data[$item->id]['allocationRoute'] = $allocation->route ? $allocation->route->name : 'Not Specified';
            $data[$item->id]['allocationDate'] = $allocation->from_date;
        }
        dispatch(new StockUpdateJob('In', $data));
    }

    public function handoverOrders()
    {
        $allocation = getRepAllocation()->first();
        return SalesOrder::where('prepared_by', auth()->id())
            ->whereIn('status', ['Open', 'Closed'])
            ->whereBetween('order_date', [$allocation->from_date, $allocation->to_date])
            ->get();
    }

    public function storeOdometer(Request $request, SalesHandover $handover, DailySale $allocation)
    {
        /** @var DailySalesOdoReading $odoReading */
        $odoReading = DailySalesOdoReading::where('daily_sale_id', $allocation->id)->first();
        if (!$odoReading) return null;
        $odoReading->setAttribute('sales_handover_id', $handover->id);
        $odoReading->setAttribute('ends_at', $request->input('odometer_end_reading'));
        $odoReading->save();
        return $odoReading;
    }


    protected function getExpenseTypeID($type)
    {
        switch ($type) {
            case 'Mileage';
                $id = mileageTypeId();
                break;
            case 'Fuel';
                $id = fuelTypeId();
                break;
            case 'Allowance';
                $id = allowanceTypeId();
                break;
            default;
                $id = generalTypeId();
        }
        return $id;
    }

    public function isAllActualStockConfirmed(DailySale $allocation)
    {
        $notConfirmedStocks = DailySaleItem::where('daily_sale_id', $allocation->id)
            ->where('actual_stock', null)->count();
        return $notConfirmedStocks == 0 ? true : false;
    }

    public function returnChequesPayments(DailySale $allocation)
    {
        return ChequePayment::where('prepared_by', auth()->id())
            ->where('daily_sale_id', $allocation->id)
            ->whereBetween('payment_date', [$allocation->from_date, $allocation->to_date])
            ->get();
    }

    public function getReturnedChequeAmounts($payments = null)
    {
        $paymentsByPaymentMode = $this->paymentGroupByPaymentMode($payments);
        $amountByPaymentMode = [];
        $amountByPaymentMode['cash'] = $paymentsByPaymentMode->get('cash')->sum('payment');
        $amountByPaymentMode['cheque'] = $paymentsByPaymentMode->get('cheque')->sum('payment');
        $amountByPaymentMode['direct_deposit'] = $paymentsByPaymentMode->get('direct_deposit')->sum('payment');
        $amountByPaymentMode['credit_card'] = $paymentsByPaymentMode->get('credit_card')->sum('payment');
        return collect($amountByPaymentMode);
    }

    /**
     * @param DailySale $allocation
     * @param SalesHandover $handover
     * @param $repData
     * @param $staffData
     */
    protected function recordExcessStocks(DailySale $allocation, SalesHandover $handover, $repData, $staffData)
    {
        $excessItems = DailySaleItem::where('daily_sale_id', $allocation->getAttribute('id'))
            ->where('excess_qty', '>', 0)->get();

        $excessItems = $excessItems->transform(function($item) use ($allocation){
            $item->rate = getProductSellingPrice($allocation, $item->product_id, $item->excess_qty);
            $item->amount = ($item->excess_qty * getProductSellingPrice($allocation, $item->product_id, $item->excess_qty));
            return $item;
        });

        if($excessItems->count()) {

            /** calculate amount */
            $amount = $excessItems->sum('amount');

            /** create stock excess record and items */
            $stockExcess = new StockExcess();
            $stockExcess->setAttribute('date', $handover->getAttribute('date'));
            $stockExcess->setAttribute('amount', $amount);
            $stockExcess->setAttribute('notes', 'Stock excess from - '.$allocation->route->name);
            $stockExcess->setAttribute('prepared_by', auth()->id());
            $stockExcess->setAttribute('prepared_on', carbon()->now()->toDateTimeString());
            $stockExcess->setAttribute('route_id', $allocation->getAttribute('route_id'));
            $stockExcess->setAttribute('rep_id', $repData->id);
            $stockExcess->setAttribute('staff_id', $staffData->id);
            $stockExcess->setAttribute('daily_sale_id', $allocation->getAttribute('id'));
            $stockExcess->setAttribute('sales_handover_id', $handover->getAttribute('id'));
            $stockExcess->setAttribute('company_id', $allocation->getAttribute('company_id'));
            $stockExcess->save();

            foreach ($excessItems as $excessItem) {
                $stockExcessItem = new StockExcessItem();
                $stockExcessItem->setAttribute('date', $stockExcess->getAttribute('date'));
                $stockExcessItem->setAttribute('qty', $excessItem->excess_qty);
                $stockExcessItem->setAttribute('rate', $excessItem->rate);
                $stockExcessItem->setAttribute('amount', $excessItem->amount);
                $stockExcessItem->setAttribute('product_id', $excessItem->product_id);
                $stockExcessItem->setAttribute('store_id', $excessItem->store_id);
                $stockExcessItem->setAttribute('stock_excess_id', $stockExcess->getAttribute('id'));
                $stockExcessItem->save();
            }
        }
    }

    protected function recordShortageStocks(DailySale $allocation, SalesHandover $handover, $repData, $staffData)
    {
        $shortageItems = DailySaleItem::where('daily_sale_id', $allocation->getAttribute('id'))
            ->where('shortage_qty', '>', 0)->get();

        $shortageItems = $shortageItems->transform(function($item) use ($allocation){
            $item->rate = getProductSellingPrice($allocation, $item->product_id, $item->shortage_qty);
            $item->amount = ($item->shortage_qty * getProductSellingPrice($allocation, $item->product_id, $item->shortage_qty));
            return $item;
        });

        if($shortageItems->count()) {

            /** calculate amount */
            $amount = $shortageItems->sum('amount');

            /** create stock shortage record and items */
            $stockShortage = new StockShortage();
            $stockShortage->setAttribute('date', $handover->getAttribute('date'));
            $stockShortage->setAttribute('amount', $amount);
            $stockShortage->setAttribute('notes', 'Stock shortage from - '.$allocation->route->name);
            $stockShortage->setAttribute('prepared_by', auth()->id());
            $stockShortage->setAttribute('prepared_on', carbon()->now()->toDateTimeString());
            $stockShortage->setAttribute('route_id', $allocation->getAttribute('route_id'));
            $stockShortage->setAttribute('rep_id', $repData->id);
            $stockShortage->setAttribute('staff_id', $staffData->id);
            $stockShortage->setAttribute('daily_sale_id', $allocation->getAttribute('id'));
            $stockShortage->setAttribute('sales_handover_id', $handover->getAttribute('id'));
            $stockShortage->setAttribute('company_id', $allocation->getAttribute('company_id'));
            $stockShortage->save();

            foreach ($shortageItems as $shortageItem) {
                $stockShortageItem = new StockShortageItem();
                $stockShortageItem->setAttribute('date', $stockShortage->getAttribute('date'));
                $stockShortageItem->setAttribute('qty', $shortageItem->shortage_qty);
                $stockShortageItem->setAttribute('rate', $shortageItem->rate);
                $stockShortageItem->setAttribute('amount', $shortageItem->amount);
                $stockShortageItem->setAttribute('product_id', $shortageItem->product_id);
                $stockShortageItem->setAttribute('store_id', $shortageItem->store_id);
                $stockShortageItem->setAttribute('stock_shortage_id', $stockShortage->getAttribute('id'));
                $stockShortageItem->save();
            }
        }
    }

    protected function recordDamagedStocks(DailySale $allocation)
    {
        $damagedItems = DailySaleItem::where('daily_sale_id', $allocation->getAttribute('id'))
            ->where('damaged_qty', '>', 0)->get();

        $damagedItems = $damagedItems->transform(function($item) use ($allocation){
            $item->rate = getProductSellingPrice($allocation, $item->product_id, $item->shortage_qty);
            $item->amount = ($item->shortage_qty * getProductSellingPrice($allocation, $item->product_id, $item->shortage_qty));
            return $item;
        });

        if($damagedItems->count()) {

            $damagedStore = Store::where('type', 'Damage')
                ->where('company_id', $allocation->getAttribute('company_id'))
                ->first();

            if($damagedStore) {
                $damagedItems->each(function (DailySaleItem $dailySaleItem) use ($damagedStore, $allocation) {

                    /** check stock available or not  */
                    $damagedStock = Stock::where('category', 'Damage')
                        ->where('company_id', $damagedStore->company_id)
                        ->where('product_id', $dailySaleItem->getAttribute('product_id'))
                        ->where('store_id', $damagedStore->id)->first();

                    if($damagedStock) {
                        /** add qty to damage stock */
                        $damagedStock->available_stock = ($damagedStock->available_stock + $dailySaleItem->getAttribute('damaged_qty'));
                        $damagedStock->save();

                        /** add damaged history */
                        $dgStockHis = new StockHistory();
                        $dgStockHis->setAttribute('stock_id', $damagedStock->id);
                        $dgStockHis->setAttribute('quantity', $dailySaleItem->getAttribute('damaged_qty'));
                        $dgStockHis->setAttribute('rate', 0);
                        $dgStockHis->setAttribute('type', 'Damage');
                        $dgStockHis->setAttribute('transaction', 'In');
                        $dgStockHis->setAttribute('trans_date', carbon()->now()->toDateString());
                        $dgStockHis->setAttribute('trans_description', 'Damaged stocks from - '.$allocation->route->name);
                        $dgStockHis->setAttribute('store_id', $damagedStore->id);
                        $dgStockHis->save();
                    } else {
                        /** need to create a damaged stock and add stock history */
                        $newDgStock = new Stock();
                        $newDgStock->setAttribute('store_id', $damagedStore->id);
                        $newDgStock->setAttribute('available_stock', $dailySaleItem->getAttribute('damaged_qty'));
                        $newDgStock->setAttribute('product_id', $dailySaleItem->getAttribute('product_id'));
                        $newDgStock->setAttribute('notes', 'Damaged stocks from - '.$allocation->route->name);
                        $newDgStock->setAttribute('type', 'Auto');
                        $newDgStock->setAttribute('category', 'Damage');
                        $newDgStock->setAttribute('company_id', $damagedStore->company_id);
                        $newDgStock->setAttribute('min_stock_level', '5000');
                        $newDgStock->save();

                        /** add damaged history */
                        $newDgStockHis = new StockHistory();
                        $newDgStockHis->setAttribute('stock_id', $newDgStock->getAttribute('id'));
                        $newDgStockHis->setAttribute('quantity', $dailySaleItem->getAttribute('damaged_qty'));
                        $newDgStockHis->setAttribute('rate', 0);
                        $newDgStockHis->setAttribute('type', 'Damage');
                        $newDgStockHis->setAttribute('transaction', 'In');
                        $newDgStockHis->setAttribute('trans_date', carbon()->now()->toDateString());
                        $newDgStockHis->setAttribute('trans_description', 'Damaged stocks from - '.$allocation->route->name);
                        $newDgStockHis->setAttribute('store_id', $damagedStore->id);
                        $newDgStockHis->save();
                    }
                });
            }
        }
    }

}
