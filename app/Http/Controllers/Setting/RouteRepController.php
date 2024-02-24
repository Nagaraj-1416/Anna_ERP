<?php

namespace App\Http\Controllers\Setting;

use App\Repositories\Settings\RouteRepRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RouteRepController extends Controller
{
    /**
     * @var RouteRepRepository
     */
    protected $routeRep;

    /**
     * RouteRepController constructor.
     * @param RouteRepRepository $routeRep
     */
    public function __construct(RouteRepRepository $routeRep)
    {
        $this->routeRep = $routeRep;
    }
    /**
     * @param $model
     * @param $modalId
     * @param $relation
     * @param $relationId
     * @return JsonResponse
     */
    public function detach($model, $modalId, $relation, $relationId)
    {
        $model = app('App\\' . $model)->find($modalId);
        if (!$model) return response()->json(['error' => true]);
        $response = $this->routeRep->detach($model, $relation, $relationId);
        return response()->json($response);
    }

    /**
     * @param $attachModal
     * @param $attachModalId
     * @param $relation
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attach($attachModal, $attachModalId, $relation, Request $request)
    {
        $model = app('App\\' . $attachModal)->find($attachModalId);
        if ($model) {
            $this->routeRep->setModel($model);
            $this->routeRep->attach($request, $relation);
        }
        return redirect()->back();
    }

    /**
     * @param $modal
     * @param $modalId
     * @param $searchableModal
     * @param $relation
     * @param $column
     * @param null $q
     * @return JsonResponse
     */
    public function search($modal, $modalId, $searchableModal, $relation, $column, $q = null)
    {
        $modal = app('App\\' . $modal)->find($modalId);
        $this->routeRep->setModel($modal);
        $response = $this->routeRep->searchModal($searchableModal, $relation, $column, $q);
        return response()->json($response);
    }
}
