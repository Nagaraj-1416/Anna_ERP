<?php
Route::group(['prefix' => '/expense', 'namespace' => 'Expense'], function ($routes) {
    $routes->get('', 'ExpenseController@index')->name('expense.index');
    $routes->post('get-mileage-rate', 'ExpenseController@getMileageRate')->name('expense.get.mileage.rate');
    $routes->get('year/chart', 'ExpenseController@yearChart')->name('expense.year.chart');
    $routes->get('month/chart', 'ExpenseController@monthChart')->name('expense.month.chart');
    $routes->get('type/chart', 'ExpenseController@typeChart')->name('expense.type.chart');
    $routes->get('top/reports', 'ExpenseController@topReports')->name('expense.top.reports');

    $routes->group(['prefix' => '/receipts', 'as' => 'expense.receipt.'], function ($routes) {
        $routes->get('', 'ReceiptController@index')->name('index');
        $routes->get('/create', 'ReceiptController@create')->name('create');
        $routes->post('/', 'ReceiptController@store')->name('store');
        $routes->post('/get-expenses', 'ReceiptController@getExpenses')->name('get.expenses');
        $routes->get('search-by-bt/{businessType}/{q?}', 'ReceiptController@searchByBusinessType')->name('search.by.business.type');
        $routes->get('search/{q?}', 'ReceiptController@search')->name('search');
        $routes->group(['prefix' => '{expense}'], function ($routes) {
            $routes->get('/export/{type?}', 'ReceiptController@export')->name('export');
            $routes->get('/print', 'ReceiptController@printView')->name('print');
            $routes->get('/', 'ReceiptController@show')->name('show');
            $routes->patch('/approve', 'ReceiptController@approve')->name('approve');
            $routes->get('/edit', 'ReceiptController@edit')->name('edit');
            $routes->patch('/', 'ReceiptController@update')->name('update');
            $routes->delete('/', 'ReceiptController@delete')->name('delete');

            $routes->get('/add-item', 'ReceiptController@addItem')->name('add.item');
            $routes->post('/store-item', 'ReceiptController@storeItem')->name('store.item');
            $routes->group(['prefix' => '/exp-item', 'as' => 'expense.item.'], function ($routes) {
                $routes->group(['prefix' => '{item}'], function ($routes) {
                    $routes->delete('/delete-item', 'ReceiptController@deleteItem')->name('delete.item');
                });
            });

            /** add payments */
            $routes->get('/add-payment/{mode}', 'ReceiptController@addPayment')->name('add.payment');
            $routes->post('/store-payment/{mode}', 'ReceiptController@storePayment')->name('store.payment');
            $routes->group(['prefix' => '/exp-payment', 'as' => 'expense.payment.'], function ($routes) {
                $routes->group(['prefix' => '{expensePayment}'], function ($routes) {
                    $routes->delete('/delete-payment', 'ReceiptController@deletePayment')->name('delete');
                });
            });

        });
    });

    $routes->group(['prefix' => '/reports', 'as' => 'expense.reports.'], function ($routes) {
        $routes->get('', 'ReportController@index')->name('index');
        $routes->get('/create', 'ReportController@create')->name('create');
        $routes->post('/', 'ReportController@store')->name('store');
        $routes->get('search/{q?}', 'ReportController@search')->name('search');

        $routes->group(['prefix' => '/approvals', 'as' => 'approvals.'], function ($routes) {
            $routes->get('/', 'ReportApprovalController@index')->name('index');
            $routes->post('/', 'ReportApprovalController@index')->name('data.table');
            $routes->post('{report}/approve', 'ReportApprovalController@approve')->name('approve');
        });

        $routes->group(['prefix' => '{report}'], function ($routes) {
            $routes->get('/', 'ReportController@show')->name('show');
            $routes->post('/submit', 'ReportController@submitToApproval')->name('submit.to.approvals');
            $routes->get('/edit', 'ReportController@edit')->name('edit');
            $routes->patch('/', 'ReportController@update')->name('update');
            $routes->delete('/', 'ReportController@delete')->name('delete');
            $routes->get('/export', 'ReportController@export')->name('export');
            $routes->get('/print', 'ReportController@printPdf')->name('print');
            $routes->group(['prefix' => 'reimburse', 'as' => 'reimbursement.'], function ($routes) {
                $routes->post('/', 'ReportController@reimbursementStore')->name('store');
                $routes->get('/{reimburse}', 'ReportController@reimbursementShow')->name('show');
                $routes->patch('/{reimburse}', 'ReportController@reimbursementUpdate')->name('update');
                $routes->delete('/{reimburse}', 'ReportController@reimbursementDelete')->name('delete');
            });
        });
    });
    $routes->get('summary/{model}/{where?}', 'ExpenseController@getSummary')->name('expense.index.summary');
    $routes->group(['prefix' => '/category', 'as' => 'expense.category.'], function ($routes) {
        $routes->post('/', 'ExpenseCategoryController@store')->name('store');
        $routes->get('search/{q?}', 'ExpenseCategoryController@search')->name('search');
    });

    $routes->group(['prefix' => '/type', 'as' => 'expense.type.'], function ($routes) {
        $routes->post('/', 'ExpenseTypeController@store')->name('store');
        $routes->get('search/{q?}', 'ExpenseTypeController@search')->name('search');
    });

});
