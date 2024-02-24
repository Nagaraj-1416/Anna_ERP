<?php

namespace App\Http\Controllers\General;

use App\Repositories\General\SearchRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    protected $search;

    public function __construct(SearchRepository $search)
    {
        $this->search = $search;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $request = request();
        $query = $request->input('keyword');
        if (request()->ajax()) {
            $data = $this->search->searchAll($request);
            return response()->json($data);
        }
        $breadcrumb = $this->breadcrumbs('index');
        return view('general.search.index', compact('breadcrumb', 'query'));
    }

    public function breadcrumbs(string $method): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Search Results'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}
