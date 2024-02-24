<?php

namespace App\Repositories\Settings;

use App\Repositories\BaseRepository;
use App\RouteTarget;

class RouteTargetRepository extends BaseRepository
{
    /**
     * @param $route
     * @param $request
     */
    public function saveTarget($route, $request)
    {
        $types = $request->input('type');
        $startDates = $request->input('start_date');
        $endDates = $request->input('end_date');
        $targets = $request->input('target');
        foreach ($types as $key => $item) {
            $type = array_get($types, $key);
            $startDate = array_get($startDates, $key);
            $endDate = array_get($endDates, $key);
            $target = array_get($targets, $key);
            $newTarget = new RouteTarget();
            $newTarget->type = $type;
            $newTarget->start_date = carbon($startDate);
            $newTarget->end_date = carbon($endDate);
            $newTarget->target = $target;
            $newTarget->route_id = $route->id;
            $newTarget->save();
        }
    }

    /**
     * @param $route
     * @param RouteTarget $target
     * @param $request
     */
    public function updateTarget($route, RouteTarget $target, $request)
    {
        $target->type = $request->input('type');
        $target->start_date = carbon($request->input('start_date'));
        $target->end_date = carbon($request->input('end_date'));
        $target->target = $request->input('target');
        $target->achieved = $request->input('achieved');
        $target->is_active = $request->input('is_active');
        $target->save();
    }
}