<?php

namespace App\Http\Controllers\Setting;

use App\Company;
use App\Exports\RouteCustomerExport;
use App\Exports\RouteProductExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\AssignProductRequest;
use App\Http\Requests\Setting\RouteStoreRequest;
use App\Product;
use App\Repositories\Settings\RouteRepository;
use App\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class RouteController extends Controller
{
    protected $route;

    /**
     * RouteController constructor.
     * @param RouteRepository $route
     */
    public function __construct(RouteRepository $route)
    {
        $this->route = $route;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if (\request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $routes = $this->route->grid();
            return response()->json($routes);
        }
        $this->authorize('index', $this->route->getModel());
        $breadcrumb = $this->route->breadcrumbs('index');
        // if (\request()->ajax()) {
        //     $routes = $this->route->grid();
        //     return response()->json($routes);
        // }
        return view('settings.route.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()) {
            return $this->route->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->route->getModel());
        $breadcrumb = $this->route->breadcrumbs('create');
        return view('settings.route.create', compact('breadcrumb'));
    }

    /**
     * @param RouteStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(RouteStoreRequest $request)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $route=$this->route->save($request);
            return response()->json($route);
        }
        $this->authorize('store', $this->route->getModel());
        $this->route->save($request);
        return redirect()->route('setting.route.index');
    }

    /**
     * @param Route $route
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Route $route)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($route);
        }
        $this->authorize('show', $this->route->getModel());
        $breadcrumb = $this->route->breadcrumbs('show', $route);
        $products = $route->products;
        $customers = $route->customers;
        return view('settings.route.show', compact('breadcrumb', 'route', 'products', 'customers'));
    }

    /**
     * @param Route $route
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Route $route)
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            $route->company_id = $route->company ? $route->company->id : '';
            return response()->json($route);
        }
        $this->authorize('edit', $this->route->getModel());
        $breadcrumb = $this->route->breadcrumbs('edit', $route);
        return view('settings.route.edit', compact('breadcrumb', 'route'));
    }

    /**
     * @param RouteStoreRequest $request
     * @param Route $route
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(RouteStoreRequest $request, Route $route)
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $single_route=$this->route->update($request, $route);
            return response()->json($single_route);
        }
        $this->authorize('update', $this->route->getModel());
        $this->route->update($request, $route);
        return redirect()->route('setting.route.index');
    }

    /**
     * @param Route $route
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Route $route)
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $response = $this->route->delete($route);
            return response()->json($response);
        }
        $this->authorize('delete', $this->route->getModel());
        $response = $this->route->delete($route);
        return response()->json($response);
    }

    /**
     * @param Route $route
     * @param null $q
     * @return JsonResponse
     */
    public function searchLocation(Route $route, $q = null)
    {
        if ($q == null) {
            $locations = $route->locations()->get(['id', 'name', 'code'])->toArray();
        } else {
            $locations = $route->locations()->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $locations = array_map(function ($obj) {
            return ["name" => $obj['name'] . ' (' . $obj['code'] . ')', "value" => $obj['id']];
        }, $locations);
        return response()->json(["success" => true, "results" => $locations]);
    }

    public function searchByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $routes = $company->routes()->get(['id', 'name', 'code'])->toArray();
        } else {
            $routes = $company->routes()->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $routes = array_map(function ($obj) {
            return ["name" => $obj['name'] . ' (' . $obj['code'] . ')', "value" => $obj['id']];
        }, $routes);
        return response()->json(["success" => true, "results" => $routes]);
    }

    /**
     * @param Route $route
     * @return JsonResponse
     */
    public function getRoute(Route $route)
    {
        return response()->json([$route]);
    }

    /**
     * @param AssignProductRequest $request
     * @param Route $route
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignProduct(AssignProductRequest $request, Route $route)
    {
        $this->route->setModel($route);
        $this->route->assignProduct($request);
        alert()->success('Products assigned successfully', 'Success')->persistent();
        return redirect()->route('setting.route.index');
    }

    public function removeProduct(Route $route, Product $product)
    {
        $route->products()->detach([$product->id]);
        return response()->json([
            'success' => true
        ]);
    }

    /**
     * @param Route $route
     * @return JsonResponse
     */
    public function getAllowance(Route $route)
    {
        $allowance = [];
        if ($route->allowance) {
            $allowance = $route->allowance->toArray();
        }
        return response()->json($allowance);
    }

    /**
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null)
    {
        $response = $this->route->search($q, 'name', ['name', 'code'], ['is_active' => 'No']);
        return response()->json($response);
    }

    /**
     * @param Route $route
     * @return mixed
     */
    public function exportProducts(Route $route)
    {
        if (\request()->input('type') == 'excel') {
            return $this->excelProductDownload($route);
        }
        $data = [];
        $data['products'] = $route->products()->with('stock')->get();
        $data['route'] = $route;
        $pdf = PDF::loadView('settings.route.export.product', $data);
        return $pdf->download('Route - Products (' . $route->code . ')' . '.pdf');
    }

    /**
     * @param Route $route
     * @return mixed
     */
    public function exportCustomers(Route $route)
    {
        if (\request()->input('type') == 'excel') {
            return $this->excelCustomerDownload($route);
        }
        $data = [];
        $data['customers'] = $route->customers;
        $data['route'] = $route;
        $pdf = PDF::loadView('settings.route.export.customer', $data);
        return $pdf->download('Route - Customers (' . $route->code . ')' . '.pdf');
    }

    /**
     * @param Route $route
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function excelProductDownload(Route $route)
    {
        return Excel::download(new RouteProductExport($route), 'Route - Products (' . $route->code . ')' . '.xlsx', 'Xlsx');
    }

    /**
     * @param Route $route
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function excelCustomerDownload(Route $route)
    {
        return Excel::download(new RouteCustomerExport($route), 'Route - Customers (' . $route->code . ')' . '.xlsx', 'Xlsx');
    }

    public function editQty(Route $route)
    {
        $this->authorize('edit', $this->route->getModel());
        $breadcrumb = $this->route->breadcrumbs('edit-qty', $route);
        $products = $route->products;
        return view('settings.route.edit-qty', compact('breadcrumb', 'route', 'products'));
    }

    public function updateQty(Route $route)
    {
        $this->authorize('update', $this->route->getModel());
        $this->route->updateQty($route);
        return redirect()->route('setting.route.index');
    }

}
