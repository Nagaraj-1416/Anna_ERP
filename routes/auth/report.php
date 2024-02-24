<?php
Route::group(['prefix' => '/report', 'namespace' => 'Report'], function ($routes) {
    $routes->get('', 'ReportController@index')->name('report.index');

    /** purchase reports related routes */
    $routes->group(['prefix' => '/purchase'], function ($routes) {

        /** Purchases */
        $routes->get('purchase-by-supplier', 'PurchaseReportController@poBySup')->name('report.purchase.by.supplier');
        $routes->get('purchase-by-supplier/export', 'PurchaseReportController@poBySupExport')->name('report.purchase.by.supplier.export');

        $routes->get('purchase-by-product', 'PurchaseReportController@poByPro')->name('report.purchase.by.product');
        $routes->get('purchase-by-product/export', 'PurchaseReportController@poByProExport')->name('report.purchase.by.product.export');

        $routes->get('purchase-by-product-category', 'PurchaseReportController@poByProCat')->name('report.purchase.by.product.category');
        $routes->get('purchase-by-product-category/export', 'PurchaseReportController@poByProCatExport')->name('report.purchase.by.product.category.export');

        $routes->get('monthly-purchases', 'PurchaseReportController@monthlyPos')->name('report.monthly.purchases');
        $routes->get('monthly-purchases/export', 'PurchaseReportController@monthlyPosExport')->name('report.monthly.purchases.export');

        /** Payments Made */
        $routes->get('payments-made', 'PurchaseReportController@paysMade')->name('report.payments.made');
        $routes->get('payments-made/export', 'PurchaseReportController@paysMadeExport')->name('report.payments.made.export');

        $routes->get('credit-details', 'PurchaseReportController@creditDetails')->name('report.purchase.credit.details');
        $routes->get('credit-details/export', 'PurchaseReportController@creditDetailsExport')->name('report.purchase.credit.details.export');

        /** Payables */
        $routes->get('supplier-balance', 'PurchaseReportController@supplierBalance')->name('report.supplier.balance');
        $routes->get('supplier-balance/export', 'PurchaseReportController@supplierBalanceExport')->name('report.supplier.balance.export');

        $routes->get('aging-summary', 'PurchaseReportController@agingSummary')->name('report.purchase.aging.summary');
        $routes->get('aging-summary/export', 'PurchaseReportController@agingSummaryExport')->name('report.purchase.aging.summary.export');

        $routes->get('aging-details', 'PurchaseReportController@agingDetails')->name('report.purchase.aging.details');
        $routes->get('aging-details/export', 'PurchaseReportController@agingDetailsExport')->name('report.purchase.aging.details.export');

        $routes->get('purchase-order-details', 'PurchaseReportController@poDetails')->name('report.purchase.order.details');
        $routes->get('purchase-order-details/export', 'PurchaseReportController@poDetailsExport')->name('report.purchase.order.details.export');

        $routes->get('bill-details', 'PurchaseReportController@billDetails')->name('report.bill.details');
        $routes->get('bill-details/export', 'PurchaseReportController@billDetailsExport')->name('report.bill.details.export');

        $routes->get('purchase-returns', 'PurchaseReportController@purchaseReturns')->name('report.purchase.returns');
    });

    /** sales reports related routes */
    $routes->group(['prefix' => '/sales'], function ($routes) {

        /** sales */
        $routes->get('sales-summary', 'SalesReportController@salesSummary')->name('report.sales.summary');
        $routes->post('sales-summary-list', 'SalesReportController@salesSummaryList')->name('report.sales.summary.list');
        $routes->post('sales-summary-product-list', 'SalesReportController@salesSummaryProductList')->name('report.sales.summary.product.list');
        $routes->get('sales-summary/export', 'SalesReportController@salesSummaryExport')->name('report.sales.summary.export');

        $routes->get('sales-by-customer', 'SalesReportController@salesByCus')->name('report.sales.by.customer');
        $routes->get('sales-by-customer/export', 'SalesReportController@salesByCusExport')->name('report.sales.by.customer.export');

        $routes->get('sales-by-product', 'SalesReportController@salesByPro')->name('report.sales.by.product');
        $routes->get('sales-by-product/export', 'SalesReportController@salesByProExport')->name('report.sales.by.product.export');

        $routes->get('damage-by-product', 'SalesReportController@damageByPro')->name('report.damage.by.product');
        $routes->get('damage-by-product/export', 'SalesReportController@damageByProExport')->name('report.damage.by.product.export');

        $routes->get('damage-by-route', 'SalesReportController@damageByRoute')->name('report.damage.by.route');

        $routes->get('damage-by-rep', 'SalesReportController@damageByRep')->name('report.damage.by.rep');

        $routes->get('damage-by-customer', 'SalesReportController@damageByCustomer')->name('report.damage.by.customer');

        $routes->get('sales-by-product-category', 'SalesReportController@salesByProCat')->name('report.sales.by.product.category');
        $routes->get('sales-by-product-category/export', 'SalesReportController@salesByProCatExport')->name('report.sales.by.product.category.export');

        $routes->get('sales-by-sales-rep', 'SalesReportController@salesByRep')->name('report.sales.by.sales.rep');
        $routes->get('sales-by-sales-rep/export', 'SalesReportController@salesByRepExport')->name('report.sales.by.sales.rep.export');

        $routes->get('sales-by-route', 'SalesReportController@salesByRoute')->name('report.sales.by.route');
        $routes->get('sales-by-route/export', 'SalesReportController@salesByRouteExport')->name('report.sales.by.route.export');

        $routes->get('monthly-sales', 'SalesReportController@monthlySales')->name('report.monthly.sales');
        $routes->get('monthly-sales/export', 'SalesReportController@monthlySalesExport')->name('report.monthly.sales.export');
        /** payment received */
        $routes->get('payments-received', 'SalesReportController@paysReceived')->name('report.payments.received');
        $routes->get('payments-received/export', 'SalesReportController@paysReceivedExport')->name('report.payments.received.export');

        $routes->get('credit-details', 'SalesReportController@creditDetails')->name('report.credit.details');
        $routes->get('credit-details/export', 'SalesReportController@creditDetailsExport')->name('report.credit.details.export');

        /** receivables */
        $routes->get('customer-balance', 'SalesReportController@customerBalance')->name('report.customer.balance');
        $routes->get('customer-balance/export', 'SalesReportController@customerBalanceExport')->name('report.customer.balance.export');

        $routes->get('aging-summary', 'SalesReportController@agingSummary')->name('report.aging.summary');
        $routes->get('aging-summary/export', 'SalesReportController@agingSummaryExport')->name('report.aging.summary.export');

        $routes->get('aging-details', 'SalesReportController@agingDetails')->name('report.aging.details');
        $routes->get('aging-details/export', 'SalesReportController@agingDetailsExport')->name('report.aging.details.export');

        $routes->get('sales-order-details', 'SalesReportController@salesDetails')->name('report.sales.order.details');
        $routes->get('sales-order-details/export', 'SalesReportController@salesDetailsExport')->name('report.sales.order.details.export');

        $routes->get('invoice-details', 'SalesReportController@invoiceDetails')->name('report.invoice.details');
        $routes->get('allocation-details', 'SalesReportController@allocationDetails')->name('report.allocation.details');
        $routes->get('invoice-details/export', 'SalesReportController@invoiceDetailsExport')->name('report.invoice.details.export');
        $routes->get('allocation-details/export', 'SalesReportController@allocationDetailsExport')->name('report.allocation.details.export');

        $routes->get('estimate-details', 'SalesReportController@estimateDetails')->name('report.estimate.details');
        $routes->get('estimate-details/export', 'SalesReportController@estimateDetailsExport')->name('report.estimate.details.export');

        $routes->get('inquiry-details', 'SalesReportController@inquiryDetails')->name('report.inquiry.details');
        $routes->get('inquiry-details/export', 'SalesReportController@inquiryDetailsExport')->name('report.inquiry.details.export');

        $routes->get('sales-returns', 'SalesReportController@salesReturns')->name('report.sales.returns');
        $routes->get('sales-returns/export', 'SalesReportController@salesReturnsExport')->name('report.sales.return.export');

        $routes->get('sales-by-sales-location', 'SalesReportController@salesByLocation')->name('report.sales.by.sales.location');
        $routes->get('sales-by-sales-location/export', 'SalesReportController@salesByLocationExport')->name('report.sales.by.sales.location.export');
    });

    /** credits reports related routes */
    $routes->group(['prefix' => '/credits'], function ($routes) {

        $routes->get('credit-by-route', 'CreditsReportController@creditByRoute')->name('report.credits.by.route');

        $routes->get('credit-by-rep', 'CreditsReportController@creditByRep')->name('report.credits.by.rep');

        $routes->get('credit-by-customer', 'CreditsReportController@creditByCustomer')->name('report.credits.by.customer');

        $routes->post('sales-list', 'CreditsReportController@salesList')->name('report.credits.sales.list');
        $routes->post('sold-items-list', 'CreditsReportController@soldItemsList')->name('report.credits.sold.items.list');

    });

    /** expense reports related routes */
    $routes->group(['prefix' => '/expense'], function ($routes) {

        /** Receipts */
        $routes->get('expense-details', 'ExpenseReportController@expenseDetails')->name('report.expense.details');
        $routes->get('expense-details/export', 'ExpenseReportController@expenseDetailsExport')->name('report.expense.details.export');

        $routes->get('un-submitted-expenses', 'ExpenseReportController@unSubExpenses')->name('report.expense.un.submitted');
        $routes->get('un-submitted-expenses/export', 'ExpenseReportController@unSubExpensesExport')->name('report.expense.un.submitted.export');

        $routes->get('expenses-by-category', 'ExpenseReportController@expenseByCat')->name('report.expense.by.category');
        $routes->get('expenses-by-category/export', 'ExpenseReportController@expenseByCatExport')->name('report.expense.by.category.export');

        $routes->get('expenses-by-type', 'ExpenseReportController@expenseByType')->name('report.expense.by.type');
        $routes->get('expenses-by-type/export', 'ExpenseReportController@expenseByTypeExport')->name('report.expense.by.type.export');

        $routes->get('expenses-by-rep', 'ExpenseReportController@expenseByRep')->name('report.expense.by.rep');
        $routes->get('expenses-by-rep/export', 'ExpenseReportController@expenseByRepExport')->name('report.expense.by.rep.export');

        $routes->get('expenses-by-shop', 'ExpenseReportController@expenseByShop')->name('report.expense.by.shop');
        $routes->get('expenses-by-shop/export', 'ExpenseReportController@expenseByShopExport')->name('report.expense.by.shop.export');

        $routes->get('office-exp-by-company', 'ExpenseReportController@officeExpByCompany')->name('report.expense.office.by.company');

        $routes->get('expenses-by-customer', 'ExpenseReportController@expenseByCus')->name('report.expense.by.customer');
        $routes->get('expenses-by-customer/export', 'ExpenseReportController@expenseByCusExport')->name('report.expense.by.customer.export');

        $routes->get('expenses-by-supplier', 'ExpenseReportController@expenseBySup')->name('report.expense.by.supplier');
        $routes->get('expenses-by-supplier/export', 'ExpenseReportController@expenseBySupExport')->name('report.expense.by.supplier.export');

        $routes->get('expenses-by-employee', 'ExpenseReportController@expenseByEmp')->name('report.expense.by.emp');
        $routes->get('expenses-by-employee/export', 'ExpenseReportController@expenseByEmpExport')->name('report.expense.by.emp.export');

        $routes->get('mileage-exp-by-employee', 'ExpenseReportController@mileageExpByEmp')->name('report.mileage.expense.by.emp');
        $routes->get('mileage-exp-by-employee/export', 'ExpenseReportController@mileageExpByEmpExport')->name('report.mileage.expense.by.emp.export');

        /** Reports */
        $routes->get('expense-report-details', 'ExpenseReportController@expReportDetails')->name('report.expense.report.details');
        $routes->get('expense-report-details/export', 'ExpenseReportController@expReportDetailsExport')->name('report.expense.report.details.export');

        /** Reimbursements */
        $routes->get('reimbursements', 'ExpenseReportController@reimbursements')->name('report.expense.reimbursements');
        $routes->get('reimbursements/export', 'ExpenseReportController@reimbursementsExport')->name('report.expense.reimbursements.export');
    });

    /** stock reports related routes */
    $routes->group(['prefix' => '/stock'], function ($routes) {

        /** stock ledger report */
        $routes->get('stock-ledger', 'StockReportController@stockLedger')->name('report.stock.ledger');
        $routes->get('stock-ledger/export', 'StockReportController@stockLedgerExport')->name('report.stock.ledger.export');

    });

    /** finance reports related routes */
    $routes->group(['prefix' => '/finance'], function ($routes) {

        /** stock ledger report */
        $routes->get('customer-ledger', 'FinanceReportController@customerLedger')->name('report.finance.customer.ledger');
        $routes->get('customer-ledger/export', 'FinanceReportController@customerLedgerExport')->name('report.finance.customer.ledger.export');

    });

});
