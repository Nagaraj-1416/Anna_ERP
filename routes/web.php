<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['prefix' => '/', 'middleware' => ['web']], function ($routes) {
    $routeDirectory = __DIR__ . '/auth';
    $routeFiles = array_values(array_filter(scandir($routeDirectory), function ($name) {
        return strpos($name, '.php');
    }));
    foreach ($routeFiles as $file){
        $filePath = $routeDirectory . '/' . $file;
        require_once($filePath);
    }
});

// Auth::routes();

// Route::group(['prefix' => 'face-verification', 'middleware' => ['web', 'auth', 'nonWorkingHrs', 'workHours'], 'namespace' => 'Auth'], function ($routes) {
//     $routes->get('/', 'TFAController@index')->name('auth.tfa.index');
//     $routes->post('/', 'TFAController@verify')->name('auth.tfa.verify');
// });

// Route::group(['prefix' => '/', 'middleware' => ['web', 'auth', 'tfa', 'nonWorkingHrs', 'workHours']], function ($routes) {
//     $routeDirectory = __DIR__ . '/auth';
//     $routeFiles = array_values(array_filter(scandir($routeDirectory), function ($name) {
//         return strpos($name, '.php');
//     }));
//     foreach ($routeFiles as $file){
//         $filePath = $routeDirectory . '/' . $file;
//         require_once($filePath);
//     }
//     $routes->get('app-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
//     // this is for test
//     $routes->group(['prefix' => 'test'], function ($routes) {
//         $routes->get('{method}', 'TestController@index')->name('admin.test');
//     });
// });
