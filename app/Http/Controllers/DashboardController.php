<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Company;
use App\Customer;
use App\DailySaleCustomer;
use App\DailyStock;
use App\Invoice;
use App\Rep;
use App\Repositories\DashboardRepository;
use App\Repositories\DashboardStatsRepository;
use App\Repositories\Purchase\SalesRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PDF;

class DashboardController extends Controller
{
    protected $dashboard;
    protected $dashboardStats;
    protected $sales;

    public function __construct(DashboardRepository $dashboard, DashboardStatsRepository $stats, SalesRepository $sales)
    {
        $this->dashboard = $dashboard;
        $this->dashboardStats = $stats;
        $this->sales = $sales;
    }

    /**
     * Show the application dashboard.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isDirectorLevelStaff() || isAccountLevelStaff()) {
            $todaySales = $this->dashboard->todayCollection();
            $oldSales = $this->dashboard->oldCollection();
        } else {
            $todaySales = $this->dashboard->todayStaffCollection();
            $oldSales = $this->dashboard->oldStaffCollection();
        }
        $breadcrumb = [
            ['text' => ''],
        ];
        $companies = Company::whereIn('id', userCompanyIds(loggedUser()))->get();

        //$customers = collect($this->sales->topCustomersByPayment(10));
        //$reps = collect($this->sales->topRepsByPayment(10));
        $customers = collect();
        $reps = collect();
        $topThreeCustomer = array_values($customers->take(3)->toArray());
        $topThreeReps = array_values($reps->take(3)->toArray());
        $customers = $customers->splice(3);
        $reps = $reps->splice(3);
        return view('dashboard.index', compact('breadcrumb', 'companies', 'todaySales',
            'oldSales', 'topThreeCustomer', 'customers', 'topThreeReps', 'reps'));
        /*return view('dashboard.index', compact('breadcrumb'));*/
    }

    public function getDailyStockData()
    {
        $user = auth()->user();
        $staff = $user->staffs->first();
        if (!$staff) return response()->json([]);
        $store = $staff->stores->first();
        if (!$store) return response()->json([]);
        $dailyStocks = $store->dailyStocks()
            ->with(['rep', 'store', 'route', 'saleLocation', 'preAllocation', 'preparedBy'])
            ->orderBy('id', 'desc')
            ->limit(25)
            ->get();
        $dailyStocks = $dailyStocks->transform(function (DailyStock $dailyStock) {
            $preAllocation = $dailyStock->preAllocation;
            $toDate = $preAllocation ? $preAllocation->to_date : '';
            $dailyStock->pre_al_code = $preAllocation ? $preAllocation->code : '';
            $dailyStock->pre_al_date = $toDate ? carbon($toDate)->toDateString() : '';
            $dailyStock->date = $toDate ? carbon($toDate)->addDay()->toDateString() : carbon()->now()->toDateString();
            $dailyStock->no_of_products = $dailyStock->route->products->count();
            $dailyStock->prepared_staff = $dailyStock->preparedBy ? $dailyStock->preparedBy->name : 'None';
            return $dailyStock;
        });
        return response()->json($dailyStocks->toArray());
    }

    /**
     * @return Factory|View
     */
    public function companyStats()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Company Stats'],
        ];
        $companies = Company::whereIn('id', userCompanyIds(loggedUser()))->get();
        if (\request()->ajax()) {
            $data = $this->dashboard->getCompanyStatsData();
            return response()->json($data);
        }
        return view('dashboard.company-stats.index', compact('breadcrumb', 'companies'));
    }

    /**
     * @return mixed
     */
    public function companyStatsExport()
    {
        $data = $this->dashboard->getCompanyStatsData(true);
        $pdf = PDF::loadView('dashboard.company-stats.export', $data);
        return $pdf->download(env('APP_NAME') . ' Company Stats.pdf');
    }

    /**
     * @return Factory|View
     */
    public function companyStatsPrint()
    {
        $data = $this->dashboard->getCompanyStatsData(true);
        $purchase_data = array_get($data, 'purchase_data');
        $sales_data = array_get($data, 'sales_data');
        $expense_data = array_get($data, 'expense_data');
        $sales_by_customer = array_get($data, 'sales_by_customer');
        $purchase_by_supplier = array_get($data, 'purchase_by_supplier');
        $sales_by_products = array_get($data, 'sales_by_products');
        $purchase_by_products = array_get($data, 'purchase_by_products');
        $sales_by_shop = array_get($data, 'sales_by_shop');
        $sales_by_rep = array_get($data, 'sales_by_rep');
        $customer_balance = array_get($data, 'customer_balance');
        $supplier_balance = array_get($data, 'supplier_balance');
        $request = array_get($data, 'request');
        $param = request()->toArray();
        $breadcrumb = [
            ['text' => 'Home'],
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Company Stats', 'route' => 'company.stats'],
            ['text' => 'Print View'],
        ];
        return view('dashboard.company-stats.print',
            compact(
                'purchase_data', 'sales_data', 'expense_data', 'sales_by_customer', 'purchase_by_supplier', 'sales_by_products',
                'purchase_by_products', 'sales_by_shop', 'sales_by_rep', 'customer_balance', 'supplier_balance', 'param', 'breadcrumb', 'request'
            ));
    }

    /**
     * @return Factory|View
     */
    public function salesStats()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Sales Stats'],
        ];
        $companies = Company::whereIn('id', userCompanyIds(loggedUser()))->get();
        if (\request()->ajax()) {
            $data = $this->dashboard->getSalesStatData();
            return response()->json($data);
        }

        return view('dashboard.sales-stats.index', compact('breadcrumb', 'companies'));
    }

    /**
     * @return JsonResponse
     */
    public function getDueInvoices()
    {
        $invoices = Invoice::whereNotIn('status', ['Paid', 'Canceled', 'Refunded'])->whereDate('due_date', '<', carbon())->get();
        $data = [];
        getDueCollection($invoices, $data);
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function getDueBills()
    {
        $data = $this->getBillData();
        return response()->json($data);
    }

    /**
     * @param $dateRange
     * @return Factory|View
     */
    public function listDueInvoice($dateRange)
    {
        $breadcrumb = [
            ['text' => 'Dashboard'],
        ];
        return view('dashboard.due.invoice', compact('dateRange', 'breadcrumb'));
    }

    /**
     * @param Request $request
     * @param $modal
     * @param $dateRange
     * @return JsonResponse
     */
    public function listDueData(Request $request, $modal, $dateRange)
    {
        $data = $this->dashboard->getDueData($request, $modal, $dateRange);
        return response()->json($data);
    }

    /**
     * @return array
     */
    public function getBillData()
    {
        $invoices = Bill::whereNotIn('status', ['Paid', 'Canceled'])->whereDate('due_date', '<', carbon())->get();
        $data = [];
        getDueCollection($invoices, $data);
        return $data;
    }

    public function exportSalesStat()
    {
        $data = $this->dashboard->exportSalesStat();
        $pdf = PDF::loadView('dashboard.sales-stats.export', $data);
        return $pdf->download(env('APP_NAME') . ' Sales Stats.pdf');
    }

    public function printSalesStat()
    {
        $data = $this->dashboard->exportSalesStat();
        $orderData = array_get($data, 'orderData');
        $paymentsData = array_get($data, 'paymentsData');
        $masterData = array_get($data, 'masterData');
        $salesVisitData = array_get($data, 'salesVisitData');
        $salesExpensesData = array_get($data, 'salesExpensesData');
        $request = array_get($data, 'request');
        $param = request()->toArray();
        $breadcrumb = [
            ['text' => 'Home'],
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Sales Stats', 'route' => 'sales.stats'],
            ['text' => 'Print View'],
        ];
        return view('dashboard.sales-stats.print', compact('orderData',
            'paymentsData', 'masterData', 'request', 'breadcrumb', 'param', 'salesVisitData', 'salesExpensesData'));
    }

    /**
     * @return Factory|JsonResponse|View
     */
    public function visitStats()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Sales Visits'],
        ];
        $companies = Company::whereIn('id', userCompanyIds(loggedUser()))->get();
        $reasons = DailySaleCustomer::all()->pluck('reason', 'reason')->filter()->unique()->toArray();
        if (\request()->ajax()) {
            $data = $this->dashboard->visitStats();
            return response()->json($data);
        }
        return view('dashboard.visit-stats.index', compact('breadcrumb', 'companies', 'reasons'));

    }

    /**
     * @param null $range
     * @return JsonResponse
     */
    public function overDueData($range = null)
    {
        if (request()->input('model') == 'Invoice') {
            $data = $this->dashboardStats->overDueData($range);
        } else if (request()->input('model') == 'Bill') {
            $data = $this->dashboardStats->billOverDue($range);
        }
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function yearDataIncome()
    {
        $request = request();
        $data = $this->dashboardStats->yearDataIncome($request);
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function yearDataExpense()
    {
        $request = request();
        $data = $this->dashboardStats->yearDataExpense($request);
        return response()->json($data);
    }

    /**
     * @return JsonResponse
     */
    public function summaryData()
    {
        $request = request();
        $data = $this->dashboardStats->summaryData($request);
        return response()->json($data);
    }

    /**
     * @param Customer $customer
     * @return JsonResponse
     */
    public function customerPayments(Customer $customer)
    {
        $data = $this->dashboardStats->getPaymentDataForCustomer($customer);
        return response()->json($data);
    }

    /**
     * @param Rep $rep
     * @return JsonResponse
     */
    public function getPaymentDataForRep(Rep $rep)
    {
        $data = $this->dashboardStats->getPaymentDataForRep($rep);
        return response()->json($data);
    }

    public function repStats()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Rep Stats'],
        ];
        $reps = Rep::get();
        if (\request()->ajax()) {
            $data = $this->dashboard->getRepStatsData();
            return response()->json($data);
        }
        return view('dashboard.rep-stats.index', compact('breadcrumb', 'reps'));
    }

}
