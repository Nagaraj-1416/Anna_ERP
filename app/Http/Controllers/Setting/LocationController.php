<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\LocationStoreRequest;
use App\Location;
use App\Repositories\Settings\LocationRepository;
use App\Route;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    /**
     * @var LocationRepository
     */
    protected $location;

    /**
     * LocationController constructor.
     * @param LocationRepository $location
     */
    public function __construct(LocationRepository $location)
    {
        $this->location = $location;
    }

    public function store(LocationStoreRequest $request, Route $route)
    {
        $this->location->save($request, $route);
        return redirect()->back();
    }

    public function delete(Route $route, Location $location)
    {
        $location->delete();
        return response()->json(['success' => true]);
    }

}
