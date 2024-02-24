<?php
Route::group(['prefix' => '/'], function ($routes) {
    $routes->get('', 'DashboardController@index')->name('dashboard');
    $routes->get('dashboard/company-stats', 'DashboardController@companyStats')->name('company.stats');
    $routes->get('dashboard/company-stats/export', 'DashboardController@companyStatsExport')->name('company.stats.export');
    $routes->get('dashboard/company-stats/print', 'DashboardController@companyStatsPrint')->name('company.stats.print');
    $routes->get('dashboard/sales-stats', 'DashboardController@salesStats')->name('sales.stats');
    $routes->get('dashboard/sales-stats/export', 'DashboardController@exportSalesStat')->name('sales.stats.export');
    $routes->get('dashboard/sales-stats/print', 'DashboardController@printSalesStat')->name('sales.stats.print');
    $routes->get('due/invoice', 'DashboardController@getDueInvoices')->name('dashboard.invoice');
    $routes->get('due/bill', 'DashboardController@getDueBills')->name('dashboard.bill');
    $routes->get('dashboard/income', 'DashboardController@yearDataIncome')->name('dashboard.income.data');
    $routes->get('dashboard/expense', 'DashboardController@yearDataExpense')->name('dashboard.expense.data');
    $routes->get('dashboard/summary', 'DashboardController@summaryData')->name('dashboard.summary.data');
    $routes->get('dashboard/over-due/{range?}', 'DashboardController@overDueData')->name('dashboard.over.due.data');
    $routes->get('list/invoice/{dateRange}', 'DashboardController@listDueInvoice')->name('dashboard.list.due.invoice');
    $routes->post('list/data/{modal}/{dateRange}', 'DashboardController@listDueData')->name('dashboard.list.due');
    $routes->get('dashboard/visit-stats', 'DashboardController@visitStats')->name('visit.stats');
    $routes->get('dashboard/customer-stats/{customer}', 'DashboardController@customerPayments')->name('dashboard.customer.stats');
    $routes->get('dashboard/rep-stats/{rep}', 'DashboardController@getPaymentDataForRep')->name('dashboard.rep.stats');
    $routes->get('dashboard/daily-stocks/', 'DashboardController@getDailyStockData')->name('dashboard.daily.stocks');
    $routes->get('dashboard/rep-stats', 'DashboardController@repStats')->name('rep.stats');
    $routes->get('dashboard/rep-stats/export', 'DashboardController@exportRepStat')->name('rep.stats.export');
    $routes->get('dashboard/rep-stats/print', 'DashboardController@printRepStat')->name('rep.stats.print');
});