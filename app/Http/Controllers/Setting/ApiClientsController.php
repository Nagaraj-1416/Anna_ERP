<?php

namespace App\Http\Controllers\Setting;

use App\OauthClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiClientsController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Settings', 'route' => 'setting.index'],
            ['text' => 'API Clients'],
        ];
        $oAuthClients = OauthClient::all();
        return view('settings.api-clients.index', compact('breadcrumb', 'oAuthClients'));
    }
}
