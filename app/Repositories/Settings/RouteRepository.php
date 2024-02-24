<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\AssignProductRequest;
use App\Http\Requests\Setting\StaffStoreRequest;
use App\Location;
use App\Repositories\BaseRepository;
use App\Route;
use Illuminate\Http\Request;

/**
 * Class RouteRepository
 * @package App\Repositories\Settings
 */
class RouteRepository extends BaseRepository
{
    /**
     * RouteRepository constructor.
     * @param Route|null $route
     */
    public function __construct(Route $route = null)
    {
        $this->setModel($route ?? new Route());
        $this->setCodePrefix('RTE');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request)
    {
        $columns = ['code', 'name', 'notes', 'is_active'];
        $searchingColumns = ['code', 'name', 'notes', 'is_active'];
        $data = $this->getTableData($request, $columns, $searchingColumns);
        $data['data'] = array_map(function ($item) {
            $item['code'] = '<a href="' . route('setting.route.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('show', $this->getModel())) {
                $item['action'] .= actionBtn('Show', null, ['setting.route.show', [$item['id']]], ['class' => 'btn-success']);
            }
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, ['setting.route.edit', [$item['id']]]);
            }
            if (can('delete', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-route']);
            }
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $routes = Route::orderBy('id', 'desc')->with('company', 'locations');
        if ($search) {
            $routes->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%');
            });
        }
        switch ($filter) {
            case 'Active':
                $routes->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $routes->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $routes->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $routes->where('updated_at', '>', $lastWeek);
                break;
        }
        return $routes->paginate(15)->toArray();
    }

    public function save($request)
    {
        $request->merge(['code' => $this->getCode()]);
        $route = $this->model->fill($request->toArray());
        $route->save();
        return $route;
    }

    /**
     * @param $request
     * @param Route $route
     * @return Route
     */
    public function update($request, Route $route)
    {
        $request->merge(['code' => $route->code]);
        $this->setModel($route);
        $this->model->update($request->toArray());
        return $route;
    }

    /**
     * @param Route $route
     * @return array
     * @throws \Exception
     */
    public function delete(Route $route): array
    {
        $route->delete();
        return ['success' => true];
    }

    /**
     * @param string $method
     * @param Route|null $route
     * @return array
     */
    public function breadcrumbs(string $method, Route $route = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Routes'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Routes', 'route' => 'setting.route.index'],
                ['text' => 'Create'],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Routes', 'route' => 'setting.route.index'],
                ['text' => $route->name ?? ''],
                ['text' => 'Edit'],
            ],
            'edit-qty' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Routes', 'route' => 'setting.route.index'],
                ['text' => $route->name ?? ''],
                ['text' => 'Update Default Qty'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Routes', 'route' => 'setting.route.index'],
                ['text' => $route->name ?? ''],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    public function assignProduct(AssignProductRequest $request)
    {
        $products = $request->input('products');
        $this->model->products()->attach($products);
        return $this->model;
    }

    /**
     * @param Route $route
     * @return Route
     */
    public function updateQty(Route $route)
    {
        $request = Request();
        $products = $request->input('products');

        /** detach available products */
        $route->products()->detach();

        /** attach products */
        $route->products()->attach($products);
        return $route;
    }

}