<?php

namespace App\Repositories\Settings;

use App\Repositories\BaseRepository;
/**
 * Class RouteRepRepository
 * @package App\Repositories\Settings
 */
class RouteRepRepository extends BaseRepository
{
    /**
     * @param $request
     * @param $relation
     */
    public function attach($request, $relation)
    {
        $ids = explode(',', $request->input('models'));
        $this->model->$relation()->attach($ids);
    }

    /**
     * @param $rep
     * @param $relation
     * @param $relationId
     * @return array
     */
    public function detach($rep, $relation, $relationId): array
    {
        $rep->$relation()->detach([$relationId]);
        return ["success" => true];
    }

    /**
     * @param $searchableModal
     * @param $relation
     * @param $column
     * @param null $q
     * @return array
     */
    public function searchModal($searchableModal, $relation, $column, $q = null)
    {
        $model = app('App\\' . $searchableModal);
        if ($model && $relation) {
            $alreadyHave = $this->model->$relation->pluck('id')->toArray();
            if (!$q) {
                $data = $model::whereNotIn('id', $alreadyHave)->get(['id', $column])->toArray();
            } else {
                $data = $model::whereNotIn('id', $alreadyHave)
                    ->where(function ($query) use ($q, $column) {
                        $query->where($column, 'LIKE', '%' . $q . '%');
                    })
                    ->get(['id', $column])
                    ->toArray();
            }
            // mapping the data
            $data = array_map(function ($obj) use ($column) {
                return ["name" => $obj[$column], "value" => $obj['id']];
            }, $data);
            return ["success" => true, "results" => $data];
        }
    }
}