<?php
Route::group(['namespace' => 'Setting', 'prefix' => 'setting'], function ($routes) {
    $routes->group(['prefix' => '/products',], function ($routes) {
        $routes->get('/{product}', 'ProductController@show');
    });

    $routes->group(['prefix' => '/price-books',], function ($routes) {
        $routes->get('/{priceBook?}', 'PriceBookController@show');
    });

    $routes->group(['prefix' => '/route',], function ($routes) {
        $routes->post('pick-next/', 'RouteController@pickNext');
        $routes->get('/next-day-route', 'RouteController@nextDay');
        $routes->get('/{route}', 'RouteController@show');
    });
});
