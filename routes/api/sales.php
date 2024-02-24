<?php
Route::group(['namespace' => 'Sales'], function ($routes) {
    $routes->group(['prefix' => '/customers',], function ($routes) {
        $routes->get('/', 'CustomerController@index');
        $routes->get('/for-today', 'CustomerController@todayIndex');
        $routes->post('/', 'CustomerController@store');
        $routes->group(['prefix' => '{customer}',], function ($routes) {
            $routes->get('/', 'CustomerController@show');
            $routes->get('/orders', 'CustomerController@orders');
            $routes->get('/invoices', 'CustomerController@invoices');
            $routes->get('/payments', 'CustomerController@payments');
            $routes->patch('/', 'CustomerController@update');
            $routes->patch('/location', 'CustomerController@updateLocation');
            $routes->delete('/', 'CustomerController@delete');
            $routes->group(['prefix' => 'not-visited'], function ($routes) {
                $routes->post('/', 'CustomerController@notVisit');
            });
        });
    });
    $routes->group(['prefix' => '/sales',], function ($routes) {
        $routes->group(['prefix' => '/orders',], function ($routes) {
            $routes->get('/', 'OrderController@index');
            $routes->get('/for-today', 'OrderController@todayIndex');
            $routes->post('/', 'OrderController@store');
            $routes->group(['prefix' => '{order}',], function ($routes) {
                $routes->patch('/is-printed', 'OrderController@isPrinted');
                $routes->patch('/update-status', 'OrderController@updateStatus');
                $routes->get('/', 'OrderController@show');
                $routes->patch('/', 'OrderController@update');
                $routes->post('/cancel', 'OrderController@cancel');
                $routes->delete('/', 'OrderController@delete');
            });
        });

        $routes->group(['prefix' => '/invoices',], function ($routes) {
            $routes->get('/', 'InvoiceController@index');
            $routes->get('/for-today', 'InvoiceController@todayIndex');
            $routes->post('{order}/', 'InvoiceController@store');
            $routes->group(['prefix' => '{invoice}',], function ($routes) {
                $routes->get('/', 'InvoiceController@show');
                $routes->patch('/', 'InvoiceController@update');
                $routes->delete('/', 'InvoiceController@delete');
                $routes->post('/cancel', 'InvoiceController@cancel');
                $routes->post('/refund', 'InvoiceController@refund');
            });
        });

        $routes->group(['prefix' => '/payments',], function ($routes) {
            $routes->get('/', 'PaymentController@index');
            $routes->get('/for-today', 'PaymentController@todayIndex');
            $routes->post('{invoice}/', 'PaymentController@store');
            $routes->group(['prefix' => '{payment}',], function ($routes) {
                $routes->get('/', 'PaymentController@show');
                $routes->patch('/', 'PaymentController@update');
                $routes->delete('/', 'PaymentController@delete');
                $routes->post('/cancel', 'PaymentController@cancel');
                $routes->post('/refund', 'PaymentController@refund');
            });
        });

        $routes->group(['prefix' => '/estimates',], function ($routes) {
            $routes->get('/', 'EstimateController@index');
            $routes->get('/for-today', 'EstimateController@todayIndex');
            $routes->post('/', 'EstimateController@store');
            $routes->group(['prefix' => '{estimate}',], function ($routes) {
                $routes->get('/', 'EstimateController@show');
                $routes->patch('/', 'EstimateController@update');
                $routes->delete('/', 'EstimateController@delete');
            });
        });

        $routes->group(['prefix' => '/handover',], function ($routes) {
            $routes->get('/', 'HandOverController@details');
            $routes->post('/', 'HandOverController@store');
        });

        $routes->group(['prefix' => '/return/',], function ($routes) {
            $routes->patch('/is-printed/{return}', 'ReturnController@isPrinted');
            $routes->get('/{customer?}', 'ReturnController@index');
            $routes->post('/{customer}', 'ReturnController@store');
        });

        $routes->get('/products', 'OrderController@salesProducts');

        $routes->group(['prefix' => '/expense',], function ($routes) {
            $routes->get('/for-today', 'ExpenseController@todayIndex');
            $routes->post('/', 'ExpenseController@store');
            $routes->group(['prefix' => '{expense}',], function ($routes) {
                $routes->delete('/', 'ExpenseController@delete');
            });
        });

        $routes->group(['prefix' => '/confirm-stock',], function ($routes) {
            $routes->patch('/', 'HandOverController@confirmStock');
        });

        $routes->group(['prefix' => '/cheque-payments',], function ($routes) {
            $routes->get('/', 'ChequePaymentController@index');
            $routes->post('{cheque}/', 'ChequePaymentController@store');
            $routes->group(['prefix' => '{payment}',], function ($routes) {
                $routes->patch('/is-printed', 'ChequePaymentController@isPrinted');
                $routes->get('/', 'ChequePaymentController@show');
                $routes->patch('/', 'ChequePaymentController@update');
                $routes->delete('/', 'ChequePaymentController@delete');
            });
        });

    });
});
