<?php

namespace App\Http\Controllers\General;

use App\Allowance;
use App\Http\Requests\General\AllowanceRequest;
use App\Repositories\General\AllowanceRepository;
use App\Http\Controllers\Controller;

class AllowanceController extends Controller
{
    public $allowance;

    /**
     * AllowanceController constructor.
     * @param AllowanceRepository $allowance
     */
    public function __construct(AllowanceRepository $allowance)
    {
        $this->allowance = $allowance;
    }

    /**
     * @param $modal
     * @param $modalId
     * @param AllowanceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create($modal, $modalId, AllowanceRequest $request)
    {
        $this->allowance->create($modal, $modalId, $request);
        return redirect()->back();
    }

    /**
     * @param AllowanceRequest $request
     * @param Allowance $allowance
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(AllowanceRequest $request, Allowance $allowance)
    {
        $this->allowance->edit($request, $allowance);
        return redirect()->back();
    }
}
