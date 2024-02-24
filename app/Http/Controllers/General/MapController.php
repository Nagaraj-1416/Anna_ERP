<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function index()
    {
        $startLat = \request()->input('startLat');
        $startLng = \request()->input('startLng');
        $startInfo = \request()->input('startInfo');

        $endLat = \request()->input('endLat');
        $endLng = \request()->input('endLng');
        $endInfo = \request()->input('endInfo');
        $startInfo = json_decode($startInfo, 1);
        $endInfo = json_decode($endInfo, 1);
        $breadcrumb = [];
        return view('general.map.index', compact('startLat', 'startLng',
            'breadcrumb', 'startInfo', 'endLat', 'endLng', 'endInfo'));
    }
}
