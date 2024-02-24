<?php
Route::group(['prefix' => '/purchase', 'namespace' => 'Purchase'], function ($routes) {
    $routes->get('', 'PurchaseController@index')->name('purchase.index');
    $routes->get('year/chart', 'PurchaseController@yearChart')->name('purchase.year.chart');
    $routes->get('month/chart', 'PurchaseController@monthChart')->name('purchase.month.chart');

    /** suppliers related routes */
    $routes->group(['prefix' => '/supplier'], function ($routes) {
        $routes->get('', 'SupplierController@index')->name('purchase.supplier.index');
        $routes->post('/data', 'SupplierController@dataTableData')->name('purchase.supplier.table.data');
        $routes->get('/create', 'SupplierController@create')->name('purchase.supplier.create');
        $routes->post('/', 'SupplierController@store')->name('purchase.supplier.store');
        $routes->get('search/{q?}', 'SupplierController@search')->name('purchase.supplier.search');
        $routes->group(['prefix' => '{supplier}'], function ($routes) {
            $routes->get('/', 'SupplierController@show')->name('purchase.supplier.show');
            $routes->get('/edit', 'SupplierController@edit')->name('purchase.supplier.edit');
            $routes->patch('/', 'SupplierController@update')->name('purchase.supplier.update');
            $routes->delete('/', 'SupplierController@delete')->name('purchase.supplier.delete');
            $routes->post('opening/', 'SupplierController@storeOpening')->name('purchase.supplier.opening.store');

            $routes->get('/get/supplier/image', 'SupplierController@getLogo')->name('purchase.supplier.logo');
            $routes->get('/remove/supplier/image', 'SupplierController@removeLogo')->name('purchase.supplier.logo.remove');

            $routes->get('/statement', 'SupplierController@statement')->name('purchase.supplier.statement');
            $routes->get('/export', 'SupplierController@exportStatement')->name('purchase.supplier.statement.export');
        });
    });

    /** purchase order related routes */
    $routes->group(['prefix' => '/request'], function ($routes) {
        $routes->group(['prefix' => '{purchaseRequest}'], function ($routes) {
            $routes->get('/confirm', 'OrderController@confirmRequest')->name('purchase.request.confirm');
            $routes->patch('/do/confirm', 'OrderController@doConfirmRequest')->name('purchase.request.do.confirm');
        });
    });

    /** purchase order related routes */
    $routes->group(['prefix' => '/order'], function ($routes) {
        $routes->get('', 'OrderController@index')->name('purchase.order.index');
        $routes->get('/requests', 'OrderController@requests')->name('purchase.order.request');
        $routes->post('/data', 'OrderController@dataTableData')->name('purchase.order.table.data');
        $routes->get('/create', 'OrderController@create')->name('purchase.order.create');
        $routes->post('/', 'OrderController@store')->name('purchase.order.store');
        $routes->group(['prefix' => '{order}'], function ($routes) {
            $routes->get('/export/{type?}', 'OrderController@export')->name('purchase.order.export');
            $routes->get('/print', 'OrderController@printView')->name('purchase.order.print');
            $routes->get('/', 'OrderController@show')->name('purchase.order.show');
            $routes->get('/edit', 'OrderController@edit')->name('purchase.order.edit');
            $routes->get('/clone', 'OrderController@clone')->name('purchase.order.clone');
            $routes->post('/', 'OrderController@copy')->name('purchase.order.copy');
            $routes->patch('/', 'OrderController@update')->name('purchase.order.update');
            $routes->delete('/', 'OrderController@delete')->name('purchase.order.delete');
            $routes->post('/approve', 'OrderController@approve')->name('purchase.order.approve');
            $routes->post('/convert', 'OrderController@convert')->name('purchase.order.convert');
            $routes->post('/cancel', 'OrderController@cancelOrder')->name('purchase.order.cancel');

            /** create bill for purchase */
            $routes->post('/create/bill', 'BillController@store')->name('purchase.bill.store');

            /** create an order using wizard format */
            $routes->group(['prefix' => 'wizard'], function ($routes) {
                /** confirm order */
                $routes->get('/confirm', 'OrderController@confirm')->name('purchase.order.confirm');
                $routes->post('/do-confirm', 'OrderController@doConfirm')->name('purchase.order.do.confirm');
                /** generate bill */
                $routes->get('/bill', 'OrderController@bill')->name('purchase.order.bill');
                $routes->post('/generate-bill', 'OrderController@generateBill')->name('purchase.order.generate.bill');
                /** record payment */
                $routes->group(['prefix' => 'bill/{bill}'], function ($routes) {
                    $routes->get('/payment', 'OrderController@payment')->name('purchase.order.payment');
                    $routes->post('/record-payment', 'OrderController@recordPayment')->name('purchase.order.record.payment');
                });
            });

        });
    });

    /** bill related routes */
    $routes->group(['prefix' => '/bill'], function ($routes) {
        $routes->get('', 'BillController@index')->name('purchase.bill.index');
        $routes->post('/data', 'BillController@dataTableData')->name('purchase.bill.table.data');
        $routes->get('search/{supplier?}/{businessType?}/{where?}/{formatted?}/{q?}', 'BillController@referenceSearch')->name('purchase.bill.reference.search');
        $routes->group(['prefix' => '{bill}'], function ($routes) {
            $routes->get('/export/{type?}', 'BillController@export')->name('purchase.bill.export');
            $routes->get('/print', 'BillController@printView')->name('purchase.bill.print');
            $routes->get('/', 'BillController@show')->name('purchase.bill.show');
            $routes->get('/get', 'BillController@getBill')->name('purchase.bill.get');
            $routes->get('/edit', 'BillController@edit')->name('purchase.bill.edit');
            $routes->patch('/', 'BillController@update')->name('purchase.bill.update');
            $routes->delete('/', 'BillController@delete')->name('purchase.bill.delete');
            $routes->post('/cancel', 'BillController@cancelBill')->name('purchase.bill.cancel');
            /** record payment for a bill */
            $routes->post('/create/payment', 'PaymentController@store')->name('purchase.payment.store');
            $routes->get('/{payment}/export/{type?}', 'PaymentController@export')->name('purchase.payment.export');
            $routes->get('/{payment}/print', 'PaymentController@printView')->name('purchase.payment.print');
        });
    });

    /** payment related routes */
    $routes->group(['prefix' => '/payment'], function ($routes) {
        $routes->get('', 'PaymentController@index')->name('purchase.payment.index');
        $routes->post('/data', 'PaymentController@dataTableData')->name('purchase.payment.table.data');
        $routes->group(['prefix' => '{payment}'], function ($routes) {
            $routes->get('/', 'PaymentController@show')->name('purchase.payment.show');
            $routes->get('/edit', 'PaymentController@edit')->name('purchase.payment.edit');
            $routes->patch('/', 'PaymentController@update')->name('purchase.payment.update');
            $routes->patch('/credit', 'PaymentController@updateFromCredit')->name('purchase.payment.credit.update');
            $routes->delete('/', 'PaymentController@delete')->name('purchase.payment.delete');
            $routes->post('/cancel', 'PaymentController@cancelPayment')->name('purchase.payment.cancel');
            $routes->post('/refund', 'PaymentController@refundPayment')->name('purchase.payment.refund');
        });
    });


    $routes->group(['prefix' => '/credit', 'as' => 'purchase.credit.'], function ($routes) {
        $routes->get('', 'SupplierCreditController@index')->name('index');
        $routes->get('create', 'SupplierCreditController@create')->name('create');
        $routes->post('', 'SupplierCreditController@save')->name('store');
        $routes->group(['prefix' => '{credit}'], function ($routes) {
            $routes->post('/status/change', 'SupplierCreditController@statusChange')->name('status.change');
            $routes->get('/', 'SupplierCreditController@show')->name('show');
            $routes->get('/edit', 'SupplierCreditController@edit')->name('edit');
            $routes->patch('/', 'SupplierCreditController@update')->name('update');
            $routes->delete('/', 'SupplierCreditController@delete')->name('delete');
            $routes->get('/clone', 'SupplierCreditController@clone')->name('clone');
            $routes->post('/', 'SupplierCreditController@copy')->name('copy');
            $routes->get('/export/{type?}', 'SupplierCreditController@export')->name('export');
            $routes->get('/print', 'SupplierCreditController@printView')->name('print');
            $routes->group(['prefix' => 'refund', 'as' => 'refund.'], function ($routes) {
                $routes->get('/', 'CreditRefundController@index')->name('get');
                $routes->post('/save', 'CreditRefundController@save')->name('save');
                $routes->group(['prefix' => '{refund}'], function ($routes) {
                    $routes->get('/export/{type?}', 'CreditRefundController@export')->name('export');
                    $routes->get('/print', 'CreditRefundController@printView')->name('print');
                    $routes->get('/edit', 'CreditRefundController@edit')->name('edit');
                    $routes->patch('/', 'CreditRefundController@update')->name('update');
                    $routes->delete('/delete', 'CreditRefundController@delete')->name('delete');
                });
            });
            $routes->group(['prefix' => 'bill', 'as' => 'bill.'], function ($routes) {
                $routes->post('/save', 'SupplierCreditBillController@savePayment')->name('save');
            });
        });
    });

    /** bill related routes */
    $routes->group(['prefix' => '/grn'], function ($routes) {
        $routes->get('', 'GrnController@index')->name('purchase.grn.index');
        $routes->get('/create', 'GrnController@create')->name('purchase.grn.create');
        $routes->post('', 'GrnController@store')->name('purchase.grn.store');
        $routes->group(['prefix' => '{grn}'], function ($routes) {
            $routes->get('/print', 'GrnController@printView')->name('purchase.grn.print');
            $routes->get('/', 'GrnController@show')->name('purchase.grn.show');
            $routes->get('/edit', 'GrnController@edit')->name('purchase.grn.edit');
            $routes->patch('/', 'GrnController@update')->name('purchase.grn.update');
            $routes->post('/approve', 'GrnController@approve')->name('purchase.grn.approve');
            $routes->get('/receive', 'GrnController@receive')->name('purchase.grn.receive');
            $routes->patch('/do-receive', 'GrnController@doReceive')->name('purchase.grn.do.receive');
        });
    });

    /** return related routes */
    $routes->group(['prefix' => '/return'], function ($routes) {
        $routes->get('', 'ReturnController@index')->name('purchase.return.index');
        $routes->group(['prefix' => '{return}'], function ($routes) {
            $routes->get('/export/{type?}', 'ReturnController@export')->name('purchase.return.export');
            $routes->get('/print', 'ReturnController@printView')->name('purchase.return.print');
            $routes->get('/', 'ReturnController@show')->name('purchase.return.show');
        });
    });

    $routes->get('/supplier/{type}/search/{q?}/', 'SupplierController@searchByType')->name('purchase.supplier.by.type.search');

    //Summary related routes
    $routes->get('get/order/{status?}', 'PurchaseController@getOrderCount')->name('purchase.summary.order');
    $routes->get('get/top/products', 'PurchaseController@getTopFiveProducts')->name('purchase.top.products');
    $routes->get('get/top/suppliers', 'PurchaseController@getTopFiveSupplier')->name('purchase.top.suppliers');
    $routes->get('get/bill/', 'PurchaseController@getBills')->name('purchase.summary.bill');
    $routes->get('{model}/{take?}/{with?}/{where?}/{field?}', 'PurchaseController@summary')->name('purchase.summary.index');

});
