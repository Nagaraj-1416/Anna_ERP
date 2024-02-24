<?php
Route::group(['prefix' => '/sales', 'namespace' => 'Sales'], function ($routes) {
    $routes->get('', 'SalesController@index')->name('sales.index');
    $routes->get('year/chart', 'SalesController@yearChart')->name('sales.year.chart');
    $routes->get('month/chart', 'SalesController@monthChart')->name('sales.month.chart');

    /** customer related routes */
    $routes->group(['prefix' => '/customer'], function ($routes) {
        $routes->get('', 'CustomerController@index')->name('sales.customer.index');
        $routes->post('/data', 'CustomerController@dataTableData')->name('sales.customer.table.data');
        $routes->get('/create', 'CustomerController@create')->name('sales.customer.create');
        $routes->get('/export', 'CustomerController@export')->name('sales.customer.export');
        $routes->post('/', 'CustomerController@store')->name('sales.customer.store');
        $routes->get('search/{q?}', 'CustomerController@search')->name('sales.customer.search');

        /** change route */
        $routes->get('/change/route', 'CustomerController@changeRoute')->name('sales.customer.change.route');
        $routes->post('/update/route', 'CustomerController@updateRoute')->name('sales.customer.update.route');

        $routes->group(['prefix' => '{customer}'], function ($routes) {
            $routes->get('/', 'CustomerController@show')->name('sales.customer.show');
            $routes->get('/ledger', 'CustomerController@ledger')->name('sales.customer.ledger');
            $routes->get('/edit', 'CustomerController@edit')->name('sales.customer.edit');
            $routes->patch('/', 'CustomerController@update')->name('sales.customer.update');
            $routes->delete('/', 'CustomerController@delete')->name('sales.customer.delete');

            $routes->get('/get/customer/image', 'CustomerController@getLogo')->name('sales.customer.logo');
            $routes->get('/remove/customer/image', 'CustomerController@removeLogo')->name('sales.customer.logo.remove');
            $routes->post('/add/location', 'CustomerController@addLocation')->name('sales.customer.add.location');
            $routes->post('/remove/location', 'CustomerController@removeLocation')->name('sales.customer.remove.location');
            $routes->get('/statement', 'CustomerController@statement')->name('sales.customer.statement');
            $routes->get('/export', 'CustomerController@exportStatement')->name('sales.customer.statement.export');

            $routes->group(['prefix' => 'opening'], function ($routes) {
                $routes->get('/', 'CustomerController@createOpening')->name('sales.customer.opening.create');
                $routes->get('edit/', 'CustomerController@editOpening')->name('sales.customer.opening.edit');
                $routes->post('/', 'CustomerController@storeOpening')->name('sales.customer.opening.store');
                $routes->patch('/', 'CustomerController@updateOpening')->name('sales.customer.opening.update');
            });
        });
    });

    /** sales order related routes */
    $routes->group(['prefix' => '/order'], function ($routes) {
        $routes->get('', 'OrderController@index')->name('sales.order.index');

        $routes->post('/data', 'OrderController@dataTableData')->name('sales.order.table.data');
        $routes->get('/create', 'OrderController@create')->name('sales.order.create');
        $routes->post('/', 'OrderController@store')->name('sales.order.store');
        $routes->group(['prefix' => '{order}'], function ($routes) {
            $routes->get('/export/{type?}', 'OrderController@export')->name('sales.order.export');
            $routes->get('/', 'OrderController@show')->name('sales.order.show');
            $routes->get('/edit', 'OrderController@edit')->name('sales.order.edit');
            $routes->get('/clone', 'OrderController@clone')->name('sales.order.clone');
            $routes->post('/', 'OrderController@copy')->name('sales.order.copy');
            $routes->patch('/', 'OrderController@update')->name('sales.order.update');
            $routes->delete('/', 'OrderController@delete')->name('sales.order.delete');
            $routes->post('/approve', 'OrderController@approve')->name('sales.order.approve');
            $routes->post('/convert', 'OrderController@convert')->name('sales.order.convert');
            $routes->post('/cancel', 'OrderController@cancelOrder')->name('sales.order.cancel');
            $routes->get('/print', 'OrderController@printView')->name('sales.order.print');
            $routes->post('/allow-print', 'OrderController@allowPrint')->name('sales.order.allow.print');
            $routes->post('/update-to-credit', 'OrderController@updateToCredit')->name('sales.order.update.to.credit');

            /** create invoice for sales */
            $routes->post('/create/invoice', 'InvoiceController@store')->name('sales.invoice.store');

            /** create an order using wizard format */
            $routes->group(['prefix' => 'wizard'], function ($routes) {
                /** confirm order */
                $routes->get('/confirm', 'OrderController@confirm')->name('sales.order.confirm');
                $routes->post('/do-confirm', 'OrderController@doConfirm')->name('sales.order.do.confirm');
                /** generate invoice */
                $routes->get('/invoice', 'OrderController@invoice')->name('sales.order.invoice');
                $routes->post('/generate-invoice', 'OrderController@generateInvoice')->name('sales.order.generate.invoice');
                /** record payment */
                $routes->group(['prefix' => 'invoice/{invoice}'], function ($routes) {
                    $routes->get('/payment', 'OrderController@payment')->name('sales.order.payment');
                    $routes->post('/record-payment', 'OrderController@recordPayment')->name('sales.order.record.payment');
                });
            });

        });
    });

    /** invoice related routes */
    $routes->group(['prefix' => '/invoice'], function ($routes) {
        $routes->get('', 'InvoiceController@index')->name('sales.invoice.index');
        $routes->post('/data', 'InvoiceController@dataTableData')->name('sales.invoice.table.data');
        $routes->get('search/{q?}', 'InvoiceController@search')->name('sales.invoice.search');
        $routes->get('search/{customer?}/{businessType?}/{where?}/{formatted?}/{q?}', 'InvoiceController@referenceSearch')->name('sales.invoice.reference.search');
        $routes->group(['prefix' => '{invoice}'], function ($routes) {
            $routes->get('/export/{type?}', 'InvoiceController@export')->name('sales.invoice.export');
            $routes->get('/print', 'InvoiceController@printView')->name('sales.invoice.print');
            $routes->get('/', 'InvoiceController@show')->name('sales.invoice.show');
            $routes->get('/get', 'InvoiceController@getInvoice')->name('sales.invoice.get');
            $routes->get('/edit', 'InvoiceController@edit')->name('sales.invoice.edit');
            $routes->patch('/', 'InvoiceController@update')->name('sales.invoice.update');
            $routes->delete('/', 'InvoiceController@delete')->name('sales.invoice.delete');
            $routes->post('/cancel', 'InvoiceController@cancelInvoice')->name('sales.invoice.cancel');
            $routes->post('/refund', 'InvoiceController@refundInvoice')->name('sales.invoice.refund');
            /** record payment for an invoice */
            $routes->post('/create/payment', 'PaymentController@store')->name('sales.payment.store');
            $routes->get('/{payment}/export/{type?}', 'PaymentController@export')->name('sales.payment.export');
            $routes->get('/{payment}/print', 'PaymentController@printView')->name('sales.payment.print');
        });
    });

    /** payment related routes */
    $routes->group(['prefix' => '/payment'], function ($routes) {
        $routes->get('', 'PaymentController@index')->name('sales.payment.index');
        $routes->post('/data', 'PaymentController@dataTableData')->name('sales.payment.table.data');
        $routes->group(['prefix' => '{payment}'], function ($routes) {
            $routes->get('/', 'PaymentController@show')->name('sales.payment.show');
            $routes->get('/edit', 'PaymentController@edit')->name('sales.payment.edit');
            $routes->patch('/', 'PaymentController@update')->name('sales.payment.update');
            $routes->patch('/credit', 'PaymentController@updateFromCredit')->name('sales.payment.credit.update');
            $routes->delete('/', 'PaymentController@delete')->name('sales.payment.delete');
            $routes->post('/cancel', 'PaymentController@cancelPayment')->name('sales.payment.cancel');
            $routes->post('/refund', 'PaymentController@refundPayment')->name('sales.payment.refund');
        });
    });

    /** sales estimate related routes */
    $routes->group(['prefix' => '/estimate'], function ($routes) {
        $routes->get('', 'EstimateController@index')->name('sales.estimate.index');

        $routes->post('/data', 'EstimateController@dataTableData')->name('sales.estimate.table.data');
        $routes->get('/create', 'EstimateController@create')->name('sales.estimate.create');
        $routes->post('/', 'EstimateController@store')->name('sales.estimate.store');
        $routes->group(['prefix' => '{estimate}'], function ($routes) {
            $routes->get('/export/{type?}', 'EstimateController@export')->name('sales.estimate.export');
            $routes->get('/print', 'EstimateController@printView')->name('sales.estimate.print');
            $routes->get('/', 'EstimateController@show')->name('sales.estimate.show');
            $routes->get('/edit', 'EstimateController@edit')->name('sales.estimate.edit');
            $routes->get('/clone', 'EstimateController@clone')->name('sales.estimate.clone');
            $routes->post('/', 'EstimateController@copy')->name('sales.estimate.copy');
            $routes->patch('/', 'EstimateController@update')->name('sales.estimate.update');
            $routes->delete('/', 'EstimateController@delete')->name('sales.estimate.delete');
            $routes->post('/send', 'EstimateController@send')->name('sales.estimate.send');
            $routes->post('/accept', 'EstimateController@accept')->name('sales.estimate.accept');
            $routes->post('/decline', 'EstimateController@decline')->name('sales.estimate.decline');

            $routes->group(['prefix' => '/convert'], function ($routes) {

            });
        });
    });

    /** sales inquiries related routes */
    $routes->group(['prefix' => '/inquiries', 'as' => 'sales.inquiries.'], function ($routes) {
        $routes->get('', 'InquiryController@index')->name('index');
        $routes->get('/create', 'InquiryController@create')->name('create');
        $routes->post('/', 'InquiryController@store')->name('store');
        $routes->group(['prefix' => '{inquiry}'], function ($routes) {
            $routes->get('', 'InquiryController@show')->name('show');
            $routes->get('/edit', 'InquiryController@edit')->name('edit');
            $routes->patch('', 'InquiryController@update')->name('update');
            $routes->delete('', 'InquiryController@delete')->name('delete');
        });
    });

    /** sales credits related routes */
    $routes->group(['prefix' => '/credit', 'as' => 'sales.credit.'], function ($routes) {
        $routes->get('', 'CustomerCreditController@index')->name('index');
        $routes->get('create', 'CustomerCreditController@create')->name('create');
        $routes->post('', 'CustomerCreditController@save')->name('store');
        $routes->group(['prefix' => '{credit}'], function ($routes) {
            $routes->post('/status/change', 'CustomerCreditController@statusChange')->name('status.change');
            $routes->get('/', 'CustomerCreditController@show')->name('show');
            $routes->get('/edit', 'CustomerCreditController@edit')->name('edit');
            $routes->get('/clone', 'CustomerCreditController@clone')->name('clone');
            $routes->post('/', 'CustomerCreditController@copy')->name('copy');
            $routes->patch('/', 'CustomerCreditController@update')->name('update');
            $routes->delete('/', 'CustomerCreditController@delete')->name('delete');
            $routes->get('/export/{type?}', 'CustomerCreditController@export')->name('export');
            $routes->get('/print', 'CustomerCreditController@printView')->name('print');
            // Refund Routes
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

            $routes->group(['prefix' => 'invoice', 'as' => 'invoice.'], function ($routes) {
                $routes->post('/save', 'CustomerCreditInvoiceController@savePayment')->name('save');
            });
        });
    });

    /** sales allocation related routes */
    $routes->group(['prefix' => '/allocation', 'as' => 'sales.allocation.'], function ($routes) {
        $routes->get('', 'AllocationController@index')->name('index');
        $routes->get('create', 'AllocationController@create')->name('create');
        $routes->post('', 'AllocationController@store')->name('store');
        $routes->get('products/{location}', 'AllocationController@getSalesLocationProducts')->name('products');
        $routes->get('last/day', 'AllocationController@getLastAllocationProducts')->name('last.products');
        $routes->get('vehicle/{salesLocation}', 'AllocationController@getVehicle')->name('get.vehicle');

        $routes->get('/get/drivers/{fromDate?}/{toDate?}/{q?}', 'AllocationSearchController@getDrivers')->name('get.drivers');
        $routes->get('/get/sales-location/{fromDate?}/{toDate?}/{type}/{q?}', 'AllocationSearchController@searchSalesLocation')->name('get.sales.location');
        $routes->get('/get/labours/{fromDate?}/{toDate?}/{q?}', 'AllocationSearchController@getLabours')->name('get.labours');
        $routes->get('/get/rep/{fromDate?}/{toDate?}/{q?}', 'AllocationSearchController@searchSalesRep')->name('get.rep');
        $routes->get('/get/route/{fromDate?}/{toDate?}/{q?}', 'AllocationSearchController@searchRoute')->name('get.route');

        $routes->get('/old/customers/{rep}/{route}', 'AllocationController@getOldCustomers')->name('get.old.customers');

        $routes->group(['prefix' => '{allocation}'], function ($routes) {
            $routes->get('/', 'AllocationController@show')->name('show');
            $routes->get('/complete', 'AllocationController@complete')->name('complete');
            $routes->patch('/do/complete', 'AllocationController@doComplete')->name('do.complete');

            $routes->get('get/customers/', 'AllocationController@allocationGetCustomer')->name('get.all.customer');
            $routes->get('/add/customers/', 'AllocationController@allocationAddCustomer')->name('add.customer');
            $routes->post('/store/customers/', 'AllocationController@allocationStoreCustomer')->name('store.customer');
            $routes->get('/customers/{q?}', 'AllocationController@allocationCustomer')->name('get.customers');

            $routes->get('get/products/', 'AllocationController@allocationGetProducts')->name('get.all.product');
            $routes->post('/products/', 'AllocationController@allocationAddProduct')->name('add.product');
            $routes->get('/products/{q?}', 'AllocationController@allocationProducts')->name('get.products');
            $routes->get('/get/product/{product}', 'AllocationController@getProduct')->name('product.get');

            $routes->get('/edit', 'AllocationController@edit')->name('edit');
            $routes->patch('/', 'AllocationController@update')->name('update');
            $routes->get('/change/actors', 'AllocationController@changeActors')->name('change.actors');
            $routes->patch('/update/actors', 'AllocationController@updateActors')->name('update.actors');
            $routes->delete('/', 'AllocationController@delete')->name('delete');
            $routes->get('export', 'AllocationController@export')->name('export');
            $routes->get('print', 'AllocationController@print')->name('print');
            $routes->get('sales-sheet', 'AllocationController@salesSheet')->name('sheet');
            $routes->get('/add/expense', 'AllocationController@addExpense')->name('add.expense');
            $routes->post('/store/expense', 'AllocationController@storeExpense')->name('store.expense');
            $routes->get('/get/sold-qty', 'AllocationController@getSoldQty')->name('get.sold.qty');
            $routes->patch('/update/sold-qty', 'AllocationController@updateSoldQty')->name('update.sold.qty');
            $routes->get('/get/actual-qty', 'AllocationController@getActualQty')->name('get.actual.qty');
            $routes->patch('/restore/actual-qty', 'AllocationController@updateActualQty')->name('restore.actual.qty');
            $routes->get('/mobile/login', 'AllocationController@mobileLogin')->name('mobile.login');
            $routes->patch('/allow/mobile/login', 'AllocationController@allowMobileLogin')->name('allow.mobile.login');
            $routes->get('/allocate/products', 'AllocationController@allocateProducts')->name('allocate.products');
            $routes->post('/store/products', 'AllocationController@storeProducts')->name('store.products');

            /** attach credit orders */
            $routes->get('/get/credit/orders/', 'AllocationController@getAllocationCreditOrder')->name('get.credit.order');
            $routes->get('credit/orders/', 'AllocationController@getCreditOrder')->name('credit.order');
            $routes->post('credit/orders/', 'AllocationController@attachCreditBill')->name('attach.credit.order');

            /** attach phone orders */
            $routes->get('/get/phone/orders/', 'AllocationController@getAllocationPhoneOrders')->name('get.phone.order');
            $routes->get('phone/orders/', 'AllocationController@getPhoneOrders')->name('phone.order');
            $routes->post('phone/orders/', 'AllocationController@attachPhoneOrders')->name('attach.phone.order');

            $routes->get('credit/orders/export', 'AllocationExportController@creditOrderExport')->name('credit.order.export');
            $routes->get('export/customer', 'AllocationExportController@exportCustomer')->name('customer.export');
            $routes->get('export/product', 'AllocationExportController@exportProducts')->name('product.export');
            $routes->get('export/product-history', 'AllocationExportController@exportProductHistory')->name('product.history.export');
            $routes->patch('/status/{status}', 'AllocationController@statusChange')->name('status.change');
            $routes->patch('/allow/mobile/login', 'AllocationController@allowMobileLogin')->name('allow.mobile.login');

            $routes->group(['prefix' => 'handover/{handover}', 'as' => 'handover.'], function ($routes) {
                $routes->get('/', 'HandOverController@approval')->name('approval');
                $routes->post('/', 'HandOverController@save')->name('save');
                $routes->get('/edit', 'HandOverController@edit')->name('edit');
                $routes->patch('/update', 'HandOverController@update')->name('update');
            });

            $routes->group(['prefix' => 'item/{item}', 'as' => 'item.'], function ($routes) {
                $routes->get('/restore/stock', 'AllocationController@restoreStock')->name('restore.stock');
                $routes->patch('/do/restore/stock', 'AllocationController@doRestoreStock')->name('do.restore.stock');
            });

            $routes->get('/export/sales-sheet', 'AllocationController@exportSalesSheet')->name('export.sales.sheet');
            $routes->get('/export/credit-orders', 'AllocationController@exportCreditOrders')->name('export.credit.orders');

        });
    });

    /** cash sales related routes */
    $routes->group(['prefix' => '/cash-sales', 'as' => 'cash.sales.'], function ($routes) {
        $routes->get('', 'CashSalesController@index')->name('index')->middleware('can:index,App\SalesOrder');
        $routes->get('create', 'CashSalesController@create')->name('create')->middleware('can:create,App\SalesOrder');
        $routes->get('cancel/{order}', 'CashSalesController@cancel')->name('cancel')->middleware('can:edit,order');
        $routes->post('', 'CashSalesController@store')->name('store')->middleware('can:store,App\SalesOrder');
        $routes->post('barcode', 'CashSalesController@productForBarcode')
            ->name('barcode.product')->middleware('can:index,App\SalesOrder');
        $routes->get('handover', 'CashSalesController@handOverData')
            ->name('get.handover')->middleware('can:index,App\SalesOrder');
        $routes->post('handover/save', 'CashSalesController@handOverSave')
            ->name('handover.save')->middleware('can:store,App\SalesOrder');
        $routes->get('get/product/{allocation}/{product}', 'CashSalesController@allocationProduct')
            ->name('get.product')->middleware('can:index,App\SalesOrder');
        $routes->get('/search/{ids?}/{q?}', 'CashSalesController@searchSalesProduct')
            ->name('product.search')->middleware('can:index,App\SalesOrder');
    });

    /** sales return related routes */
    $routes->group(['prefix' => '/return', 'as' => 'sales.return.'], function ($routes) {
        $routes->get('', 'ReturnController@index')->name('index');
        $routes->get('create', 'ReturnController@create')->name('create');
        $routes->post('', 'ReturnController@store')->name('store');
        $routes->group(['prefix' => '{return}'], function ($routes) {
            $routes->get('/', 'ReturnController@show')->name('show');
        });
    });

    $routes->group(['prefix' => '/shortage', 'as' => 'sales.shortage.'], function ($routes) {
        $routes->get('', 'ShortageController@index')->name('index');
        $routes->group(['prefix' => '{shortage}'], function ($routes) {
            $routes->get('/export', 'ShortageController@export')->name('export');
            $routes->post('/approve', 'ShortageController@approve')->name('approve');
            $routes->post('/reject', 'ShortageController@reject')->name('reject');
        });
    });

    $routes->group(['prefix' => '/excess', 'as' => 'sales.excess.'], function ($routes) {
        $routes->get('', 'ExcessController@index')->name('index');
        $routes->group(['prefix' => '{excess}'], function ($routes) {
            $routes->get('/export', 'ExcessController@export')->name('export');
        });
    });

    $routes->group(['prefix' => '/daily-stock', 'as' => 'daily.stock.'], function ($routes) {
        $routes->get('', 'DailyStockController@index')->name('index');
        $routes->get('/create', 'DailyStockController@create')->name('create');
        $routes->post('/', 'DailyStockController@store')->name('store');
        $routes->get('/shop/create', 'DailyStockController@createShop')->name('create.shop');
        $routes->post('/shop/store', 'DailyStockController@storeShop')->name('store.shop');
        $routes->group(['prefix' => '{dailyStock}'], function ($routes) {
            $routes->get('/export', 'DailyStockController@export')->name('export');
            $routes->get('/', 'DailyStockController@show')->name('show');
            $routes->get('/edit', 'DailyStockController@edit')->name('edit');
            $routes->patch('/', 'DailyStockController@update')->name('update');
            $routes->get('/shop/edit', 'DailyStockController@editShop')->name('edit.shop');
            $routes->patch('/shop/update', 'DailyStockController@updateShop')->name('update.shop');
            $routes->get('/get-items', 'DailyStockController@getDailyStockProduct')->name('get.items');
            $routes->post('/update-items', 'DailyStockController@updateDailyStockProducts')->name('update.items');
            $routes->patch('/status/{status}', 'DailyStockController@statusUpdate')->name('status.update');

            $routes->get('/change-route', 'DailyStockController@changeRoute')->name('change.route');
            $routes->post('/do-change-route', 'DailyStockController@doChangeRoute')->name('do.change.route');

            $routes->get('/change-rep', 'DailyStockController@changeRep')->name('change.rep');
            $routes->post('/do-change-rep', 'DailyStockController@doChangeRep')->name('do.change.rep');
        });
    });

    $routes->group(['prefix' => '/stock-excess', 'as' => 'sales.stock.excess.'], function ($routes) {
        $routes->get('', 'StockExcessController@index')->name('index');
        $routes->group(['prefix' => '{stock}'], function ($routes) {
            $routes->get('/export', 'StockExcessController@export')->name('export');
            $routes->get('/', 'StockExcessController@show')->name('show');
            $routes->post('/approve', 'StockExcessController@approve')->name('approve');
            $routes->post('/reject', 'StockExcessController@reject')->name('reject');
            $routes->group(['prefix' => '/item/{stockItem}'], function ($routes) {
                $routes->get('/approve', 'StockExcessController@approve')->name('approve');
                $routes->patch('/update/approve', 'StockExcessController@doApprove')->name('do.approve');
                $routes->get('/reject', 'StockExcessController@reject')->name('reject');
                $routes->patch('/update/reject', 'StockExcessController@doReject')->name('do.reject');
            });
        });
    });

    $routes->group(['prefix' => '/stock-shortage', 'as' => 'sales.stock.shortage.'], function ($routes) {
        $routes->get('', 'StockShortageController@index')->name('index');
        $routes->group(['prefix' => '{stock}'], function ($routes) {
            $routes->get('/export', 'StockShortageController@export')->name('export');
            $routes->get('/', 'StockShortageController@show')->name('show');
            $routes->post('/approve', 'StockShortageController@approve')->name('approve');
            $routes->post('/reject', 'StockShortageController@reject')->name('reject');
            $routes->group(['prefix' => '/item/{stockItem}'], function ($routes) {
                $routes->get('/approve', 'StockShortageController@approve')->name('approve');
                $routes->patch('/update/approve', 'StockShortageController@doApprove')->name('do.approve');
                $routes->get('/reject', 'StockShortageController@reject')->name('reject');
                $routes->patch('/update/reject', 'StockShortageController@doReject')->name('do.reject');
            });
        });
    });

    $routes->group(['prefix' => '/cash-sales-customer', 'as' => 'cash.sales.customer.'], function ($routes) {
        $routes->post('/', 'CashSalesController@storeCustomer')->name('store');
    });

    /** credit orders */
    $routes->get('credit-orders', 'OrderController@creditOrders')->name('sales.credit.orders');
    $routes->get('credit-orders/export', 'OrderController@creditOrdersExport')->name('sales.credit.orders.export');

    $routes->group(['prefix' => '/distance', 'as' => 'sales.distance.'], function ($routes) {
        $routes->post('order/{order}', 'DistanceUpdateController@salesOrderDistance')->name('order.update');
        $routes->post('customer/{customer}', 'DistanceUpdateController@allocationCustomerDistance')->name('customer.update');
    });
    $routes->get('/get/customer/{route}', 'AllocationController@getCustomer')->name('sales.get.customer.by.route');
    $routes->get('/get/products/{route}', 'AllocationController@getProducts')->name('sales.get.products.by.route');
    $routes->get('/settlement/due', 'SalesController@settlementDue')->name('sales.summary.settlement.due');
    $routes->get('/top/customers', 'SalesController@topCustomers')->name('sales.summary.top.customer');
    $routes->get('/top/products', 'SalesController@topProduct')->name('sales.summary.top.products');
    $routes->get('/top/sales-rep', 'SalesController@topSalesRep')->name('sales.summary.top.sales.rep');

    //$routes->get('/customer/{company}/customer/search/{q?}/', 'CustomerController@searchByCompany')->name('sales.customer.by.company.search');
    $routes->get('/customer/{route}/customer/search/{q?}/', 'CustomerController@searchByRoute')->name('sales.customer.by.route.search');

    /** sales summary related routes */
    $routes->get('{model}/{take?}/{with?}/{where?}/{field?}', 'SalesController@summary')->name('sales.summary.index');

});
