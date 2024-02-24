<?php
Route::group(['prefix' => '/finance', 'namespace' => 'Finance'], function ($routes) {
    $routes->get('', 'FinanceController@index')->name('finance.index');

    /** account related routes */
    $routes->group(['prefix' => '/account'], function ($routes) {
        $routes->get('', 'AccountController@index')->name('finance.account.index');
        $routes->get('groups/', 'AccountController@getGroups')->name('finance.account.group.list');
        $routes->get('/create', 'AccountController@create')->name('finance.account.create');
        $routes->post('/', 'AccountController@store')->name('finance.account.store');
        $routes->get('expense/search/{q?}', 'AccountController@searchExpenseAccount')->name('finance.expense.account.search');
        $routes->get('paid-through/search/{q?}', 'AccountController@searchPaidThroughAccount')->name('finance.paid.through.account.search');
        $routes->get('search/{q?}', 'AccountController@search')->name('finance.account.search');
        $routes->group(['prefix' => '{account}'], function ($routes) {
            $routes->get('/', 'AccountController@show')->name('finance.account.show');
            $routes->get('/edit', 'AccountController@edit')->name('finance.account.edit');
            $routes->patch('/', 'AccountController@update')->name('finance.account.update');
            $routes->delete('/', 'AccountController@delete')->name('finance.account.delete');
            $routes->post('/opening', 'AccountController@saveOpening')->name('finance.account.save.opening');
            $routes->get('/export', 'AccountController@export')->name('finance.account.export');
        });
    });

    /** journals related routes */
    $routes->group(['prefix' => '/journal'], function ($routes) {
        $routes->get('', 'JournalController@index')->name('finance.journal.index');
        $routes->get('/create', 'JournalController@create')->name('finance.journal.create');
        $routes->post('/', 'JournalController@store')->name('finance.journal.store');
        $routes->group(['prefix' => '{journal}'], function ($routes) {
            $routes->get('/', 'JournalController@show')->name('finance.journal.show');
            $routes->get('/edit', 'JournalController@edit')->name('finance.journal.edit');
            $routes->patch('/', 'JournalController@update')->name('finance.journal.update');
            $routes->delete('/', 'JournalController@delete')->name('finance.journal.delete');
        });
    });

    /** banking related routes */
    $routes->group(['prefix' => '/banking'], function ($routes) {
        $routes->get('', 'BankingController@index')->name('finance.banking.index');

    });

    /** account balances related routes */
    $routes->group(['prefix' => '/account-balances'], function ($routes) {
        $routes->get('', 'AccountBalanceController@index')->name('finance.account.balance.index');

    });

    /** trial balance related routes */
    $routes->group(['prefix' => '/trial-balance'], function ($routes) {
        $routes->get('', 'TrialBalanceController@index')->name('finance.trial.balance.index');

    });

    /** trial balance related routes */
    $routes->group(['prefix' => '/trial-balance'], function ($routes) {
        $routes->get('', 'TrialBalanceController@index')->name('finance.trial.balance.index');

    });

    /** general ledger related routes */
    $routes->group(['prefix' => '/general-ledger'], function ($routes) {
        $routes->get('', 'GeneralLedgerController@index')->name('finance.general.ledger.index');
        $routes->get('customers-and-suppliers', 'GeneralLedgerController@getCustomerAndSuppliers')
            ->name('finance.general.ledger.customer-and-suppliers');

    });

    /** cheques in hand related routes */
    $routes->group(['prefix' => '/cheques-in-hand'], function ($routes) {
        $routes->get('', 'ChequesInHandController@index')->name('finance.cheques.hand.index');
        $routes->get('/registered-by', 'ChequesInHandController@registeredBy')->name('finance.cheques.hand.by.registered.date');
        $routes->get('/deposited-cheques', 'ChequesInHandController@deposited')->name('finance.cheques.deposited');
        $routes->get('/create', 'ChequesInHandController@create')->name('finance.cheques.hand.create');
        $routes->post('/', 'ChequesInHandController@store')->name('finance.cheques.hand.store');
        $routes->group(['prefix' => '{cheque}'], function ($routes) {
            $routes->get('/', 'ChequesInHandController@show')->name('finance.cheques.hand.show');
            $routes->get('/edit', 'ChequesInHandController@edit')->name('finance.cheques.hand.edit');
            $routes->patch('/', 'ChequesInHandController@update')->name('finance.cheques.hand.update');
            $routes->delete('/', 'ChequesInHandController@delete')->name('finance.cheques.hand.delete');
        });

        /** deposit */
        $routes->get('/deposit/{chequeNo}', 'ChequesInHandController@deposit')->name('finance.cheques.hand.deposit');
        $routes->patch('/deposit/{chequeNo}/do', 'ChequesInHandController@doDeposit')->name('finance.cheques.hand.do.deposit');

        /** realise */
        $routes->get('/realise/{chequeNo}', 'ChequesInHandController@realise')->name('finance.cheques.hand.realise');
        $routes->patch('/realise/{chequeNo}/do', 'ChequesInHandController@doRealise')->name('finance.cheques.hand.do.realise');

        /** bounce */
        $routes->get('/bounce/{chequeNo}', 'ChequesInHandController@bounce')->name('finance.cheques.hand.bounce');
        $routes->patch('/bounce/{chequeNo}/do', 'ChequesInHandController@doBounce')->name('finance.cheques.hand.do.bounce');
    });

    /** transfer related routes */
    $routes->group(['prefix' => '/transfer'], function ($routes) {
        $routes->get('', 'TransferController@index')->name('finance.transfer.index');
        $routes->get('/create', 'TransferController@create')->name('finance.transfer.create');
        $routes->get('/report', 'TransferController@report')->name('finance.transfer.report');
        $routes->post('/', 'TransferController@store')->name('finance.transfer.store');
        $routes->group(['prefix' => '{transfer}'], function ($routes) {
            $routes->get('/', 'TransferController@show')->name('finance.transfer.show');
            $routes->delete('/', 'TransferController@delete')->name('finance.transfer.delete');

            $routes->patch('/status-update', 'TransferController@statusUpdate')->name('finance.transfer.status.update');
            $routes->post('/approve', 'TransferController@approve')->name('finance.transfer.approve');
            $routes->post('/decline', 'TransferController@decline')->name('finance.transfer.decline');

            $routes->post('/upload-receipt', 'TransferController@uploadReceipt')->name('finance.transfer.upload.receipt');

            $routes->get('/get/deposited/receipt', 'TransferController@getReceipt')->name('finance.transfer.deposited.receipt');
        });
    });

    /** shop transfer related routes */
    $routes->group(['prefix' => '/shop-transfer'], function ($routes) {
        $routes->get('/create', 'TransferController@shopCreate')->name('finance.transfer.shop.create');
        $routes->post('/', 'TransferController@shopStore')->name('finance.transfer.shop.store');
    });

    /** cash book related rep */
    $routes->group(['prefix' => '/cash-book'], function ($routes) {
        $routes->get('/rep', 'CashBookController@byRep')->name('finance.cash.book.rep.index');
    });

    /** cash book related company */
    $routes->group(['prefix' => '/cash-book'], function ($routes) {
        $routes->get('/company', 'CashBookController@byCompany')->name('finance.cash.book.company.index');
    });

    /** day book related rep */
    $routes->group(['prefix' => '/day-book'], function ($routes) {
        $routes->get('/rep', 'DayBookController@byRep')->name('finance.day.book.rep.index');
    });

    /** day book related company */
    $routes->group(['prefix' => '/day-book'], function ($routes) {
        $routes->get('/company', 'DayBookController@byCompany')->name('finance.day.book.company.index');
    });

    $routes->group(['prefix' => '/returned-cheque'], function ($routes) {
        $routes->get('', 'ReturnedChequeController@index')->name('finance.return.cheques.index');
        $routes->group(['prefix' => '{cheque}'], function ($routes) {
            $routes->get('/show', 'ReturnedChequeController@show')->name('finance.return.cheques.show');
            $routes->group(['prefix' => '/payment'], function ($routes) {
                $routes->get('/create', 'ReturnedChequeController@createPayment')->name('finance.return.cheques.create.payment');
                $routes->post('/', 'ReturnedChequeController@storePayment')->name('finance.return.cheques.store.payment');
                $routes->group(['prefix' => '{payment}'], function ($routes) {
                    $routes->get('/edit', 'ReturnedChequeController@editPayment')->name('finance.return.cheques.edit.payment');
                    $routes->patch('/', 'ReturnedChequeController@updatePayment')->name('finance.return.cheques.update.payment');
                    $routes->post('/cancel', 'ReturnedChequeController@cancelPayment')->name('finance.return.cheques.cancel.payment');
                });
            });
        });
    });

    $routes->group(['prefix' => '/commission', 'as' => 'finance.commission.'], function ($routes) {
        $routes->get('/year/{year}', 'CommissionController@index')->name('index');
        $routes->get('/rep/{rep}/year/{year}/month/{month}/create', 'CommissionController@create')->name('create');
        $routes->post('/rep/{rep}/year/{year}/month/{month}/store', 'CommissionController@store')->name('store');

        $routes->get('/rep/{rep}/year/{year}/month/{month}/credit-sales', 'CommissionController@creditSales')->name('credit.sales');
        $routes->get('/rep/{rep}/year/{year}/month/{month}/total-sales', 'CommissionController@totalSales')->name('total.sales');

        $routes->get('/rep/{rep}/year/{year}/month/{month}/cheque-received', 'CommissionController@chequeReceived')->name('cheque.received');

        $routes->get('/rep/{rep}/year/{year}/month/{month}/cash-collection', 'CommissionController@cashCollection')->name('cash.collection');
        $routes->get('/rep/{rep}/year/{year}/month/{month}/cheque-collection', 'CommissionController@chequeCollection')->name('cheque.collection');

        $routes->get('/rep/{rep}/year/{year}/month/{month}/sales-returns', 'CommissionController@salesReturns')->name('sales.returns');
        $routes->get('/rep/{rep}/year/{year}/month/{month}/sales-returns-expired', 'CommissionController@expiredSalesReturns')->name('expired.sales.returns');

        $routes->get('/rep/{rep}/year/{year}/month/{month}/cheques-returned', 'CommissionController@chequeReturned')->name('cheques.returned');

        $routes->get('/rep/{rep}/year/{year}/month/{month}/cheque-realized', 'CommissionController@chequeRealized')->name('cheque.realized');

        $routes->get('/rep/{rep}/year/{year}/month/{month}/cash-shortages', 'CommissionController@cashShortages')->name('cash.shortages');
        $routes->get('/rep/{rep}/year/{year}/month/{month}/stock-shortages', 'CommissionController@stockShortages')->name('stock.shortages');

        $routes->group(['prefix' => '{commission}'], function ($routes) {
            $routes->get('/', 'CommissionController@show')->name('show');
            $routes->patch('/confirm', 'CommissionController@confirm')->name('confirm');
        });

    });

    /** issued cheques related routes */
    $routes->group(['prefix' => '/issued-cheques'], function ($routes) {
        $routes->get('', 'IssuedChequeController@index')->name('finance.issued.cheque.index');
        $routes->get('/create', 'IssuedChequeController@create')->name('finance.issued.cheque.create');
        $routes->post('/', 'IssuedChequeController@store')->name('finance.issued.cheque.store');
        $routes->group(['prefix' => '{cheque}'], function ($routes) {
            $routes->get('/', 'IssuedChequeController@show')->name('finance.issued.cheque.show');
            $routes->get('/edit', 'IssuedChequeController@edit')->name('finance.issued.cheque.edit');
            $routes->patch('/', 'IssuedChequeController@update')->name('finance.issued.cheque.update');
            $routes->delete('/', 'IssuedChequeController@delete')->name('finance.issued.cheque.delete');
        });
    });

    $routes->get('/account/{company}/account/search/{q?}/', 'AccountController@searchByCompany')->name('finance.account.by.company.search');
    $routes->get('/expense/account/{company}/account/search/{q?}/', 'AccountController@searchExpAccountByCompany')->name('finance.expense.account.by.company.search');
    $routes->get('/cash/paid/through/account/{company}/account/search/{q?}/', 'AccountController@searchCashPaidThroughByCompany')->name('finance.cash.paid.through.account.by.company.search');
    $routes->get('/cih/paid/through/account/{company}/account/search/{q?}/', 'AccountController@searchCihPaidThroughByCompany')->name('finance.cih.paid.through.account.by.company.search');
    $routes->get('/others/paid/through/account/{company}/account/search/{q?}/', 'AccountController@searchOthersPaidThroughByCompany')->name('finance.others.paid.through.account.by.company.search');

});
