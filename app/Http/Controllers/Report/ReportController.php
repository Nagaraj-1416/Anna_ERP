<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Reports'],
        ];
        return view('report.index', compact('breadcrumb'));
    }

}
