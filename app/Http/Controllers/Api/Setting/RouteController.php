<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\RouteResource;
use App\Route;
use Illuminate\Http\Request;

class RouteController extends ApiController
{
    public function show(Route $route = null)
    {
        $route->load('customers');
        return new RouteResource($route);
    }

    public function pickNext(Request $request)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
        ]);
        $allocation = getRepAllocation(null, null, auth()->user())->first();
        $nextRoute = request()->input('route_id');
        if ($allocation){
            $allocation->nxt_day_al_route = $nextRoute;
            $allocation->save();
            return response()->json(array(
                'message' => 'Next day route updated.',
                'success' => true
            ), 200);
        }else{
            return response()->json(array(
                'message' => 'Could\'t find the allocation.',
                'success' => false
            ), 422);
        }
    }

    public function nextDay()
    {
        $allocation = getRepAllocation(null, null, auth()->user())->first();
        if ($allocation->nextDayRoute){
            return new RouteResource($allocation->nextDayRoute);
        }else{
            return response()->json([
               'data' => null
            ]);
        }
    }
}
