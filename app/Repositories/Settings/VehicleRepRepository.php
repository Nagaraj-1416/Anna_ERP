<?php

namespace App\Repositories\Settings;

use App\Repositories\BaseRepository;
use App\RepVehicleHistory;

/**
 * Class VehicleRepRepository
 * @package App\Repositories\Settings
 */
class VehicleRepRepository extends BaseRepository
{
    public function searchData($model, $modelId, $searchModal, $relation, $column, $q = null)
    {
        $model = app('App\\' . $model)->find($modelId);
        $searchModal = app('App\\' . $searchModal);
        $this->setModel($model);
        if ($model && $relation) {
            $alreadyHave = $this->model->$relation()->wherePivot('status', 'Assigned')->pluck('id')->toArray();
            if (!$q) {
                $data = $searchModal::whereNotIn('id', $alreadyHave)->get(['id', $column])->toArray();
            } else {
                $data = $searchModal::whereNotIn('id', $alreadyHave)
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

    /**
     * @param $model
     * @param $modelId
     * @param $relation
     * @param $request
     */
    public function attach($model, $modelId, $relation, $request)
    {
        $model = app('App\\' . $model)->find($modelId);
        $vehicleId = $request->input('vehicle');
        $assignedDate = $request->input('date');
        if (!$assignedDate) $assignedDate = carbon()->now();
        $model->vehicle_id = (int)$vehicleId;
        $model->save();
        $repVehicleHistory = new RepVehicleHistory();
        $repVehicleHistory->vehicle_id = $vehicleId;
        $repVehicleHistory->rep_id = $model->id;
        $repVehicleHistory->assigned_date = $assignedDate;
        $repVehicleHistory->status = 'Active';
        $repVehicleHistory->save();
    }

    /**
     * @param $method
     * @param $modal
     * @param $vehicle
     * @param $request
     */
    public function statusChange($method, $modal, $vehicle, $request)
    {
        if ($method == 'Revoke') {
            $modal->vehicles()->updateExistingPivot(['vehicle_id' => $vehicle, 'status' => 'Assigned'], ['revoked_date' => $request->input('date'), 'status' => 'Revoked']);
        } else if ($method == 'Block') {
            $modal->vehicles()->updateExistingPivot(['vehicle_id' => $vehicle, 'status' => 'Assigned'], ['blocked_date' => $request->input('block_date'), 'status' => 'Blocked']);
        } else if ($method == 'Un block') {

        }
    }

    /**
     * @param $method
     * @param $rep
     * @param $request
     */
    public function vehicleStatusChange($method, $rep, $request)
    {
        if ($method == 'Revoke') {
            $repVehicleHistory = new RepVehicleHistory();
            $repVehicleHistory->vehicle_id = $rep->vehicle_id;
            $repVehicleHistory->rep_id = $rep->id;
            $repVehicleHistory->revoked_date = $request->input('date') ?? carbon()->now();
            $repVehicleHistory->status = 'Revoked';
            $repVehicleHistory->save();
            $rep->vehicle_id = null;
            $rep->save();
        } else if ($method == 'Block') {
            $repVehicleHistory = new RepVehicleHistory();
            $repVehicleHistory->vehicle_id = $rep->vehicle_id;
            $repVehicleHistory->rep_id = $rep->id;
            $repVehicleHistory->blocked_date = $request->input('blocked_date') ?? carbon()->now();
            $repVehicleHistory->status = 'Blocked';
            $repVehicleHistory->save();
            $rep->vehicle_id = null;
            $rep->save();
        }
    }
}