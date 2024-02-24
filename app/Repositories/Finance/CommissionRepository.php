<?php

namespace App\Repositories\Finance;

use App\Account;
use App\ChequeInHand;
use App\Customer;
use App\DailySale;
use App\DailySaleCustomer;
use App\DailySaleItem;
use App\Invoice;
use App\InvoicePayment;
use App\Product;
use App\Rep;
use App\Repositories\BaseRepository;
use App\SalesCommission;
use App\SalesOrder;
use App\SalesReturn;
use App\SalesReturnItem;
use App\SalesReturnReplaces;
use App\SalesReturnResolution;
use Illuminate\Http\Request;

/**
 * Class CommissionRepository
 * @package App\Repositories\Finance
 */
class CommissionRepository extends BaseRepository
{
    protected $commission;

    /**
     * CommissionRepository constructor.
     * @param SalesCommission|null $commission
     */
    public function __construct(SalesCommission $commission = null)
    {
        $this->setModel($commission ?? new SalesCommission());
    }

    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();

        $commissions = SalesCommission::whereIn('company_id', userCompanyIds(loggedUser()))
            ->orderBy('id', 'desc')->with('rep', 'company');

        switch ($filter) {
            case 'Drafted':
                $commissions->where('status', 'Drafted');
                break;
            case 'Approved':
                $commissions->where('status', 'Approved');
                break;
            case 'recentlyCreated':
                $commissions->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $commissions->where('updated_at', '>', $lastWeek);
                break;
        }
        return $commissions->paginate(12)->toArray();
    }

    /**
     * @param Request $request
     * @param Rep $rep
     * @param $year
     * @param $month
     * @return mixed
     */
    public function store(Request $request, Rep $rep, $year, $month)
    {
        $this->model->setAttribute('date', carbon()->now()->toDateString());
        $this->model->setAttribute('year', $year);
        $this->model->setAttribute('month', $month);

        $this->model->setAttribute('credit_sales', $request->input('credit_sales'));
        $this->model->setAttribute('cheque_received', $request->input('cheque_received'));
        $this->model->setAttribute('cheque_collection_dr', $request->input('cheque_collection_dr'));
        $this->model->setAttribute('sales_returned', $request->input('sales_returned'));
        $this->model->setAttribute('cheque_returned', $request->input('cheque_returned'));

        $this->model->setAttribute('sales_target', $request->input('sales_target'));
        $this->model->setAttribute('special_target', $request->input('special_target'));

        $this->model->setAttribute('total_sales', $request->input('total_sales'));
        $this->model->setAttribute('cash_collection', $request->input('cash_collection'));
        $this->model->setAttribute('cheque_collection_cr', $request->input('cheque_collection_cr'));
        $this->model->setAttribute('cheque_realized', $request->input('cheque_realized'));

        $this->model->setAttribute('customer_visited_count', $request->input('customer_visited_count'));
        $this->model->setAttribute('customer_visited_rate', $request->input('customer_visited_rate'));
        $this->model->setAttribute('customer_visited', $request->input('customer_visited'));

        $this->model->setAttribute('product_sold_count', $request->input('product_sold_count'));
        $this->model->setAttribute('product_sold_rate', $request->input('product_sold_rate'));
        $this->model->setAttribute('product_sold', $request->input('product_sold'));
        $this->model->setAttribute('special_commission', $request->input('special_commission'));

        $this->model->setAttribute('debit_balance', $request->input('debit_balance'));
        $this->model->setAttribute('credit_balance', $request->input('credit_balance'));

        $this->model->setAttribute('notes', 'Sales commission for '.$rep->getAttribute('name'));

        $this->model->setAttribute('prepared_by', auth()->id());
        $this->model->setAttribute('prepared_on', carbon()->now()->toDateTimeString());

        $this->model->setAttribute('staff_id', $rep->getAttribute('staff_id'));
        $this->model->setAttribute('rep_id', $rep->getAttribute('id'));
        $this->model->setAttribute('company_id', $rep->getAttribute('company_id'));
        $this->model->save();

        $model =  $this->model->refresh();
        return $model;
    }

    /**
     * @param Rep $rep
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getCreditSales(Rep $rep, $startDate, $endDate)
    {
        $data = [];

        /** get cash sales */
        /*$cashOrderIds = SalesOrder::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('order_date', [$startDate, $endDate])
            ->whereIn('status', ['Open', 'Closed'])
            ->pluck('id');
        $cashPayments = InvoicePayment::whereIn('sales_order_id', $cashOrderIds)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get();
        $cashSales = $cashPayments->where('status', 'Paid')->sum('payment');*/

        /** get credit sales */
        /*$totalOrders = SalesOrder::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('order_date', [$startDate, $endDate])
            ->whereIn('status', ['Open', 'Closed'])
            ->get();

        $totalsSales = $totalOrders->sum('total');
        $creditSales = ($totalsSales - $cashSales);*/

        /** get credit orders */
        /*$creditOrders = SalesOrder::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('order_date', [$startDate, $endDate])
            ->whereIn('status', ['Open', 'Closed'])
            ->with('customer')
            ->get();
        $creditOrders = $creditOrders->reject(function ($order) {
            $amount = $order->total;
            $payments = $order->payments;
            $others = $payments->where('status', 'Paid')->whereNotIn('payment_mode', ['Cheque'])->sum('payment');
            $cheques = $payments->where('status', 'Paid')->where('payment_mode', 'Cheque')->sum('payment');
            $order->balance = $order->total - ($others + $cheques);
            $order->paid = $others + $cheques;
            if ($amount == ($others + $cheques)) {
                return true;
            }
            return false;
        });*/

        /** get allocation */
        $allocations = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('to_date', [$startDate, $endDate])->get();
        $allocationIds = $allocations->pluck('id')->toArray();

        $orders = SalesOrder::whereIn('daily_sale_id', $allocationIds)
            ->whereIn('status', ['Open', 'Closed'])->get();
        $orderIds = $orders->pluck('id')->toArray();

        $payments = InvoicePayment::whereIn('sales_order_id', $orderIds)
            ->whereIn('daily_sale_id', $allocationIds)->get();

        $total = $orders->sum('total');

        $received = $payments->where('status', 'Paid')->sum('payment');

        $balance = ($total - $received);

        /** get credit orders */
        $creditOrders = SalesOrder::whereIn('daily_sale_id', $allocationIds)
            ->whereIn('status', ['Open', 'Closed'])
            ->where('is_credit_sales', 'Yes')
            ->with('customer')
            ->get();

        $creditOrders = $creditOrders->reject(function (SalesOrder $order) use ($startDate, $endDate){
            $amount = $order->total;
            $payments = InvoicePayment::where('sales_order_id', $order->id)
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->where('status', 'Paid')
                ->get();
            $received = $payments->sum('payment');
            $order->balance = $order->total - $received;
            $order->paid = $received;
            if ($amount == $received) {
                return true;
            }
            return false;
        });

        $data['creditSales'] = $balance;
        $data['creditOrders'] = $creditOrders;

        return $data;
    }

    public function getTotalSales(Rep $rep, $startDate, $endDate)
    {
        /** get total sales */
        $totalOrders = SalesOrder::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('order_date', [$startDate, $endDate])
            ->whereIn('status', ['Open', 'Closed'])->get();
        $totalsSales = $totalOrders->sum('total');
        return $totalsSales;
    }

    public function getTotalSalesOrders(Rep $rep, $startDate, $endDate)
    {
        /** get total sales */
        $totalOrders = SalesOrder::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('order_date', [$startDate, $endDate])
            ->whereIn('status', ['Open', 'Closed'])->get();
        return $totalOrders;
    }

    public function getChequeReceived(Rep $rep, $startDate, $endDate)
    {
        $data = [];

        /** get cheque received */
        /*$cashOrderIds = SalesOrder::where('rep_id', $rep->getAttribute('id'))
            ->whereIn('status', ['Open', 'Closed'])
            ->pluck('id');
        $payments = InvoicePayment::whereBetween('payment_date', [$startDate, $endDate])
            ->whereIn('sales_order_id', $cashOrderIds)
            ->with(['order', 'customer'])
            ->get();
        $payments = $payments->reject(function ($payment) {
            $paymentDate = $payment->payment_date;
            $orderDate = $payment->order->order_date;
            return $paymentDate != $orderDate;
        });
        $chequeReceived = $payments->where('payment_mode', 'Cheque')
            ->where('status', 'Paid')->sum('payment');

        $chequeReceivedItems = $payments->where('payment_mode', 'Cheque')
            ->where('status', 'Paid')//->groupByChequeNo;

        $data['chequeReceived'] = $chequeReceived;
        $data['payments'] = $chequeReceivedItems;*/

        /** get allocation */
        $allocations = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('to_date', [$startDate, $endDate])->get();
        $allocationIds = $allocations->pluck('id')->toArray();

        $orders = SalesOrder::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('order_date', [$startDate, $endDate])
            ->whereIn('daily_sale_id', $allocationIds)
            ->whereIn('status', ['Open', 'Closed'])->get();

        $orders = $orders->transform(function ($item) use ($allocationIds, $rep){
            $item->cheques = InvoicePayment::where('sales_order_id', $item->id)
                ->where('payment_date', $item->order_date)
                ->where('payment_mode', 'Cheque')
                ->where('status', 'Paid')
                ->whereIn('daily_sale_id', $allocationIds)
                ->get();
            return $item;
        });

        $orders = $orders->reject(function ($order) {
            return count($order->cheques) == 0;
        });

        $cheques = groupByCallbackForCheque($orders->pluck('cheques')->collapse());
        $chequeReceived = $orders->pluck('cheques')->collapse()->sum('payment');

        $data['payments'] = $cheques;
        $data['chequeReceived'] = $chequeReceived;

        return $data;
    }

    public function getCashCollection(Rep $rep, $startDate, $endDate)
    {
        $data = [];

        $staff = $rep->staff;
        $user = $staff->user;

        /** get cash collection */
        /*$cashOrderIds = SalesOrder::where('rep_id', $rep->getAttribute('id'))
            ->whereIn('status', ['Open', 'Closed'])
            ->pluck('id');
        $payments = InvoicePayment::whereBetween('payment_date', [$startDate, $endDate])
            ->where('prepared_by', $user->id)
            ->with(['order', 'customer'])
            ->get();
        $payments = $payments->reject(function ($payment) {
            $paymentDate = $payment->payment_date;
            $orderDate = $payment->order->order_date;
            return $paymentDate == $orderDate;
        });
        $cashCollection = $payments->where('payment_mode', 'Cash')
            ->where('status', 'Paid')->sum('payment');

        $cashCollectionItems = $payments->where('payment_mode', 'Cash')
            ->where('status', 'Paid');*/

        $allocations = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('from_date', [$startDate, $endDate])->get();
        $allocationIds = $allocations->pluck('id')->toArray();

        /** get allocation */
        $payments = InvoicePayment::where('status', 'Paid')
            ->whereIn('daily_sale_id', $allocationIds)
            ->where('payment_mode', 'Cash')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->whereIn('prepared_by', [$user->id, 1])
            ->with(['order', 'customer'])
            ->get();

        $payments = $payments->reject(function ($payment) {
            $paymentDate = $payment->payment_date;
            $orderDate = $payment->order->order_date;
            return $paymentDate == $orderDate;
        });

        $oldCashReceived = $payments->sum('payment');

        $data['cashCollection'] = $oldCashReceived;
        $data['payments'] = $payments;

        return $data;
    }

    public function getChequeCollection(Rep $rep, $startDate, $endDate)
    {
        $data = [];

        $staff = $rep->staff;
        $user = $staff->user;

        /** get cash collection */
        /*$cashOrderIds = SalesOrder::where('rep_id', $rep->getAttribute('id'))
            ->whereIn('status', ['Open', 'Closed'])
            ->pluck('id');
        $payments = InvoicePayment::whereBetween('payment_date', [$startDate, $endDate])
            ->whereIn('sales_order_id', $cashOrderIds)
            ->with(['order', 'customer'])
            ->get();
        $payments = $payments->reject(function ($payment) {
            $paymentDate = $payment->payment_date;
            $orderDate = $payment->order->order_date;
            return $paymentDate == $orderDate;
        });
        $chequeCollection = $payments->where('payment_mode', 'Cheque')
            ->where('status', 'Paid')->sum('payment');

        $chequeCollectionItems = $payments->where('payment_mode', 'Cheque')
            ->where('status', 'Paid')//->groupByChequeNo;*/

        /** get allocation */
        $allocations = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('from_date', [$startDate, $endDate])->get();
        $allocationIds = $allocations->pluck('id')->toArray();

        /** get allocation */
        $payments = InvoicePayment::where('status', 'Paid')
            ->whereIn('daily_sale_id', $allocationIds)
            ->where('payment_mode', 'Cheque')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->whereIn('prepared_by', [$user->id, 1])
            ->with(['order', 'customer'])
            ->get();

        $payments = $payments->reject(function ($payment) {
            $paymentDate = $payment->payment_date;
            $orderDate = $payment->order->order_date;
            return $paymentDate == $orderDate;
        });

        $oldChequeReceived = $payments->sum('payment');

        $data['chequeCollection'] = $oldChequeReceived;
        $data['payments'] = groupByCallbackForCheque($payments);

        return $data;
    }

    public function getSalesReturn(Rep $rep, $startDate, $endDate)
    {
        $allocations = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('to_date', [$startDate, $endDate])->get();
        $allocationIds = $allocations->pluck('id')->toArray();

        /** get sales return */
        $salesReturn = SalesReturnItem::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('date', [$startDate, $endDate])
            ->where(function ($query) use ($allocationIds){
                $query->whereHas('salesReturn', function ($query) use ($allocationIds){
                    $query->whereIn('daily_sale_id', $allocationIds);
                });
            })
            ->sum('returned_amount');

        return $salesReturn;
    }

    public function getSalesReturnItems(Rep $rep, $startDate, $endDate)
    {
        $allocations = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('to_date', [$startDate, $endDate])->get();
        $allocationIds = $allocations->pluck('id')->toArray();

        /** get sales return */
        $salesReturn = SalesReturnItem::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('date', [$startDate, $endDate])
            ->where(function ($query) use ($allocationIds){
                $query->whereHas('salesReturn', function ($query) use ($allocationIds){
                    $query->whereIn('daily_sale_id', $allocationIds);
                });
            })
            ->get();

        return $salesReturn;
    }

    public function getExpiredSalesReturn(Rep $rep, $startDate, $endDate)
    {
        /** get sales return */
        $salesReturn = SalesReturnItem::where('reason', 'Product was expired')
            ->where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('returned_amount');
        return $salesReturn;
    }

    public function getExpiredSalesReturnItems(Rep $rep, $startDate, $endDate)
    {
        /** get sales return */
        $salesReturn = SalesReturnItem::where('reason', 'Product was expired')
            ->where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('date', [$startDate, $endDate])
            ->with('order', 'product', 'salesReturn', 'customer')
            ->get();
        return $salesReturn;
    }

    public function getChequeRealized(Rep $rep, $startDate, $endDate)
    {
        /** get cheque realized */
        /*$cashOrderIds = SalesOrder::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('order_date', [$startDate, $endDate])
            ->whereIn('status', ['Open', 'Closed'])
            ->pluck('id');
        $payments = InvoicePayment::whereIn('sales_order_id', $cashOrderIds)
            ->where('payment_mode', 'Cheque')
            ->whereBetween('cheque_date', [$startDate, $endDate])
            ->get();
        $chequeRealized = $payments->where('status', 'Paid')->sum('payment');*/

        /** get allocation */
        $allocations = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->where('to_date', '<=', $endDate)->get();
        $allocationIds = $allocations->pluck('id')->toArray();

        $cheques = ChequeInHand::whereIn('daily_sale_id', $allocationIds)
            ->whereBetween('cheque_date', [$startDate, $endDate])
            ->whereNotIn('status', ['Bounced','Canceled'])
            ->get();
        $chequeRealized = $cheques->sum('amount');

        return $chequeRealized;
    }

    public function getChequeRealizedItems(Rep $rep, $startDate, $endDate)
    {
        /** get cheque realized */
        /*$cashOrderIds = SalesOrder::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('order_date', [$startDate, $endDate])
            ->whereIn('status', ['Open', 'Closed'])
            ->pluck('id');
        $payments = InvoicePayment::whereIn('sales_order_id', $cashOrderIds)
            ->where('payment_mode', 'Cheque')
            ->whereBetween('cheque_date', [$startDate, $endDate])
            ->get();
        $chequeRealized = $payments->where('status', 'Paid')->//groupByChequeNo;*/

        /** get allocation */
        $allocations = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->where('to_date', '<=', $endDate)->get();
        $allocationIds = $allocations->pluck('id')->toArray();

        $cheques = groupByCallbackForCheque(ChequeInHand::whereIn('daily_sale_id', $allocationIds)
            ->whereBetween('cheque_date', [$startDate, $endDate])
            ->whereNotIn('status', ['Bounced','Canceled'])
            ->get());

        return $cheques;
    }

    public function getChequeReturned(Rep $rep, $startDate, $endDate)
    {
        /** get all bounced cheques */
        $cheques = ChequeInHand::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('bounced_date', [$startDate, $endDate])
            ->where('status', 'Bounced')
            ->get();

        $chequeReturned = $cheques->sum('amount');

        return $chequeReturned;
    }

    public function getChequeReturnedItems(Rep $rep, $startDate, $endDate)
    {
        /** get allocation */
        return groupByCallbackForCheque(ChequeInHand::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('bounced_date', [$startDate, $endDate])
            ->where('status', 'Bounced')
            ->get());
    }

    public function getVisitedCustomers(Rep $rep, $startDate, $endDate)
    {
        $allocations = DailySale::where('rep_id', $rep->getAttribute('id'))
            ->whereBetween('to_date', [$startDate, $endDate])->get();
        $allocationIds = $allocations->pluck('id')->toArray();

        $allocationCustomers = DailySaleCustomer::whereIn('daily_sale_id', $allocationIds)
            ->where('is_visited', 'Yes')
            ->where('reason', 'System - Order created')
            ->count();
        return $allocationCustomers;
    }

    /**
     * @param string $method
     * @return array
     */
    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Draft'],
            ],
            'credit-sales' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Credit Sales'],
            ],
            'total-sales' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Total Sales'],
            ],
            'cheque-received' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Cheque Received'],
            ],
            'cash-collection' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Cash Collection'],
            ],
            'cheque-collection' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Cheque Collection'],
            ],
            'sales-return' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Sales Return'],
            ],
            'cheque-realized' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Cheques Realized'],
            ],
            'cheque-returned' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Cheques Returned'],
            ],
            'cash-shortages' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Cash Shortages'],
            ],
            'stock-shortages' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Stock Shortages'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Finance', 'route' => 'finance.index'],
                ['text' => 'Sales Commission', 'route' => 'finance.commission.index', 'parameters' => carbon()->year],
                ['text' => 'Sales Commission Details'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
