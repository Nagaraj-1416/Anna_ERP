<?php
Route::group(['namespace' => 'General'], function ($routes) {

    $routes->group(['prefix' => '/contact-person', 'as' => 'contact.person.'], function ($routes) {
        $routes->post('store/{model}/{modelId}', 'ContactPersonController@store')->name('store');
        $routes->post('list/{model}/{modelId}', 'ContactPersonController@getData')->name('list');
        $routes->get('edit/{contactPerson}', 'ContactPersonController@edit')->name('edit');
        $routes->patch('{contactPerson}', 'ContactPersonController@update')->name('update');
        $routes->delete('{contactPerson}/{model}/{modelId}', 'ContactPersonController@delete')->name('delete');
    });

    /** documents related routes */
    $routes->group(['prefix' => 'document'], function ($routes) {
        $routes->group(['prefix' => '{document}'], function ($routes) {
            $routes->get('/', 'DocumentController@show')->name('document.show');
            $routes->get('/download', 'DocumentController@download')->name('document.download');
            $routes->delete('/delete', 'DocumentController@delete')->name('document.delete');
        });
        $routes->post('upload/{model}/{modelId}', 'DocumentController@upload')->name('document.upload');
        $routes->get('/{model}/{modelId}', 'DocumentController@index')->name('document.index');
    });

    /** comments related routes */
    $routes->group(['prefix' => 'comment'], function ($routes) {
        $routes->group(['prefix' => '{model}'], function ($routes) {
            $routes->get('{modelId}', 'CommentController@getComments')->name('comment.get');
            $routes->post('/create', 'CommentController@create')->name('comment.create');
        });
        $routes->post('/update', 'CommentController@update')->name('comment.update');
        $routes->post('/delete', 'CommentController@delete')->name('comment.delete');
    });

    /** logs related routes */
    $routes->group(['prefix' => 'log'], function ($routes) {
        $routes->get('/log/{model}/{modelId}', 'LogController@getLogs')->name('log.get');
    });

    /** allowance related routes */
    $routes->group(['prefix' => 'allowance', 'as' => 'allowance.'], function ($routes) {
        $routes->post('/create/{modal}/{modalId}', 'AllowanceController@create')->name('create');
        $routes->post('/edit/{allowance}', 'AllowanceController@edit')->name('edit');
    });

    /** profile related routes */
    $routes->group(['prefix' => 'profile'], function ($routes) {
        $routes->get('/', 'ProfileController@index')->name('profile.index');
        $routes->patch('/update', ['uses' => 'ProfileController@update', 'as' => 'profile.update']);
        $routes->get('/get/profile/image', 'ProfileController@getProfileImage')->name('profile.image');
        $routes->get('/remove/profile/image', 'ProfileController@removeProfileImage')->name('profile.image.remove');
    });

    /** search related routes */
    $routes->group(['prefix' => 'search'], function ($routes) {
        $routes->get('', 'SearchController@index')->name('search.result');
    });

    $routes->group(['prefix' => 'map'], function ($routes) {
        $routes->get('', 'MapController@index')->name('map.index');
    });

    /** transaction related routes */
    $routes->group(['prefix' => '/transaction'], function ($routes) {
        $routes->get('/', 'TransactionController@index')->name('finance.trans.index')->middleware('can:index,App\Transaction');
        $routes->get('/manual', 'TransactionController@manualTrans')->name('finance.manual.trans.index')->middleware('can:index,App\Transaction');
        $routes->get('/create', 'TransactionController@create')->name('finance.trans.create')->middleware('can:create,App\Transaction');
        $routes->post('/', 'TransactionController@store')->name('finance.trans.store')->middleware('can:store,App\Transaction');
        $routes->group(['prefix' => '{trans}'], function ($routes) {
            $routes->get('/', 'TransactionController@show')->name('finance.trans.show')->middleware('can:show,trans');
            $routes->get('/edit', 'TransactionController@edit')->name('finance.trans.edit')->middleware('can:edit,trans');
            $routes->patch('/', 'TransactionController@update')->name('finance.trans.update')->middleware('can:update,trans');
            $routes->delete('/', 'TransactionController@delete')->name('finance.trans.delete')->middleware('can:delete,trans');
            $routes->get('/export/{type?}', 'TransactionController@export')->name('finance.trans.export')->middleware('can:show,trans');
            $routes->get('/print', 'TransactionController@printView')->name('finance.trans.print')->middleware('can:show,trans');
        });
    });

});
