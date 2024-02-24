<?php

namespace App\Repositories\General;

use App\{
    Bill, BillPayment, Customer, CustomerCredit, DailySale, Estimate, Expense, ExpenseReport, Invoice, InvoicePayment, PurchaseOrder, SalesInquiry, SalesOrder, Supplier, SupplierCredit
};
use App\Repositories\BaseRepository;
use App\Swagger\Models\Product;

/**
 * Class SearchRepository
 * @package App\Repositories\General
 */
class SearchRepository extends BaseRepository
{
    public $count = 0;

    public function searchAll($request)
    {
        $data = [];
        $query = $request->input('keyword');
        $data['purchase'] = $this->searchInPurchase($query);
        $data['sales'] = $this->searchInSales($query);
        $data['expenses'] = $this->searchInExpense($query);
        //$data['settings'] = $this->searchInSettings($query);
        $data['total'] = $this->count;
        return $data;
    }

    /**
     * @param $search
     * @return array
     */
    public function searchInPurchase($search)
    {
        $purchase = [];
        $orders = $this->getModelQuery($search, new PurchaseOrder())->get();
        $bills = $this->getModelQuery($search, new Bill())->get();
        $credits = $this->getModelQuery($search, new SupplierCredit())->get();
        $supplier = $this->getModelQuery($search, new Supplier())->get();
        $payments = $this->getModelQuery($search, new BillPayment())->with('bill')->get();


        $this->count = $this->count + $orders->count();
        $this->count = $this->count + $bills->count();
        $this->count = $this->count + $credits->count();
        $this->count = $this->count + $supplier->count();
        $this->count = $this->count + $payments->count();


        $purchase['orders'] = $orders->toArray();
        $purchase['bills'] = $bills->toArray();
        $purchase['credits'] = $credits->toArray();
        $purchase['suppliers'] = $supplier->toArray();
        $purchase['payments'] = $payments->toArray();

        return $purchase;
    }

    /**
     * @param $query
     * @return array
     */
    public function searchInSales($query)
    {
        $sales = [];
        $orders = $this->getModelQuery($query, new SalesOrder())->get();
        $invoices = $this->getModelQuery($query, new Invoice())->get();
        $credits = $this->getModelQuery($query, new CustomerCredit())->get();
        $customer = $this->getModelQuery($query, new Customer())->with('account')->get();
        $estimates = $this->getModelQuery($query, new Estimate())->get();
        $inquiries = $this->getModelQuery($query, new SalesInquiry())->get();
        $allocation = $this->getModelQuery($query, new DailySale())->get();
        $payments = $this->getModelQuery($query, new InvoicePayment())->with('invoice')->get();

        $this->count = $this->count + $orders->count();
        $this->count = $this->count + $invoices->count();
        $this->count = $this->count + $credits->count();
        $this->count = $this->count + $customer->count();
        $this->count = $this->count + $estimates->count();
        $this->count = $this->count + $inquiries->count();
        $this->count = $this->count + $payments->count();
        $this->count = $this->count + $allocation->count();

        $sales['orders'] = $orders->toArray();
        $sales['invoices'] = $invoices->toArray();
        $sales['credits'] = $credits->toArray();
        $sales['customers'] = $customer->toArray();
        $sales['estimates'] = $estimates->toArray();
        $sales['inquiries'] = $inquiries->toArray();
        $sales['allocations'] = $allocation->toArray();
        $sales['payments'] = $payments->toArray();

        return $sales;
    }

    /**
     * @param $query
     * @return array
     */
    public function searchInExpense($query)
    {
        $expenses = [];
        $receipts = $this->getModelQuery($query, new Expense())->get();
        $reports = $this->getModelQuery($query, new ExpenseReport())->get();

        $this->count = $this->count + $receipts->count();
        $this->count = $this->count + $reports->count();

        $expenses['receipts'] = $receipts->toArray();
        $expenses['reports'] = $reports->toArray();
        return $expenses;
    }

    /**
     * @param $query
     * @return array
     */
    public function searchInSettings($query)
    {
        $settings = [];
        $products = $this->getModelQuery($query, new Product())->get();

        $this->count = $this->count + $products->count();

        $settings['products'] = $settings->toArray();
        return $settings;
    }
}