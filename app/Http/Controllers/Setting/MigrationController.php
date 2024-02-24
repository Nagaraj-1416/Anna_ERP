<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\MigrationRequest;
use Maatwebsite\Excel\Facades\Excel;

class MigrationController extends Controller
{
    /**
     *
     */
    public function index()
    {
        $breadcrumb = $this->breadcrumbs('index');
        return view('settings.migration.index', compact('breadcrumb'));
    }

    public function products()
    {
        $breadcrumb = $this->breadcrumbs('products');
        return view('settings.migration.products', compact('breadcrumb'));
    }

    public function migrateProducts(MigrationRequest $request)
    {
        if($request->hasFile('source_file')){
            $path = $request->file('source_file')->getRealPath();
            $data = \Excel::load($path)->get();


        }
    }

    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'settings', 'route' => 'setting.index'],
                ['text' => 'Data Administration'],
            ],
            'products' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'settings', 'route' => 'setting.index'],
                ['text' => 'Data Administration', 'route' => 'setting.migrate.index'],
                ['text' => 'Products'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
