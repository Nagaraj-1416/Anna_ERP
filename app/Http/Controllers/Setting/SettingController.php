<?php

namespace App\Http\Controllers\Setting;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\SettingUpdateRequest;
use App\ProductionUnit;
use App\Rep;
use App\Repositories\Settings\SettingRepository;
use App\SalesLocation;
use App\Store;
use App\UnitType;

class SettingController extends Controller
{
    protected $setting;

    /**
     * SettingController constructor.
     * @param SettingRepository $setting
     */
    public function __construct(SettingRepository $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Setting index
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumb = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Settings'],
        ];
        return view('settings.index', compact('breadcrumb'));
    }

    /**
     * @return array
     */
    public function staffList()
    {
        $request = request();
        $model = $request->model;
        $modelId = $request->modelId;
        $relation = $request->relation;
        $model = app('App\\' . $model)->find($modelId);
        $columns = ['code', 'full_name', 'email', 'phone', 'mobile', 'is_active'];
        $searchingColumns = ['code', 'full_name', 'email', 'phone', 'mobile', 'is_active'];
        $showRoute = 'setting.staff.show';
        $data = $this->setting->dataTable($model, $request, $relation, $columns, $searchingColumns, $showRoute);
        if (\request()->ajax()) {
            return $data;
        }
    }

    /**
     * @return array
     */
    public function routeList()
    {
        $request = request();
        $model = $request->model;
        $modelId = $request->modelId;
        $relation = $request->relation;
        if ($model == 'Rep') {
            $route = 'setting.route.show';
        } else {
            $route = 'setting.rep.show';
        }
        $model = app('App\\' . $model)->find($modelId);
        $columns = ['code', 'name', 'is_active'];
        $searchingColumns = ['code', 'name', 'is_active'];
        $data = $this->setting->dataTable($model, $request, $relation, $columns, $searchingColumns, $route);
        if (\request()->ajax()) {
            return $data;
        }
    }

    /**
     * @param SettingUpdateRequest $request
     * @param $modal
     * @param $modalId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateData(SettingUpdateRequest $request, $modal, $modalId)
    {
        $model = app('App\\' . $modal)->find($modalId);
        $this->setting->updateData($request, $model);
        return redirect()->back();
    }

    /**
     * @param $modal
     * @param $modalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteData($modal, $modalId)
    {
        $model = app('App\\' . $modal)->find($modalId);
        $response = $this->setting->delete($model);
        return response()->json($response);
    }

    /**
     * @param $model
     * @param $take
     * @param null $with
     * @return \Illuminate\Http\JsonResponse
     */

    public function summary($model, $take = null, $with = null)
    {
        $data = $this->setting->index($model, $take, $with);
        return response()->json($data);
    }

    /**
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUnitType($q = null)
    {
        $this->setting->setModel(new UnitType());
        $response = $this->setting->search($q, 'name', ['name'], ['is_active' => 'No']);
        return response()->json($response);
    }

    public function searchRelated($related, $q = null)
    {
        if ($related == 'Production To Store') {
            $this->setting->setModel(new ProductionUnit());
            $response = $this->setting->search($q, 'name', ['name'], ['is_active' => 'No']);
        } else if ($related == 'Store To Store') {
            $this->setting->setModel(new Store());
            $response = $this->setting->search($q, 'name', ['name'], ['is_active' => 'No'], [['type', 'General']]);
        } else if ($related == 'Store To Shop') {
            $this->setting->setModel(new Store());
            $response = $this->setting->search($q, 'name', ['name'], ['is_active' => 'No'], [['type', 'General']]);
        } else if ($related == 'Shop Selling Price') {
            $this->setting->setModel(new SalesLocation());
            $response = $this->setting->search($q, 'name', ['name'], ['is_active' => 'No'], [['type', 'Shop']]);
        } else if ($related == 'Van Selling Price') {
            $this->setting->setModel(new Rep());
            $response = $this->setting->search($q, 'name', ['name']);
        } else if ($related == 'Virtual Price') {
            $this->setting->setModel(new Company());
            $response = $this->setting->search($q, 'name', ['name']);
        } else {
            $response = ["success" => true, "results" => []];
        }
        return response()->json($response);
    }
}
