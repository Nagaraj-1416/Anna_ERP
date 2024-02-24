<?php

namespace App\Repositories\Settings;

use App\Repositories\BaseRepository;
use App\SalesLocation;
use Illuminate\Http\Request;

/**
 * Class SettingRepository
 * @package App\Repositories\Settings
 */
class SettingRepository extends BaseRepository
{
    /**
     * @param $model
     * @param Request $request
     * @param $relation
     * @param $columns
     * @param $searchingColumns
     * @param null $showRoute
     * @return array
     */

    public function dataTable($model, Request $request, $relation, $columns, $searchingColumns, $showRoute = null): array
    {
        $this->setModel($model);
        $data = $this->getTableData($request, $columns, $searchingColumns, [], true, $relation);
        $data['data'] = array_map(function ($item) use ($showRoute) {
            if ($showRoute) {
                $item['code'] = '<a href="' . route($showRoute, $item['id']) . '">' . $item['code'] . '</a>';
            }
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Remove', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger remove-staff']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    /**
     * @param $request
     * @param $modal
     */
    public function updateData($request, $modal)
    {
        $code = array_get($modal->toArray(), 'code');
        if ($code) {
            $request->merge(['code' => $code]);
        }
        $this->setModel($modal);
        $this->model->update($request->toArray());
    }

    /**
     * @param $modal
     * @return array
     */
    public function delete($modal)
    {
        $modal->delete();
        return ['success' => true];
    }

    /**
     * @param $model
     * @param $take
     * @param null $with
     * @return array
     */
    public function index($model, $take = null, $with = null)
    {
        if ($model == 'Shop' || $model == 'Sales Van') {
            $data = $this->getSalesLocation($model);
        } elseif ($model == 'Product') {
            $data = $this->getProducts($model, $take, $with);
        } elseif ($model == 'Activity') {
            $data = $this->getActivities($model, $take, $with);
        } else {
            $data = $this->getData($model, $take, $with);
        }
        return $data;
    }

    /**
     * @param $model
     * @return array
     */
    public function getSalesLocation($model)
    {
        $modelCount = SalesLocation::where('type', $model)->count();
        $data = [];
        $data['count'] = $modelCount;
        return $data;
    }


    /**
     * @param $model
     * @param null $take
     * @param null $with
     * @return array
     */
    public function getProducts($model, $take = null, $with = null)
    {

        $model = app('App\\' . $model);
        $modelData = collect([]);
        if ($take) {
            $modelData = $model->where('is_active', 'Yes')->orderBy('created_at', 'desc')->take($take);
            if ($with) {
                $modelData = $modelData->with([$with]);
                $modelData = $modelData->get()->map(function ($data) {
                    $data->stock_level = $data->stocks->sum('available_stock');
                    return $data;
                });
            }
        }
        $modelCount = $model->count();
        $data = [];
        $data['model'] = $modelData;
        $data['count'] = $modelCount;
        return $data;
    }

    /**
     * @param $model
     * @param null $take
     * @param null $with
     * @return array
     */
    public function getData($model, $take = null, $with = null)
    {
        $model = app('App\\' . $model);
        $modelData = collect([]);
        if ($take) {
            $modelData = $model->where('is_active', 'Yes')->orderBy('created_at', 'desc')->take($take);

            $modelData = $modelData->get();
        }
        $modelCount = $model->count();
        $data = [];
        $data['model'] = $modelData;
        $data['count'] = $modelCount;
        return $data;
    }

    /**
     * @param $model
     * @param null $take
     * @param null $with
     * @return array
     */
    public function getActivities($model, $take = null, $with = null)
    {
        $model = app('App\\' . $model);
        $modelData = collect([]);
        if ($take) {
            $modelData = $model->orderBy('created_at', 'desc')->take($take);
            if ($with) {
                $modelData = $modelData->with(['causer']);
            }
            $modelData = $modelData->get();
        }
        $modelData = $modelData->map(function ($data) {
            $data->created = $data->created_at->diffForHumans();
            return $data;
        });
        $modelCount = $model->count();
        $data = [];
        $data['model'] = $modelData;
        $data['count'] = $modelCount;
        return $data;
    }
}