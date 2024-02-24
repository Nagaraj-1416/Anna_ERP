<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;

class FinanceController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Finance'],
        ];
        return view('finance.index', compact('breadcrumb'));
    }

}
