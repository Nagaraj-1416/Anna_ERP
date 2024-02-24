<?php
Route::group(['prefix' => '/stock-summary', 'namespace' => 'Stock'], function ($routes) {
    $routes->get('', 'StockSummaryController@index')->name('stock.summary.index');
    $routes->get('low/stock', 'StockSummaryController@lowStockItems')->name('stock.summary.low.stock');
    $routes->get('out/of/stock', 'StockSummaryController@outOfStock')->name('stock.summary.out.of.stock');

    /** stock related routes */
    $routes->group(['prefix' => '/stock'], function ($routes) {
        $routes->get('', 'StockController@index')->name('stock.index');
        $routes->post('/data', 'StockController@dataTableData')->name('stock.table.data');
        $routes->get('/create', 'StockController@create')->name('stock.create');
        $routes->post('/', 'StockController@store')->name('stock.store');
        $routes->get('search/{q?}', 'StockController@search')->name('stock.search');
        $routes->get('history', 'StockHistoryController@index')->name('stock.history');
        $routes->get('history-data', 'StockHistoryController@historyData')->name('stock.history.data');
        $routes->group(['prefix' => '{stock}'], function ($routes) {
            $routes->get('/', 'StockController@show')->name('stock.show');
            $routes->get('/edit', 'StockController@edit')->name('stock.edit');
            $routes->patch('/', 'StockController@update')->name('stock.update');
            $routes->get('/add-damage', 'StockController@addDamage')->name('stock.add.damage');
            $routes->post('/store-damage', 'StockController@storeDamage')->name('stock.store.damage');
            $routes->get('/review-stock', 'StockController@reviewStock')->name('stock.add.review');
            $routes->post('/do-review-stock', 'StockController@doReviewStock')->name('stock.store.review');
            $routes->delete('/', 'StockController@delete')->name('stock.delete');
            $routes->post('/undo-purchase', 'StockController@undoPurchase')->name('stock.undo.purchase');
            $routes->get('export/history', 'StockController@exportHistory')->name('stock.history.export');
        });
    });

    $routes->group(['prefix' => '/van-stock', 'as' => 'stock.van.'], function ($routes) {
        $routes->get('', 'VanStockController@index')->name('index');
    });

    $routes->group(['prefix' => '/shop-stock', 'as' => 'stock.shop.'], function ($routes) {
        $routes->get('', 'ShopStockController@index')->name('index');
    });

    $routes->group(['prefix' => '/store-stock', 'as' => 'stock.store.'], function ($routes) {
        $routes->get('', 'StoreStockController@index')->name('index');
        $routes->get('export', 'StoreStockController@export')->name('export');
    });

    $routes->group(['prefix' => '/stock-out', 'as' => 'stock.out.'], function ($routes) {
        $routes->get('/create', 'StockController@stockOut')->name('create');
        $routes->post('/', 'StockController@doStockOut')->name('store');
    });

    $routes->group(['prefix' => '/damaged-stock', 'as' => 'stock.damaged.'], function ($routes) {
        $routes->get('', 'DamageStockController@index')->name('index');
    });

    $routes->group(['prefix' => '/stock-review', 'as' => 'stock.review.'], function ($routes) {
        $routes->get('', 'StockReviewController@index')->name('index');
        $routes->group(['prefix' => '{store}'], function ($routes) {
            $routes->get('/create', 'StockReviewController@create')->name('create');
            $routes->post('/', 'StockReviewController@store')->name('store');
        });
        $routes->group(['prefix' => '{review}'], function ($routes) {
            $routes->get('/', 'StockReviewController@show')->name('show');
            $routes->patch('/approve', 'StockReviewController@approve')->name('approve');
            $routes->delete('/delete', 'StockReviewController@delete')->name('delete');
        });
    });

    $routes->group(['prefix' => '/stock-transfer', 'as' => 'stock.transfer.'], function ($routes) {
        $routes->get('', 'StockController@transferList')->name('index');
        $routes->group(['prefix' => '{store}'], function ($routes) {
            $routes->get('/create', 'StockController@transfer')->name('create');
            $routes->post('/', 'StockController@doTransfer')->name('store');
        });
    });

    $routes->group(['prefix' => '/trans', 'as' => 'stock.trans.'], function ($routes) {
        $routes->get('', 'StockController@stockTrans')->name('index');
    });

    $routes->group(['prefix' => '/purchased-history', 'as' => 'stock.purchased.history.'], function ($routes) {
        $routes->get('', 'StockController@purchasedHistories')->name('index');
    });

    $routes->group(['prefix' => '/stock-return', 'as' => 'stock.return.'], function ($routes) {
        $routes->get('', 'StockController@returnList')->name('index');
        $routes->group(['prefix' => '{store}'], function ($routes) {
            $routes->get('/create', 'StockController@return')->name('create');
            $routes->post('/', 'StockController@doReturn')->name('store');
        });
    });

});
