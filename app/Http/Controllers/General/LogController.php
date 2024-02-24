<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public function index() 
    {

    }

    /**
     * @param $model
     * @param $modelId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs($model, $modelId)
    {
        $model  = app($model)->find($modelId);
        $activities = $model->auditLog->sortByDesc('id');
        $activities = $activities->map(function ($activity) {
            if ($activity->created_at->diffInSeconds(carbon()->now()) < 40) {
                $diff = 'Just Now';
            } else {
                $diff = carbon()->now()->sub($activity->created_at->diff(carbon()->now()))->diffForHumans();
            }
            $activity->created = $diff;
            return $activity;
        });
        return response()->json($activities->toArray());
    }
}
