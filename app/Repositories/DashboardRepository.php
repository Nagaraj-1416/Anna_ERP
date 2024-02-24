<?php

namespace App\Repositories;

use App\Bill;
use App\BillPayment;
use App\BusinessType;
use App\Company;
use App\Customer;
use App\CustomerCredit;
use App\DailySale;
use App\Expense;
use App\ExpenseReport;
use App\Invoice;
use App\InvoicePayment;
use App\Product;
use App\PurchaseOrder;
use App\Rep;
use App\SalesInquiry;
use App\SalesLocation;
use App\SalesOrder;
use App\Supplier;
use App\SupplierCredit;
use App\Estimate;
use Illuminate\Http\Request;

/**
 * Class VehicleRepository
 * @package App\Repositories\Settings
 */
class DashboardRepository extends BaseRepository
{

    /**
     * Get data to data table
     * @param Request $request
     * @param $columns
     * @param $searchingColumns
     * @param $relationColumns
     * @return array
     */
    public function dataTable(Request $request, $columns, $searchingColumns, $relationColumns, $data): array
    {
        $data = $this->getTableData($request, $columns, $searchingColumns, $relationColumns, true, null, $data);
        return $data;
    }

    public function getDueData(Request $request, $modal, $dateRange)
    {
        $data = collect([]);
        if ($modal == 'bill') {
            $columns = ['bill_no', 'due_date', 'amount'];
            $searchingColumns = ['bill_no', 'due_date', 'amount'];
            $relationColumns = [
                'supplier' => [
                    [
                        'column' => 'id', 'as' => 'supplier_id',

                    ],
                    [
                        'column' => 'display_name', 'as' => 'supplier',

                    ]
                ],
            ];
            $forIds = Bill::whereNotIn('status', ['Paid', 'Canceled'])->whereDate('due_date', '<', carbon())->get();
            $ids = $forIds->filter(function ($collection) use ($dateRange) {
                $dueDate = $collection->due_date;
                $diff = carbon()->diffInDays(carbon($dueDate));
                if ($dateRange == '>90') {
                    if ($diff > 90) return $collection;
                    return null;
                }
                if ($diff <= $dateRange) return $collection;
                return null;
            })->pluck('id')->toArray();
            $data = Bill::whereIn('id', $ids);
            $data = $this->dataTable($request, $columns, $searchingColumns, $relationColumns, $data);
        } else if ($modal == 'invoice') {
            $columns = ['invoice_no', 'due_date', 'amount', 'invoice_date'];
            $searchingColumns = ['invoice_no', 'due_date', 'amount', 'invoice_date'];
            $relationColumns = [
                'customer' => [
                    [
                        'column' => 'id', 'as' => 'customer_id',
                    ],
                    [
                        'column' => 'display_name', 'as' => 'customer'
                    ]
                ],
            ];
            $forIDs = Invoice::whereNotIn('status', ['Paid', 'Canceled', 'Refunded'])->whereDate('due_date', '<', carbon())->get();
            $ids = $forIDs->filter(function ($collection) use ($dateRange) {
                $dueDate = $collection->due_date;
                $diff = carbon()->diffInDays(carbon($dueDate));
                if ($dateRange == '>90') {
                    if ($diff > 90) return $collection;
                    return null;
                }
                if ($diff <= $dateRange) return $collection;
                return null;
            })->pluck('id')->toArray();
            $data = Invoice::whereIn('id', $ids);
            $data = $this->dataTable($request, $columns, $searchingColumns, $relationColumns, $data);
            $data['data'] = array_map(function ($item) {
                $item['customer'] = '<a href="' . route('sales.customer.show', $item['customer_id']) . '" target="_blank">' . $item['customer'] . '</a>';
                $item['invoice_no'] = '<a href="' . route('sales.invoice.show', $item['id']) . '" target="_blank">' . $item['invoice_no'] . '</a>';
                $item['amount'] = number_format($item['amount'], 2);
                return $item;
            }, $data['data']);
        }
        return $data;
    }

    /**
     * @param string $method
     * @param Vehicle|null $vehicle
     * @return array
     */
    public function breadcrumbs(string $method, Vehicle $vehicle = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => $vehicle->vehicle_no ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Vehicles', 'route' => 'setting.vehicle.index'],
                ['text' => $vehicle->vehicle_no ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    /**
     * @param bool $collection
     * @return array
     */
    public function getSalesStatData($collection = false)
    {
        $request = request();
        $request->validate([
            'company' => 'required',
            'rep' => 'required'
        ]);
        $rep = $request->input('rep');
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();

        $salesOrders = $this->salesDetails();
        $data = [];
        $data['orderData'] = [];
        $data['paymentsData'] = [];
        $data['masterData'] = [];

        $totalSales = $salesOrders->sum('total');
        $invoices = $salesOrders->pluck('invoices')->collapse();
        $totalInvoiced = $invoices->sum('amount');
        $payments = $salesOrders->pluck('payments')->collapse();
        $cheque = $payments->where('payment_mode', 'Cheque');
        $cash = $payments->where('payment_mode', 'Cash');
        $deposit = $payments->where('payment_mode', 'Direct Deposit');
        $card = $payments->where('payment_mode', 'Credit Card');
        $totalPaid = $payments->sum('payment');

        $data['orderData']['totalSales'] = $totalSales;
        $data['orderData']['totalInvoiced'] = $totalInvoiced;
        $data['orderData']['totalPaid'] = $totalPaid;
        $data['orderData']['totalSalesOrders'] = $salesOrders->count();
        $data['orderData']['balance'] = $totalSales - $totalPaid;

        $data['paymentsData']['cheque'] = $cheque->sum('payment');
        $data['paymentsData']['cash'] = $cash->sum('payment');
        $data['paymentsData']['deposit'] = $deposit->sum('payment');
        $data['paymentsData']['card'] = $card->sum('payment');
        $data['paymentsData']['total'] = $data['paymentsData']['cheque'] + $data['paymentsData']['cash'] + $data['paymentsData']['deposit'] + $data['paymentsData']['card'];


        $data['salesVisitData'] = [];
        $data['salesExpensesData'] = [];
        $data['salesVisitData']['allocated'] = [];
        $data['salesVisitData']['visited'] = [];
        $data['salesVisitData']['notVisited'] = [];
        $data['salesExpensesData']['general'] = [];
        $data['salesExpensesData']['fuel'] = [];
        $data['salesExpensesData']['total'] = [];
        $rep = Rep::find($rep);
        if ($rep) {
            $allocations = $rep->dailySales()->where('from_date', '>=', $fromDate)->where('to_date', '<=', $toDate)->get();
            $customers = $allocations->pluck('customers')->collapse();
            $data['salesVisitData']['allocated'] = $customers->count();
            $data['salesVisitData']['visited'] = $customers->where('is_visited', 'Yes')->count();
            $data['salesVisitData']['notVisited'] = $customers->where('is_visited', 'No')->count();

            $expenses = $allocations->pluck('salesExpenses')->collapse();
            $data['salesExpensesData']['allowance'] = $expenses->where('type_id', 3)->sum('amount');
            $data['salesExpensesData']['general'] = $expenses->where('type_id', 4)->sum('amount');
            $data['salesExpensesData']['parking'] = $expenses->where('type_id', 5)->sum('amount');
            $data['salesExpensesData']['repairs'] = $expenses->where('type_id', 6)->sum('amount');
            $data['salesExpensesData']['mileage'] = $expenses->where('type_id', 1)->sum('amount');
            $data['salesExpensesData']['fuel'] = $expenses->where('type_id', 2)->sum('amount');
            $data['salesExpensesData']['total'] = $expenses->whereIn('type_id', [3, 4, 5, 6, 1, 2])->sum('amount');
        }
        if ($collection) {
            $data['masterData']['orders'] = $salesOrders;
            $data['masterData']['cash'] = $cash;
            $data['masterData']['cheque'] = $cheque;
            $data['masterData']['deposit'] = $deposit;
            $data['masterData']['card'] = $card;
        } else {
            $data['masterData']['orders'] = array_values($salesOrders->toArray());
            $data['masterData']['cash'] = array_values($cash->toArray());
            $data['masterData']['cheque'] = array_values($cheque->toArray());
            $data['masterData']['deposit'] = array_values($deposit->toArray());
            $data['masterData']['card'] = array_values($card->toArray());
        }
        return $data;
    }

    /**
     * @return mixed
     */
    public function salesDetails()
    {
        $request = request();
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $company = $request->input('company');
        $rep = $request->input('rep');
        $salesOrders = SalesOrder::whereBetween('order_date', [$fromDate, $toDate])->with(['invoices.payments', 'customer', 'payments', 'payments.preparedBy',
            'payments.depositedTo', 'payments.invoice']);
        if ($company) {
            $salesOrders->where('company_id', $company);
        }

        if ($rep) {
            $salesOrders->where('rep_id', $rep);
        }
        $salesOrders = $salesOrders->get();
        $salesOrders = $salesOrders->map(function ($order) {
            $order->created_on = $order->created_at->format('F j, Y, g:i a');
            $order->by_cash = soOutstanding($order)['byCash'];
            $order->by_cheque = soOutstanding($order)['byCheque'];
            $order->by_deposit = soOutstanding($order)['byDeposit'];
            $order->by_card = soOutstanding($order)['byCard'];
            $order->credit = soOutstanding($order)['balance'];
            return $order;
        });
        return $salesOrders;
    }

    /**
     * @return array
     */
    public function exportSalesStat()
    {
        $sales = $this->getSalesStatData(true);
        $request = request();
        $company = $request->input('company');
        $businessType = $request->input('businessType');
        $rep = $request->input('rep');
        $sales['request'] = request()->toArray();

        if ($company) {
            $sales['request']['company'] = Company::find($company);
        }
        if ($businessType) {
            $sales['request']['businessType'] = BusinessType::find($businessType);
        }
        if ($rep) {
            $sales['request']['rep'] = Rep::find($rep);
        }
        return $sales;
    }


    public function getCompanyStatsData($export = false)
    {
        $request = request();
        $request->validate([
            'company' => 'required',
        ]);
        $page = $request->input('page');
        $company = $request->input('company');
        $formDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $data = [];
        $data['request'] = $request->toArray();
        $data['purchase_data'] = [];
        $data['sales_data'] = [];
        $data['expense_data'] = [];
        $data['sales_by_customer'] = [];
        $data['purchase_by_supplier'] = [];
        $data['sales_by_products'] = [];
        $data['purchase_by_products'] = [];
        $data['sales_by_shop'] = [];
        $data['sales_by_rep'] = [];

        //Purchase Data
        $data['purchase_data']['order_data'] = [];
        $data['purchase_data']['payment_data'] = [];
        $data['purchase_data']['credit_data'] = [];

        $data['sales_data']['order_data'] = [];
        $data['sales_data']['payment_data'] = [];
        $data['sales_data']['credit_data'] = [];
        $data['sales_data']['estimate_date'] = [];

        $data['expense_data']['receipts_data'] = [];
        $data['expense_data']['reports_data'] = [];
        $data['expense_data']['payments_data'] = [];
        $data['expense_data']['customer'] = [];
        $data['expense_data']['supplier'] = [];

        if ($page == 'purchase_data' || $export) {
            $this->getPurchaseDetails($data, $formDate, $toDate, $company);
        }

        //Sales Data
        if ($page == 'sales_data' || $export) {
            $this->getSalesDetails($data, $formDate, $toDate, $company);
        }

        //Get Expense Data
        if ($page == 'expense_data' || $export) {
            $this->getExpenseDetails($data, $formDate, $toDate, $company);
            $this->getExpenseByCustomer($data, $formDate, $toDate, $company);
            $this->getExpenseBySupplier($data, $formDate, $toDate, $company);
        }
        
        //Sales BY Customer Data
        if ($page == 'sales_by_customer' || $export) {
            $this->getSalesByCustomerDetails($data, $formDate, $toDate, $company);
        }

        //Purchase by Supplier Data
        if ($page == 'purchase_by_supplier' || $export) {
            $this->getPurchaseBySupplierDetails($data, $formDate, $toDate, $company);
        }

        //sales by products
        if ($page == 'sales_by_products' || $export) {
            $this->getSalesByProductsDetails($data, $formDate, $toDate, $company);
        }

        //sales by products
        if ($page == 'purchase_by_products' || $export) {
            $this->getPurchaseByProductsDetails($data, $formDate, $toDate, $company);
        }

        //Customer Balance
        if ($page == 'customer_balance' || $export) {
            $this->getCustomerOutstandingData($data, $formDate, $toDate, $company);
        }

        //Supplier Balance
        if ($page == 'supplier_balance' || $export) {
            $this->getSupplierOutstandingData($data, $formDate, $toDate, $company);
        }

        //sales by shop
        if ($page == 'sales_by_shop' || $export) {
            $this->getSalesByShop($data, $formDate, $toDate, $company);
        }

        //sales by rep
        if ($page == 'sales_by_rep' || $export) {
            $this->getSalesByRep($data, $formDate, $toDate, $company);
        }
        return $data;
    }

    public function getPurchaseDetails(&$data, $formDate, $toDate, $company)
    {
        // Purchase Order Data
        $orders = PurchaseOrder::whereBetween('order_date', [$formDate, $toDate])->where('company_id', $company)->with(['bills', 'payments'])->get();
        $billed = $orders->pluck('bills')->collapse()->sum('amount');
        $purchase = $orders->sum('total');
        $made = $orders->pluck('payments')->collapse()->sum('payment');
        $data['purchase_data']['order_data']['count'] = $orders->count();
        $data['purchase_data']['order_data']['purchase'] = $purchase;
        $data['purchase_data']['order_data']['billed'] = $billed;
        $data['purchase_data']['order_data']['made'] = $made;
        $data['purchase_data']['order_data']['balance'] = $purchase - $made;

        //Purchase Payment Data
        $payments = BillPayment::whereBetween('payment_date', [$formDate, $toDate])->where('company_id', $company)->get();
        $cash = $payments->where('payment_mode', 'Cash')->sum('payment');
        $cheque = $payments->where('payment_mode', 'Cheque')->sum('payment');
        $directDeposit = $payments->where('payment_mode', 'Direct Deposit')->sum('payment');
        $creditCard = $payments->where('payment_mode', 'Credit Card')->sum('payment');
        $data['purchase_data']['payment_data']['cash'] = $cash;
        $data['purchase_data']['payment_data']['cheque'] = $cheque;
        $data['purchase_data']['payment_data']['direct_deposit'] = $directDeposit;
        $data['purchase_data']['payment_data']['credit_card'] = $creditCard;
        $data['purchase_data']['payment_data']['total'] = ($cash + $cheque + $directDeposit + $creditCard);

        //Credit Summary
        $credits = SupplierCredit::whereBetween('date', [$formDate, $toDate])->where('company_id', $company)->with(['refunds', 'payments'])->get();
        $creditsAmount = $credits->sum('amount');
        $refunded = $credits->pluck('refunds')->collapse()->sum('amount');
        $credited = $credits->pluck('payments')->collapse()->sum('amount');
        $data['purchase_data']['credit_data']['credits'] = $creditsAmount;
        $data['purchase_data']['credit_data']['refunded'] = $refunded;
        $data['purchase_data']['credit_data']['credited'] = $credited;
        $data['purchase_data']['credit_data']['total'] = $creditsAmount - ($credited + $refunded);
    }


    public function getSalesDetails(&$data, $formDate, $toDate, $company)
    {
        $orders = SalesOrder::whereBetween('order_date', [$formDate, $toDate])->where('company_id', $company)->with(['invoices', 'payments'])->get();
        $invoices = $orders->pluck('invoices')->collapse()->sum('amount');
        $purchase = $orders->sum('total');
        $made = $orders->pluck('payments')->collapse()->sum('payment');
        $data['sales_data']['order_data']['count'] = $orders->count();
        $data['sales_data']['order_data']['purchase'] = $purchase;
        $data['sales_data']['order_data']['invoices'] = $invoices;
        $data['sales_data']['order_data']['made'] = $made;
        $data['sales_data']['order_data']['balance'] = $purchase - $made;

        //Sales Payment Data
        $payments = InvoicePayment::whereBetween('payment_date', [$formDate, $toDate])->where('company_id', $company)->get();
        $cash = $payments->where('payment_mode', 'Cash')->sum('payment');
        $cheque = $payments->where('payment_mode', 'Cheque')->sum('payment');
        $directDeposit = $payments->where('payment_mode', 'Direct Deposit')->sum('payment');
        $creditCard = $payments->where('payment_mode', 'Credit Card')->sum('payment');
        $data['sales_data']['payment_data']['cash'] = $cash;
        $data['sales_data']['payment_data']['cheque'] = $cheque;
        $data['sales_data']['payment_data']['direct_deposit'] = $directDeposit;
        $data['sales_data']['payment_data']['credit_card'] = $creditCard;
        $data['sales_data']['payment_data']['total'] = ($cash + $cheque + $directDeposit + $creditCard);

        //Credit Summary
        $credits = CustomerCredit::whereBetween('date', [$formDate, $toDate])->where('company_id', $company)->with(['refunds', 'payments'])->get();
        $creditsAmount = $credits->sum('amount');
        $refunded = $credits->pluck('refunds')->collapse()->sum('amount');
        $credited = $credits->pluck('payments')->collapse()->sum('amount');
        $data['sales_data']['credit_data']['credits'] = $creditsAmount;
        $data['sales_data']['credit_data']['refunded'] = $refunded;
        $data['sales_data']['credit_data']['credited'] = $credited;
        $data['sales_data']['credit_data']['total'] = $creditsAmount - ($credited + $refunded);

        //Estimate Data
        $estimates = Estimate::whereBetween('estimate_date', [$formDate, $toDate])->where('company_id', $company)->get();
        $data['sales_data']['estimate_date']['estimate'] = [];
        $data['sales_data']['estimate_date']['estimate']['count'] = $estimates->count();
        $data['sales_data']['estimate_date']['estimate']['converted'] = $estimates->where('status', 'Ordered')->count();
        $data['sales_data']['estimate_date']['estimate']['total'] = $estimates->sum('total');

        //inquiry Data
        $inquiries = SalesInquiry::whereBetween('inquiry_date', [$formDate, $toDate])->where('company_id', $company)->get();
        $data['sales_data']['estimate_date']['inquiry'] = [];
        $data['sales_data']['estimate_date']['inquiry']['ordered'] = $inquiries->where('status', 'Converted to Order')->count();
        $data['sales_data']['estimate_date']['inquiry']['estimate'] = $inquiries->where('status', 'Converted to Estimate')->count();
    }

    /**
     * @param $data
     * @param $formDate
     * @param $toDate
     * @param $company
     */
    public function getExpenseDetails(&$data, $formDate, $toDate, $company)
    {
        //Receipts Summary
        $data['expense_data']['payment'] = [];
        $receipts = Expense::whereBetween('expense_date', [$formDate, $toDate])->where('company_id', $company)->get();
        $data['expense_data']['receipts_data']['count'] = $receipts->count();
        $data['expense_data']['receipts_data']['unreported'] = $receipts->where('status', 'Unreported')->sum('amount');
        $data['expense_data']['receipts_data']['unsubmitted'] = $receipts->where('status', 'Unsubmitted')->sum('amount');
        $data['expense_data']['receipts_data']['submitted'] = $receipts->where('status', 'Submitted')->sum('amount');
        $data['expense_data']['receipts_data']['approved'] = $receipts->where('status', 'Approved')->sum('amount');
        $data['expense_data']['receipts_data']['rejected'] = $receipts->where('status', 'Rejected')->sum('amount');
        $data['expense_data']['receipts_data']['reimbursed'] = $receipts->where('status', 'Reimbursed')->sum('amount');
        $data['expense_data']['receipts_data']['total'] = $receipts->sum('amount');

        //Payment Summary
        $data['expense_data']['payment']['cash'] = $receipts->where('payment_mode', 'Cash')->sum('amount');
        $data['expense_data']['payment']['cheque'] = $receipts->where('payment_mode', 'Cheque')->sum('amount');
        $data['expense_data']['payment']['deposit'] = $receipts->where('payment_mode', 'Direct Deposit')->sum('amount');
        $data['expense_data']['payment']['credit_card'] = $receipts->where('payment_mode', 'Credit Card')->sum('amount');

        //Report Summary
        $reports = ExpenseReport::where(function ($q) use ($formDate, $toDate) {
            $q->where('report_from', '>=', $formDate)
                ->orwhere('report_to', '<=', $toDate);
        })->where('company_id', $company)->get();
        $data['expense_data']['reports_data']['count'] = $reports->count();
        $data['expense_data']['reports_data']['drafted'] = $reports->where('status', 'Drafted')->sum('amount');
        $data['expense_data']['reports_data']['submitted'] = $reports->where('status', 'Submitted')->sum('amount');
        $data['expense_data']['reports_data']['approved'] = $reports->where('status', 'Approved')->sum('amount');
        $data['expense_data']['reports_data']['rejected'] = $reports->where('status', 'Rejected')->sum('amount');
        $data['expense_data']['reports_data']['partially_reimbursed'] = $reports->where('status', 'Partially Reimbursed')->sum('amount');
        $data['expense_data']['reports_data']['reimbursed'] = $reports->where('status', 'Reimbursed')->sum('amount');
        $data['expense_data']['reports_data']['total'] = $reports->sum('amount');
    }

    /**
     * @param $data
     * @param $formDate
     * @param $toDate
     * @param $company
     */
    public function getSalesByCustomerDetails(&$data, $formDate, $toDate, $company)
    {
//        $customers = Customer::where('company_id', $company)->with(['orders' => function ($q) use ($formDate, $toDate) {
//            $q->whereBetween('order_date', [$formDate, $toDate]);
//        }])->whereHas('orders', function ($q) use ($formDate, $toDate) {
//            $q->whereBetween('order_date', [$formDate, $toDate]);
//        })->get();

        $reps = Rep::with(['salesOrders' => function ($q) use ($formDate, $toDate, $company) {
            $q->where('company_id', $company)->whereBetween('order_date', [$formDate, $toDate])->with('customer');
        }])->whereHas('salesOrders', function ($q) use ($formDate, $toDate, $company) {
            $q->where('company_id', $company)->whereBetween('order_date', [$formDate, $toDate]);
        })->get();
        $reps = $reps->pluck('salesOrders', 'id');
        $reps = $reps->map(function ($customer, $key) {
            $rep = Rep::find($key)->name;
            $customer = $customer->groupBy('customer_id');
            $customer = $customer->map(function ($item, $key) {
                $customer = Customer::find($key)->display_name ?? '';
                return ['name' => $customer, 'total' => $item->sum('total')];
            });
            return ['customers' => $customer, 'name' => $rep, 'total' => $customer->sum('total')];
        });
        $data['sales_by_customer']['data'] = $reps->toArray();
        $data['sales_by_customer']['order_total'] = array_sum(array_pluck(array_collapse(array_pluck($reps->toArray(), 'customers')), 'total'));
    }

    /**
     * @param $data
     * @param $formDate
     * @param $toDate
     * @param $company
     */
    public function getPurchaseBySupplierDetails(&$data, $formDate, $toDate, $company)
    {
        $suppliers = Supplier::where('company_id', $company)->with(['orders' => function ($q) use ($formDate, $toDate) {
            $q->whereBetween('order_date', [$formDate, $toDate]);
        }])->whereHas('orders', function ($q) use ($formDate, $toDate) {
            $q->whereBetween('order_date', [$formDate, $toDate]);
        })->get();
        $data['purchase_by_supplier']['data'] = $suppliers->toArray();
        $data['purchase_by_supplier']['order_total'] = $suppliers->pluck('orders')->collapse()->sum('total');
    }

    /**
     * @param $data
     * @param $formDate
     * @param $toDate
     * @param $company
     */
    public function getSalesByProductsDetails(&$data, $formDate, $toDate, $company)
    {
        $products = Product::where('type', 'Finished Good')->with(['salesOrders' => function ($q) use ($formDate, $toDate) {
            $q->whereBetween('order_date', [$formDate, $toDate]);
        }])->whereHas('salesOrders', function ($q) use ($formDate, $toDate, $company) {
            $q->where('company_id', $company)->whereBetween('order_date', [$formDate, $toDate]);
        })->get();
        $data['sales_by_products']['data'] = $products->toArray();
        $data['sales_by_products']['order_total'] = $products->pluck('salesOrders')->collapse()->sum('total');
    }

    /**
     * @param $data
     * @param $formDate
     * @param $toDate
     * @param $company
     */
    public function getPurchaseByProductsDetails(&$data, $formDate, $toDate, $company)
    {
        $products = Product::where('type', 'Raw Material')->with(['purchaseOrders' => function ($q) use ($formDate, $toDate) {
            $q->whereBetween('order_date', [$formDate, $toDate]);
        }])->whereHas('purchaseOrders', function ($q) use ($formDate, $toDate, $company) {
            $q->where('company_id', $company)->whereBetween('order_date', [$formDate, $toDate]);
        })->get();
        $data['purchase_by_products']['data'] = $products->toArray();
        $data['purchase_by_products']['order_total'] = $products->pluck('purchaseOrders')->collapse()->sum('total');
    }

    /**
     * @param $data
     * @param $formDate
     * @param $toDate
     * @param $company
     */
    public function getCustomerOutstandingData(&$data, $formDate, $toDate, $company)
    {
        $data['customer_balance'] = [];
        $customers = Customer::where('company_id', $company)->with(['orders' => function ($q) use ($formDate, $toDate, $company) {
            $q->whereBetween('order_date', [$formDate, $toDate]);
        }, 'invoices' => function ($q) use ($formDate, $toDate, $company) {
            $q->whereBetween('invoice_date', [$formDate, $toDate]);
        }, 'payments' => function ($q) use ($formDate, $toDate, $company) {
            $q->whereBetween('payment_date', [$formDate, $toDate]);
        }])->where(function ($q) use ($formDate, $toDate, $company) {
            $q->whereHas('orders', function ($q) use ($formDate, $toDate, $company) {
                $q->whereBetween('order_date', [$formDate, $toDate]);
            })->whereHas('invoices', function ($q) use ($formDate, $toDate, $company) {
                $q->whereBetween('invoice_date', [$formDate, $toDate]);
            })->whereHas('payments', function ($q) use ($formDate, $toDate, $company) {
                $q->whereBetween('payment_date', [$formDate, $toDate]);
            });
        })->get();
        $customers = $customers->reject(function ($customer) {
            $orderTotal = $customer->orders->sum('total');
            $paymentTotal = $customer->payments->sum('payment');
            return $orderTotal == $paymentTotal;
        });
        $orderTotal = $customers->pluck('orders')->collapse()->sum('total');
        $paymentTotal = $customers->pluck('payments')->collapse()->sum('payment');
        $data['customer_balance']['data'] = array_values($customers->toArray());
        $data['customer_balance']['balance'] = ($orderTotal - $paymentTotal);
    }

    /**
     * @param $data
     * @param $formDate
     * @param $toDate
     * @param $company
     */
    public function getSupplierOutstandingData(&$data, $formDate, $toDate, $company)
    {
        $data['supplier_balance'] = [];
        $suppliers = Supplier::where('company_id', $company)->with(['orders' => function ($q) use ($formDate, $toDate, $company) {
            $q->whereBetween('order_date', [$formDate, $toDate]);
        }, 'bills' => function ($q) use ($formDate, $toDate, $company) {
            $q->whereBetween('bill_date', [$formDate, $toDate]);
        }, 'payments' => function ($q) use ($formDate, $toDate, $company) {
            $q->whereBetween('payment_date', [$formDate, $toDate]);
        }])->where(function ($q) use ($formDate, $toDate, $company) {
            $q->whereHas('orders', function ($q) use ($formDate, $toDate, $company) {
                $q->whereBetween('order_date', [$formDate, $toDate]);
            })->whereHas('bills', function ($q) use ($formDate, $toDate, $company) {
                $q->whereBetween('bill_date', [$formDate, $toDate]);
            })->whereHas('payments', function ($q) use ($formDate, $toDate, $company) {
                $q->whereBetween('payment_date', [$formDate, $toDate]);
            });
        })->get();
        $suppliers = $suppliers->reject(function ($customer) {
            $orderTotal = $customer->orders->sum('total');
            $paymentTotal = $customer->payments->sum('payment');
            return $orderTotal == $paymentTotal;
        });
        $orderTotal = $suppliers->pluck('orders')->collapse()->sum('total');
        $paymentTotal = $suppliers->pluck('payments')->collapse()->sum('payment');
        $data['supplier_balance']['data'] = array_values($suppliers->toArray());
        $data['supplier_balance']['balance'] = ($orderTotal - $paymentTotal);
    }

    /**
     * @param $data
     * @param $formDate
     * @param $toDate
     * @param $company
     */
    public function getExpenseByCustomer(&$data, $formDate, $toDate, $company)
    {
        $customers = Customer::with(['expenses' => function ($q) use ($formDate, $toDate) {
            $q->whereBetween('expense_date', [$formDate, $toDate]);
        }])->where('company_id', $company)->whereHas('expenses', function ($q) use ($formDate, $toDate) {
            $q->whereIn('status', ['Approved', 'Reimbursed'])->whereBetween('expense_date', [$formDate, $toDate]);
        })->get();

        $data['expense_data']['customer']['data'] = $customers->toArray();
        $data['expense_data']['customer']['total'] = $customers->pluck('expenses')->collapse()->sum('amount');

    }

    /**
     * @param $data
     * @param $formDate
     * @param $toDate
     * @param $company
     */
    public function getExpenseBySupplier(&$data, $formDate, $toDate, $company)
    {
        $suppliers = Supplier::with(['expenses' => function ($q) use ($formDate, $toDate) {
            $q->whereBetween('expense_date', [$formDate, $toDate]);
        }])->where('company_id', $company)->whereHas('expenses', function ($q) use ($formDate, $toDate) {
            $q->whereIn('status', ['Approved', 'Reimbursed'])->whereBetween('expense_date', [$formDate, $toDate]);
        })->get();

        $data['expense_data']['supplier']['data'] = $suppliers->toArray();
        $data['expense_data']['supplier']['total'] = $suppliers->pluck('expenses')->collapse()->sum('amount');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function todayCollection()
    {
        $now = now()->toDateString();
        $orders = SalesOrder::whereDate('order_date', $now)->with(['company', 'preparedBy', 'salesLocation'])->get()->groupBy('company_id');
        $invoices = Invoice::whereDate('invoice_date', $now)
            ->whereHas('order', function ($q) use ($now) {
                $q->whereDate('order_date', $now);
            })
            ->with('company', 'preparedBy', 'salesLocation')->get()->groupBy('company_id');
        $payments = InvoicePayment::whereDate('payment_date', $now)
            ->whereHas('order', function ($q) use ($now) {
                $q->whereDate('order_date', $now);
            })
            ->with('company', 'preparedBy', 'salesLocation')->get()->groupBy('company_id');
        return $this->mapDashboardSalesData($orders, $invoices, $payments);
    }

    /**
     * @param $orders
     * @param $invoices
     * @param $payments
     * @return \Illuminate\Support\Collection
     */
    public function mapDashboardSalesData($orders, $invoices, $payments)
    {
        $data = collect([]);

        $companies = Company::whereIn('id', userCompanyIds(loggedUser()))
            ->where('is_active', 'Yes')->get();
        foreach ($companies as $company) {
            $invoiceItems = $invoices->get($company->id);
            $PaymentItems = $payments->get($company->id);
            $orderItems = $orders->get($company->id);

            $orderItems = $orderItems ? $orderItems : collect();
            $PaymentItems = $PaymentItems ? $PaymentItems : collect();
            $invoiceItems = $invoiceItems ? $invoiceItems : collect();

            $totalSales = $orderItems->sum('total');
            $totalPaid = $PaymentItems->sum('payment');
            $tempArray = [
                'company_id' => $company->id ?? '',
                'company_name' => $company->name ?? '',
                'total_sales' => number_format($totalSales, 2),
                'total_invoiced' => number_format($invoiceItems->sum('amount'), 2),
                'total_paid' => number_format($totalPaid, 2),
                'total_cash' => number_format($PaymentItems->where('payment_mode', 'Cash')->sum('payment'), 2),
                'total_cheque' => number_format($PaymentItems->where('payment_mode', 'Cheque')->sum('payment'), 2),
                'total_deposit' => number_format($PaymentItems->where('payment_mode', 'Direct Deposit')->sum('payment'), 2),
                'total_card' => number_format($PaymentItems->where('payment_mode', 'Credit Card')->sum('payment'), 2),
                'total_balance' => number_format($totalSales - $totalPaid, 2),
                'users' => []
            ];
            $tempArray['users'] = $this->mapDashboardSalesPreparedBy($orderItems, $invoiceItems, $PaymentItems);
            $tempArray['shops'] = $this->mapDashboardSalesShops($orderItems, $invoiceItems, $PaymentItems);
            $data->push($tempArray);
        }
        return $data;
    }

    /**
     * @param $orders
     * @param $invoices
     * @param $payments
     * @return static
     */
    protected function mapDashboardSalesPreparedBy($orders, $invoices, $payments)
    {
        $data = collect([]);
        $orderItemsByPrepared = $orders->groupBy('prepared_by');
        $PaymentItemsByPrepared = $payments->groupBy('prepared_by');
        $invoiceItems = $invoices->groupBy('prepared_by');
        foreach ($orderItemsByPrepared as $key => $order) {
            $preparedBy = $order->first()->preparedBy;
            if ($preparedBy && !$preparedBy->isRepUser()) {
                continue;
            }
            $data->push([
                'staff_id' => $preparedBy ? $preparedBy->staffs()->first()->id : null,
                'user_id' => $key,
                'user_name' => $preparedBy ? $preparedBy->name : null,
                'total_sales' => $order->sum('total'),
                'total_invoiced' => 0.00,
                'total_paid' => 0.00,
                'total_cash' => 0.00,
                'total_cheque' => 0.00,
                'total_deposit' => 0.00,
                'total_card' => 0.00,
                'total_balance' => 0.00,
            ]);
        }
        foreach ($invoiceItems as $key => $invoice) {
            $preparedBy = $invoice->first()->preparedBy;
            if ($preparedBy && !$preparedBy->isRepUser()) {
                continue;
            }
            $existingRecord = $data->firstWhere('user_id', $key);
            $invoicedAmount = $invoice->sum('amount');
            if ($existingRecord) {
                $data = $this->updateDashboardSalesData($data, 'total_invoiced', $invoicedAmount, $key);
            } else {
                $data->push([
                    'user_id' => $key,
                    'user_name' => $preparedBy ? $preparedBy->name : null,
                    'total_sales' => 0.00,
                    'total_invoiced' => $invoicedAmount,
                    'total_paid' => 0.00,
                    'total_cash' => 0.00,
                    'total_cheque' => 0.00,
                    'total_deposit' => 0.00,
                    'total_card' => 0.00,
                    'total_balance' => 0.00,
                ]);
            }
        }
        foreach ($PaymentItemsByPrepared as $key => $payment) {
            $preparedBy = $payment->first()->preparedBy;
            if ($preparedBy && !$preparedBy->isRepUser()) {
                continue;
            }
            $existingRecord = $data->firstWhere('user_id', $key);
            $paidAmount = $payment->sum('payment');
            $totalCash = $payment->where('payment_mode', 'Cash')->sum('payment');
            $totalCheque = $payment->where('payment_mode', 'Cheque')->sum('payment');
            $totalDeposit = $payment->where('payment_mode', 'Direct Deposit')->sum('payment');
            $totalCard = $payment->where('payment_mode', 'Credit Card')->sum('payment');
            if ($existingRecord) {
                $data = $this->updateDashboardSalesData($data, 'total_paid', $paidAmount, $key);
                $data = $this->updateDashboardSalesData($data, 'total_cash', $totalCash, $key);
                $data = $this->updateDashboardSalesData($data, 'total_cheque', $totalCheque, $key);
                $data = $this->updateDashboardSalesData($data, 'total_deposit', $totalDeposit, $key);
                $data = $this->updateDashboardSalesData($data, 'total_card', $totalCard, $key);
            } else {
                $data->push([
                    'user_id' => $key,
                    'user_name' => $preparedBy ? $preparedBy->name : null,
                    'total_sales' => 0.00,
                    'total_invoiced' => 0.00,
                    'total_paid' => $paidAmount,
                    'total_cash' => $totalCash,
                    'total_cheque' => $totalCheque,
                    'total_deposit' => $totalDeposit,
                    'total_card' => $totalCard,
                    'total_balance' => 0.00,
                ]);
            }
        }
        return $data->map(function ($item) {
            $item['total_balance'] = ($item['total_sales'] - $item['total_paid']);
            return $item;
        });
    }


    protected function mapDashboardSalesShops($orders, $invoices, $payments)
    {
        $data = collect([]);
        $orderItemsByShops = $orders->groupBy('sales_location_id');
        $PaymentItemsByShops = $payments->groupBy('sales_location_id');
        $invoiceItemsByShops = $invoices->groupBy('sales_location_id');
        foreach ($orderItemsByShops as $key => $order) {
            $location = $order->first()->salesLocation;
            if (!$location || ($location && $location->type == 'Sales Van') || ($location && $location->type == 'Others')) {
                continue;
            }
            $data->push([
                'shop_id' => $key,
                'shop_name' => $location ? $location->name : '',
                'total_sales' => $order->sum('total'),
                'total_invoiced' => 0.00,
                'total_paid' => 0.00,
                'total_cash' => 0.00,
                'total_cheque' => 0.00,
                'total_deposit' => 0.00,
                'total_card' => 0.00,
                'total_balance' => 0.00,
            ]);
        }
        foreach ($invoiceItemsByShops as $key => $invoice) {
            $location = $invoice->first()->salesLocation;
            if (!$location || ($location && $location->type == 'Sales Van') || ($location && $location->type == 'Others')) {
                continue;
            }
            $existingRecord = $data->firstWhere('shop_id', $key);
            $invoicedAmount = $invoice->sum('amount');
            if ($existingRecord) {
                $data = $this->updateDashboardSalesShopData($data, 'total_invoiced', $invoicedAmount, $key);
            } else {
                $data->push([
                    'shop_id' => $key,
                    'shop_name' => $location ? $location->name : null,
                    'total_sales' => 0.00,
                    'total_invoiced' => $invoicedAmount,
                    'total_paid' => 0.00,
                    'total_cash' => 0.00,
                    'total_cheque' => 0.00,
                    'total_deposit' => 0.00,
                    'total_card' => 0.00,
                    'total_balance' => 0.00,
                ]);
            }
        }
        foreach ($PaymentItemsByShops as $key => $payment) {
            $location = $payment->first()->salesLocation;
            if (!$location || ($location && $location->type == 'Sales Van') || ($location && $location->type == 'Others')) {
                continue;
            }
            $existingRecord = $data->firstWhere('shop_id', $key);
            $paidAmount = $payment->sum('payment');
            $totalCash = $payment->where('payment_mode', 'Cash')->sum('payment');
            $totalCheque = $payment->where('payment_mode', 'Cheque')->sum('payment');
            $totalDeposit = $payment->where('payment_mode', 'Direct Deposit')->sum('payment');
            $totalCard = $payment->where('payment_mode', 'Credit Card')->sum('payment');
            if ($existingRecord) {
                $data = $this->updateDashboardSalesShopData($data, 'total_paid', $paidAmount, $key);
                $data = $this->updateDashboardSalesShopData($data, 'total_cash', $totalCash, $key);
                $data = $this->updateDashboardSalesShopData($data, 'total_cheque', $totalCheque, $key);
                $data = $this->updateDashboardSalesShopData($data, 'total_deposit', $totalDeposit, $key);
                $data = $this->updateDashboardSalesShopData($data, 'total_card', $totalCard, $key);
            } else {
                $data->push([
                    'shop_id' => $key,
                    'shop_name' => $location ? $location->name : null,
                    'total_sales' => 0.00,
                    'total_invoiced' => 0.00,
                    'total_paid' => $paidAmount,
                    'total_cash' => $totalCash,
                    'total_cheque' => $totalCheque,
                    'total_deposit' => $totalDeposit,
                    'total_card' => $totalCard,
                    'total_balance' => 0.00,
                ]);
            }
        }
        return $data->map(function ($item) {
            $item['total_balance'] = ($item['total_sales'] - $item['total_paid']);
            return $item;
        });
    }

    /**
     * @param $collection
     * @param $key
     * @param $value
     * @param $userId
     * @return mixed
     */
    protected function updateDashboardSalesData($collection, $key, $value, $userId)
    {
        return $collection->map(function ($item) use ($key, $value, $userId) {
            if (isset($item['user_id']) && isset($item[$key]) && $item['user_id'] == $userId) {
                $item[$key] += $value;
            }
            return $item;
        });
    }

    protected function updateDashboardSalesShopData($collection, $key, $value, $shopId)
    {
        return $collection->map(function ($item) use ($key, $value, $shopId) {
            if (isset($item['shop_id']) && isset($item[$key]) && $item['shop_id'] == $shopId) {
                $item[$key] += $value;
            }
            return $item;
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function oldCollection()
    {
        $collection = collect([]);
        $now = carbon()->now()->toDateString();
        $companies = Company::whereIn('id', userCompanyIds(loggedUser()))
            ->where('is_active', 'Yes')->get();
        $payments = InvoicePayment::where('payment_date', $now)
            ->whereHas('order', function ($q) use ($now) {
                $q->whereDate('order_date', '<', $now);
            })
            ->with(['company', 'preparedBy', 'salesLocation'])->get();
        $payments = $payments->filter(function ($value, $key) use ($now) {
            return $value->invoice->invoice_date < $now;
        })->groupBy('company_id');
        foreach ($companies as $company) {
            $paymentItems = $payments->get($company->id);
            $paymentItems = $paymentItems ? $paymentItems : collect([]);
            $collection->push([
                'company_name' => $company->name,
                'company_id' => $company->id,
                'total_paid' => $paymentItems->sum('payment'),
                'total_cash' => $paymentItems->where('payment_mode', 'Cash')->sum('payment'),
                'total_cheque' => $paymentItems->where('payment_mode', 'Cheque')->sum('payment'),
                'total_deposit' => $paymentItems->where('payment_mode', 'Direct Deposit')->sum('payment'),
                'total_card' => $paymentItems->where('payment_mode', 'Credit Card')->sum('payment'),
                'users' => $this->oldCollectionByPrepared($paymentItems),
                'shops' => $this->oldCollectionByShops($paymentItems)
            ]);
        }
        return $collection;
    }

    /**
     * @param $paymentItems
     * @return \Illuminate\Support\Collection
     */
    protected function oldCollectionByPrepared($paymentItems)
    {
        $data = collect();
        $paymentItems = $paymentItems->groupBy('prepared_by');
        foreach ($paymentItems as $key => $payments) {
            $preparedBy = $payments->first()->preparedBy;
            if ($preparedBy && !$preparedBy->isRepUser()) {
                continue;
            }
            $data->push([
                'user_name' => $preparedBy ? $preparedBy->name : null,
                'user_id' => $preparedBy ? $preparedBy->id : null,
                'total_paid' => $payments->sum('payment'),
                'total_cash' => $payments->where('payment_mode', 'Cash')->sum('payment'),
                'total_cheque' => $payments->where('payment_mode', 'Cheque')->sum('payment'),
                'total_deposit' => $payments->where('payment_mode', 'Direct Deposit')->sum('payment'),
                'total_card' => $payments->where('payment_mode', 'Credit Card')->sum('payment'),
            ]);
        }
        return $data;
    }

    protected function oldCollectionByShops($paymentItems)
    {
        $data = collect();
        $paymentItems = $paymentItems->groupBy('sales_location_id');
        foreach ($paymentItems as $key => $payments) {
            $location = $payments->first()->salesLocation;
            if ($location && $location->type == 'Sales Van' || ($location && $location->type == 'Others')) {
                continue;
            }
            $data->push([
                'shop_name' => $location ? $location->name : null,
                'total_paid' => $payments->sum('payment'),
                'total_cash' => $payments->where('payment_mode', 'Cash')->sum('payment'),
                'total_cheque' => $payments->where('payment_mode', 'Cheque')->sum('payment'),
                'total_deposit' => $payments->where('payment_mode', 'Direct Deposit')->sum('payment'),
                'total_card' => $payments->where('payment_mode', 'Credit Card')->sum('payment'),
            ]);
        }
        return $data;
    }

    /**
     * @param $data
     * @param $formDate
     * @param $toDate
     * @param $company
     */
    public function getSalesByRep(&$data, $formDate, $toDate, $company)
    {
        $reps = Rep::with(['salesOrders' => function ($q) use ($formDate, $toDate) {
            $q->whereBetween('order_date', [$formDate, $toDate]);
        }])->whereHas('salesOrders', function ($q) use ($formDate, $toDate, $company) {
            $q->where('company_id', $company)->whereBetween('order_date', [$formDate, $toDate]);
        })->get();
        $data['sales_by_rep']['data'] = $reps->toArray();
        $data['sales_by_rep']['order_total'] = $reps->pluck('salesOrders')->collapse()->sum('total');
    }

    /**
     * @param $data
     * @param $formDate
     * @param $toDate
     * @param $company
     */
    public function getSalesByShop(&$data, $formDate, $toDate, $company)
    {
        $locations = SalesLocation::where('type', 'Shop')->with(['orders' => function ($q) use ($formDate, $toDate) {
            $q->where('order_mode', 'Cash')->whereBetween('order_date', [$formDate, $toDate]);
        }])->whereHas('orders', function ($q) use ($formDate, $toDate, $company) {
            $q->where('order_mode', 'Cash')->where('company_id', $company)->whereBetween('order_date', [$formDate, $toDate]);
        })->get();
        $data['sales_by_shop']['data'] = $locations->toArray();
        $data['sales_by_shop']['order_total'] = $locations->pluck('orders')->collapse()->sum('total');
    }

    public function todayStaffCollection()
    {
        $now = now()->toDateString();
        $authId = auth()->id();
        return SalesOrder::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereDate('order_date', $now)
            ->where('prepared_by', $authId)
            ->with([
                'invoices' => function ($q) use ($now, $authId) {
                    $q->whereDate('invoice_date', $now)
                        ->where('prepared_by', $authId);
                },
                'payments' => function ($q) use ($now, $authId) {
                    $q->whereDate('payment_date', $now)
                        ->where('prepared_by', $authId);
                }
            ])
            ->orderBy('id', 'desc')->get();
    }

    /**
     * @return mixed
     */
    public function oldStaffCollection()
    {
        $now = now()->toDateString();
        $authId = auth()->id();
        return InvoicePayment::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereDate('payment_date', $now)
            ->where('prepared_by', $authId)
            ->whereHas('invoice', function ($q) use ($now, $authId) {
                $q->whereDate('invoice_date', '<', $now);
            })->with([
                'invoice', 'order'
            ])->get();
    }

    /**
     * @return mixed
     */
    public function visitStats()
    {
        $request = request();
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $company = $request->input('company');
        $route = $request->input('route');
        $reason = $request->input('reason');
        $allocations = DailySale::with(['customers' => function ($customer) use ($company, $route, $fromDate, $toDate, $reason) {
            $customer->with(['customer' => function ($query) use ($company, $route, $fromDate, $toDate, $reason) {
                $query->with(['orders' => function ($q) use ($company, $route, $fromDate, $toDate) {
                    $q->where('company_id', $company)->whereBetween('order_date', [$fromDate, $toDate]);
                }, 'invoices' => function ($q) use ($company, $route, $fromDate, $toDate) {
                    $q->where('company_id', $company)->whereBetween('invoice_date', [$fromDate, $toDate]);
                }, 'payments' => function ($q) use ($company, $route, $fromDate, $toDate) {
                    $q->where('company_id', $company)->whereBetween('payment_date', [$fromDate, $toDate]);
                }]);
            }, 'dailySale.rep']);
            if ($reason) {
                $customer->where('reason', 'LIKE', '%' . $reason . '%');
            }
        }])->where(function ($q) use ($company, $route, $fromDate, $toDate) {
            $q->where('company_id', $company)
                ->where('route_id', $route)->where('from_date', '>=', $fromDate)
                ->where('to_date', '<=', $toDate);
        });

        $customers = $allocations->get()->pluck('customers')->collapse();
        $customers->transform(function ($customer) {
            if(getOrderDetail($customer->customer->id, $customer->daily_sale_id)){
                $customer->visitedAt = date("F j, Y, g:i:s a", strtotime(getOrderDetail($customer->customer->id, $customer->daily_sale_id)->created_at));
                $customer->reason = 'Order Created';
                $customer->gps_lat = getOrderDetail($customer->customer->id, $customer->daily_sale_id)->gps_lat;
                $customer->gps_long = getOrderDetail($customer->customer->id, $customer->daily_sale_id)->gps_long;
            }else if(getPaymentDetail($customer->customer->id, $customer->daily_sale_id)){
                $customer->visitedAt = date("F j, Y, g:i:s a", strtotime(getPaymentDetail($customer->customer->id, $customer->daily_sale_id)->created_at));
                $customer->reason = 'Payment Collected';
                $customer->gps_lat = '';
                $customer->gps_long = '';
            }else{
                $customer->visitedAt = date("F j, Y, g:i:s a", strtotime($customer->updated_at));
                $customer->gps_lat = '';
                $customer->gps_long = '';
            }

            if($customer->reason == 'Time is not enough to cover'
                || $customer->reason == 'Road Closed, Unable to Reach Customer' || $customer->reason == null){
                $customer->isVisited = 'No';
            }else{
                $customer->isVisited = 'Yes';
            }

            return $customer;
        });
        $data = [];
        $data['customers'] = $customers->toArray();
        $data['total'] = [];
        $realCustomers = $customers->pluck('customer');
        $data['total']['ordersTotal'] = $realCustomers->pluck('orders')->collapse()->sum('total');
        $data['total']['invoicesTotal'] = $realCustomers->pluck('invoices')->collapse()->sum('amount');
        $data['total']['paymentsTotal'] = $realCustomers->pluck('payments')->collapse()->sum('payment');
        $data['total']['balance'] = $data['total']['invoicesTotal'] - $data['total']['paymentsTotal'];

        return $data;
    }

    public function getRepStatsData()
    {
        $request = request();
        $fromDate = $request->input('fromDate') ?? carbon()->toDateString();
        $toDate = $request->input('toDate') ?? carbon()->toDateString();
        $route = $request->input('route');

        /** get allocation count by route */
        $allocations = DailySale::where('route_id', $route)->whereIn('status', ['Progress', 'Completed'])->count();

        $customers = Customer::where('route_id', $route)->get();
        $customers->each(function (Customer $customer) use ($fromDate, $toDate) {
            $customer->total_allocated = customerVisits($customer, $fromDate, $toDate)['allocated'];
            $customer->total_visited = customerVisits($customer, $fromDate, $toDate)['visited'];
            $customer->total_not_visited = customerVisits($customer, $fromDate, $toDate)['not_visited'];
        });

        $data['allocations'] = $allocations;
        $data['totalAllocated'] = $customers->sum('total_allocated');
        $data['totalVisited'] = $customers->sum('total_visited');
        $data['totalNotVisited'] = $customers->sum('total_not_visited');
        $data['customers'] = $customers->toArray();

        return $data;
    }


}