<?php

namespace App\Http\Controllers\Sales;

use App\DailySale;
use App\Rep;
use App\Route;
use App\SalesLocation;
use App\Staff;
use App\Http\Controllers\Controller;

class AllocationSearchController extends Controller
{
    /**
     * @param null $fromDate
     * @param null $toDate
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDrivers($fromDate = null, $toDate = null, $q = null)
    {
        if (!$fromDate) $fromDate = carbon()->now()->toDateString();
        if (!$toDate) $toDate = $toDate ?? carbon()->now()->toDateString();

        $allocations = DailySale::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereNotIn('status', ['Canceled'])->where(function ($q) use ($fromDate, $toDate) {
            $q->where(function ($q) use ($fromDate, $toDate) {
                $q->where('from_date', '>=', $fromDate)
                    ->where('to_date', '<=', $toDate);
            })->orWhere('status', 'Progress');
        })->get();
        $driverIds = $allocations->pluck('driver_id')->toArray();
        if (!$q) {
            $staffs = Staff::where('designation_id', 12)->whereNotIn('id', $driverIds)
                ->whereHas('companies', function ($q){
                    $q->whereIn('id', userCompanyIds(loggedUser()));
                })->get(['id', 'short_name'])->toArray();
        } else {
            $staffs = Staff::where('designation_id', 12)->whereNotIn('id', $driverIds)
                ->where(function ($query) use ($q) {
                    $query->where('short_name', 'LIKE', '%' . $q . '%');
                })->whereHas('companies', function ($q){
                    $q->whereIn('id', userCompanyIds(loggedUser()));
                })->get(['id', 'short_name'])->toArray();
        }
        $staffs = array_map(function ($obj) {
            return ["name" => $obj['short_name'], "value" => $obj['id']];
        }, $staffs);
        return response()->json(["success" => true, "results" => $staffs]);
    }

    /**
     * @param null $fromDate
     * @param null $toDate
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLabours($fromDate = null, $toDate = null, $q = null)
    {
        if (!$fromDate) $fromDate = carbon()->now()->toDateString();
        if (!$toDate) $toDate = $toDate ?? carbon()->now()->toDateString();

        $allocations = DailySale::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereNotIn('status', ['Canceled'])->where(function ($q) use ($fromDate, $toDate) {
            $q->where(function ($q) use ($fromDate, $toDate) {
                $q->where('from_date', '>=', $fromDate)
                    ->where('to_date', '<=', $toDate);
            })->orWhere('status', 'Progress');
        })->get();
        $labours = $allocations->pluck('labour_id')->toArray();
        $laboursID = [];
        foreach ($labours as $item) {
            $data = explode(',', $item);
            $laboursID = array_merge($data, $laboursID);
        }
        if (!$q) {
            $staffs = Staff::where('designation_id', 15)->whereNotIn('id', $laboursID)
                ->whereHas('companies', function ($q){
                    $q->whereIn('id', userCompanyIds(loggedUser()));
                })->get(['id', 'short_name'])->toArray();
        } else {
            $staffs = Staff::where('designation_id', 15)->whereNotIn('id', $laboursID)
                ->where(function ($query) use ($q) {
                    $query->where('short_name', 'LIKE', '%' . $q . '%');
                })->whereHas('companies', function ($q){
                    $q->whereIn('id', userCompanyIds(loggedUser()));
                })->get(['id', 'short_name'])->toArray();
        }
        $staffs = array_map(function ($obj) {
            return ["name" => $obj['short_name'], "value" => $obj['id']];
        }, $staffs);
        return response()->json(["success" => true, "results" => $staffs]);
    }

    /**
     * @param null $fromDate
     * @param null $toDate
     * @param $type
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchSalesLocation($fromDate = null, $toDate = null, $type, $q = null)
    {
        if ($type == 'Van') $type = 'Sales Van';
        if (!$fromDate) $fromDate = carbon()->now()->toDateString();
        if (!$toDate) $toDate = $toDate ?? carbon()->now()->toDateString();

        $allocations = DailySale::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereNotIn('status', ['Canceled'])->where(function ($q) use ($fromDate, $toDate) {
            $q->where(function ($q) use ($fromDate, $toDate) {
                $q->where('from_date', '>=', $fromDate)
                    ->where('to_date', '<=', $toDate);
            })->orWhere('status', 'Progress');
        })->get();
        $locationsID = $allocations->pluck('sales_location_id')->toArray();
        if (!$q) {
            $locations = SalesLocation::whereIn('company_id', userCompanyIds(loggedUser()))
                ->whereNotIn('id', $locationsID)->where('type', $type)->get(['name', 'id'])->toArray();
        } else {
            $locations = SalesLocation::whereIn('company_id', userCompanyIds(loggedUser()))
                ->whereNotIn('id', $locationsID)->where('type', $type)
                ->where(function ($query) use ($q) {
                    $query->where('name', 'LIKE', '%' . $q . '%');
                })
                ->get(['id', 'name'])
                ->toArray();
        }
        $locations = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $locations);
        return response()->json(["success" => true, "results" => $locations]);
    }

    /**
     * @param null $fromDate
     * @param null $toDate
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchSalesRep($fromDate = null, $toDate = null, $q = null)
    {
        if (!$fromDate) $fromDate = carbon()->now()->toDateString();
        if (!$toDate) $toDate = $toDate ?? carbon()->now()->toDateString();

        $allocations = DailySale::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereNotIn('status', ['Canceled'])->where(function ($q) use ($fromDate, $toDate) {
            $q->where(function ($q) use ($fromDate, $toDate) {
                $q->where('from_date', '>=', $fromDate)
                    ->where('to_date', '<=', $toDate);
            })->orWhere('status', 'Progress');
        })->get();
        $repId = $allocations->pluck('rep_id')->toArray();
        if (!$q) {
            $reps = Rep::whereIn('company_id', userCompanyIds(loggedUser()))
                ->whereNotIn('id', $repId)->get(['name', 'id'])->toArray();
        } else {
            $reps = Rep::whereIn('company_id', userCompanyIds(loggedUser()))
                ->whereNotIn('id', $repId)
                ->where(function ($query) use ($q) {
                    $query->where('name', 'LIKE', '%' . $q . '%');
                })
                ->get(['id', 'name'])
                ->toArray();
        }
        $reps = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $reps);
        return response()->json(["success" => true, "results" => $reps]);
    }

    /**
     * @param null $fromDate
     * @param null $toDate
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchRoute($fromDate = null, $toDate = null, $q = null)
    {
        if (!$fromDate) $fromDate = carbon()->now()->toDateString();
        if (!$toDate) $toDate = $toDate ?? carbon()->now()->toDateString();

        $allocations = DailySale::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereNotIn('status', ['Canceled'])->where(function ($q) use ($fromDate, $toDate) {
            $q->where(function ($q) use ($fromDate, $toDate) {
                $q->where('from_date', '>=', $fromDate)
                    ->where('to_date', '<=', $toDate);
            })->orWhere('status', 'Progress');
        })->get();
        $routeId = $allocations->pluck('route_id')->toArray();
        if (!$q) {
            $routes = Route::whereIn('company_id', userCompanyIds(loggedUser()))
                ->whereNotIn('id', $routeId)->get(['name', 'id'])->toArray();
        } else {
            $routes = Route::whereIn('company_id', userCompanyIds(loggedUser()))
                ->where(function ($query) use ($q) {
                    $query->where('name', 'LIKE', '%' . $q . '%');
                })
                ->get(['id', 'name'])
                ->toArray();
        }
        $routes = array_map(function ($obj) {
            return ["name" => $obj['name'], "value" => $obj['id']];
        }, $routes);
        return response()->json(["success" => true, "results" => $routes]);
    }
}
