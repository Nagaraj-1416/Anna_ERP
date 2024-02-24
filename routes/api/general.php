<?php
Route::group(['namespace' => 'General'], function ($routes) {
    $routes->group(['prefix' => '/user',], function ($routes) {
        $routes->get('/', 'UserController@index');
        $routes->get('/image', 'UserController@image');
    });

    $routes->group(['prefix' => '/mata',], function ($routes) {
        $routes->get('/', 'GeneralController@mata');
    });

    $routes->group(['prefix' => '/dashboard',], function ($routes) {
        $routes->get('/', 'DashboardController@index');
    });

    $routes->group(['prefix' => '/search',], function ($routes) {
        $routes->get('/business-type/{q?}', 'SearchController@businessType');
        $routes->get('/supplier/{q?}', 'SearchController@supplier');
        $routes->get('/product/{q?}/{type?}', 'SearchController@product');
        $routes->get('/store/{q?}', 'SearchController@store');
        $routes->get('/route/{q?}', 'SearchController@route');
        $routes->get('/route-location/{route}/{q?}', 'SearchController@routeLocation');
        $routes->get('/salutation/{q?}', 'SearchController@salutation');
        $routes->get('/country/{q?}', 'SearchController@country');
        $routes->get('/customer/{q?}', 'SearchController@customer');
        $routes->get('/rep/{q?}', 'SearchController@rep');
        $routes->get('/unit-type/{q?}', 'SearchController@unitType');
        $routes->get('/price-book/{q?}', 'SearchController@priceBook');
        $routes->get('/bank/{q?}', 'SearchController@bank');
        $routes->get('/deposited-to/{q?}', 'SearchController@depositedTo');
        $routes->get('/expense-type/{q?}', 'SearchController@expenseType');
    });

    $routes->group(['prefix' => '/contact-person',], function ($routes) {
        $routes->group(['prefix' => '{model}/{modelId}',], function ($routes) {
            $routes->get('/', 'ContactPersonController@index');
            $routes->post('/', 'ContactPersonController@store');
        });
        $routes->group(['prefix' => '{contactPerson}',], function ($routes) {
            $routes->get('/', 'ContactPersonController@show');
            $routes->patch('/', 'ContactPersonController@update');
            $routes->delete('/', 'ContactPersonController@delete');
        });
    });
});
