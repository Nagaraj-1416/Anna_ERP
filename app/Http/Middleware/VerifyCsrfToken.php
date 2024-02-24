<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'stripe/*',
        'http://example.com/foo/bar',
        'http://example.com/foo/*',
        'http://127.0.0.1:8000/setting/department',
        'http://127.0.0.1:8000/setting/department/*',
        'http://127.0.0.1:8000/setting/company',
        'http://127.0.0.1:8000/setting/company/*',
        'http://127.0.0.1:8000/setting/production-unit',
        'http://127.0.0.1:8000/setting/production-unit/*',
        'http://127.0.0.1:8000/setting/store',
        'http://127.0.0.1:8000/setting/store/*',
        'http://127.0.0.1:8000/setting/sales-location',
        'http://127.0.0.1:8000/setting/sales-location/*',
        'http://127.0.0.1:8000/setting/product',
        'http://127.0.0.1:8000/setting/product/*',
        'http://127.0.0.1:8000/setting/price-book',
        'http://127.0.0.1:8000/setting/price-book/*',
        'http://127.0.0.1:8000/setting/vehicle',
        'http://127.0.0.1:8000/setting/vehicle/*',
        'http://127.0.0.1:8000/setting/route',
        'http://127.0.0.1:8000/setting/route/*',
        'http://127.0.0.1:8000/setting/product/category/',
        'http://127.0.0.1:8000/setting/product/category/*'
    ];
}
