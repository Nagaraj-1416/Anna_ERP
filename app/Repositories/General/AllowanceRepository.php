<?php

namespace App\Repositories\General;

use App\Allowance;
use App\Http\Requests\General\AllowanceRequest;
use App\Repositories\BaseRepository;

/**
 * Class AllowanceRepository
 * @package App\Repositories\General
 */
class AllowanceRepository extends BaseRepository
{
    /**
     * AllowanceRepository constructor.
     * @param Allowance|null $allowance
     */
    public function __construct(Allowance $allowance = null)
    {
        $this->setModel($allowance ?? new Allowance());
    }

    /**
     * @param $request
     * @param $modal
     * @param $modalId
     */
    public function create($modal, $modalId, AllowanceRequest $request)
    {
        $modal = app('App\\' . $modal)->find($modalId);
        $auth = auth()->user();
        $allowance = new Allowance();
        $allowance->assigned_date = carbon($request->input('assigned_date'));
        $allowance->amount = $request->input('amount');
        $allowance->assigned_by = $auth->id;
        $allowance->notes = $request->input('notes');
        $allowance->allowanceable_type = get_class($modal);
        $allowance->allowanceable_id = $modal->id;
        $allowance->company_id = 1;
        $allowance->save();
    }

    /**
     * @param AllowanceRequest $request
     * @param Allowance $allowance
     */
    public function edit(AllowanceRequest $request, Allowance $allowance)
    {
        $allowance->assigned_date = carbon($request->input('assigned_date'));
        $allowance->amount = $request->input('amount');
        $allowance->notes = $request->input('notes');
        $allowance->is_active = $request->input('is_active');
        $allowance->save();
    }
}