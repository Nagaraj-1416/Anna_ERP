<?php

namespace App\Repositories\Settings;

use App\Repositories\BaseRepository;
use App\RepTarget;

class RepTargetRepository extends BaseRepository
{
    /**
     * @param $rep
     * @param $request
     */
    public function saveTarget($rep, $request)
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
            $newTarget = new RepTarget();
            $newTarget->type = $type;
            $newTarget->start_date = carbon($startDate);
            $newTarget->end_date = carbon($endDate);
            $newTarget->target = $target;
            $newTarget->rep_id = $rep->id;
            $newTarget->save();
        }
    }

    /**
     * @param $rep
     * @param RepTarget $target
     * @param $request
     */
    public function updateTarget($rep, RepTarget $target, $request)
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