<?php

namespace App\Repositories\Settings;

use App\Location;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class LocationRepository extends BaseRepository
{
    /**
     * LocationRepository constructor.
     * @param Location|null $location
     */
    public function __construct(Location $location = null)
    {
        $this->setModel($location ?? new Location());
        $this->setCodePrefix('RL');
    }

    public function save($request, $route)
    {
        $location = $request->input('location');
        if ($location) {
            $this->saveLocation($request->input('location'), $route);
        }
    }

    protected function saveLocation($locations, $route)
    {
        $names = array_get($locations, 'name');
        $notes = array_get($locations, 'notes');
        $ids = array_get($locations, 'id');
        foreach ($names as $key => $location) {
            $location = new Location();
            if($id =  array_get($ids, $key)){
                $location = Location::find($id);
            }
            $location->code = $this->getCode();
            $location->name = array_get($names, $key);
            $location->notes = array_get($notes, $key);
            $location->route_id = $route->id;
            $location->save();
        }
    }

}