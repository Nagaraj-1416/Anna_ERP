<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/date', 'General\GeneralController@date');
Route::group(['prefix' => '/tfa'], function ($routes) {
    $routes->post('/verify', 'Auth\TFAController@verify');
});

Route::group(['prefix' => '/user',], function ($routes) {
    $routes->get('/image/{user}', 'General\UserController@getUserImage');
});

Route::group(['prefix' => '/',], function ($routes) {
    $routeDirectory = __DIR__ . '/api';
    $routeFiles = array_values(array_filter(scandir($routeDirectory), function ($name) {
        return strpos($name, '.php');
    }));
    foreach ($routeFiles as $file) {
        $filePath = $routeDirectory . '/' . $file;
        require_once($filePath);
    }
});
