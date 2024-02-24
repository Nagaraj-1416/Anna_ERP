<?php

namespace App\Http\Controllers\Setting;

use App\Designation;
use App\Repositories\Settings\DesignationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DesignationController extends Controller
{
    public $designation;

    public function __construct(DesignationRepository $designation)
    {
        $this->designation = $designation;
    }

    public function store()
    {
        $request = \request();
        $request->validate([
            'name' => 'required'
        ]);
        return $this->designation->store($request);
    }

    public function search($q = null)
    {
        $response = $this->designation->search($q, 'name', ['name']);
        return response()->json($response);
    }
}
