<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\RouteTargetRequest;
use App\Http\Requests\Setting\RouteTargetUpdateRequest;
use App\Repositories\Settings\RouteTargetRepository;
use App\Route;
use App\RouteTarget;
use App\Http\Controllers\Controller;

class RouteTargetController extends Controller
{
    protected $routeTarget;

    /**
     * RouteTargetController constructor.
     * @param RouteTargetRepository $routeTarget
     */
    public function __construct(RouteTargetRepository $routeTarget)
    {
        $this->routeTarget = $routeTarget;
    }

    /**
     * @param Route $route
     * @param RouteTargetRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveTarget(Route $route, RouteTargetRequest $request)
    {
        $this->routeTarget->saveTarget($route, $request);
        alert()->success('Route target added successfully!', 'Success')->persistent();
        return redirect()->route('setting.route.show', [$route]);
    }

    /**
     * @param Route $route
     * @param RouteTarget $target
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTarget(Route $route, RouteTarget $target)
    {
        return response()->json($target->toArray());
    }

    /**
     * @param Route $route
     * @param RouteTarget $target
     * @param RouteTargetUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editTarget(Route $route, RouteTarget $target, RouteTargetUpdateRequest $request)
    {
        $this->routeTarget->updateTarget($route, $target, $request);
        alert()->success('Route target update successfully!', 'Success')->persistent();
        return redirect()->route('setting.route.show', [$route]);
    }
}
