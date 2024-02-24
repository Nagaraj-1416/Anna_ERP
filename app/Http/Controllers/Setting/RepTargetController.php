<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\RepTargetRequest;
use App\Http\Requests\Setting\RepTargetUpdateRequest;
use App\Rep;
use App\Repositories\Settings\RepTargetRepository;
use App\Http\Controllers\Controller;
use App\RepTarget;

class RepTargetController extends Controller
{
    protected $repTarget;

    /**
     * RepTargetController constructor.
     * @param RepTargetRepository $repTarget
     */
    public function __construct(RepTargetRepository $repTarget)
    {
        $this->repTarget = $repTarget;
    }

    /**
     * @param Rep $rep
     * @param RepTargetRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveTarget(Rep $rep, RepTargetRequest $request)
    {
        $this->repTarget->saveTarget($rep, $request);
        alert()->success('Rep target added successfully!', 'Success')->persistent();
        return redirect()->route('setting.rep.show', [$rep]);
    }

    /**
     * @param Rep $rep
     * @param RepTarget $target
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTarget(Rep $rep, RepTarget $target)
    {
        return response()->json($target->toArray());
    }

    /**
     * @param Rep $rep
     * @param RepTarget $target
     * @param RepTargetUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editTarget(Rep $rep, RepTarget $target, RepTargetUpdateRequest $request)
    {
        $this->repTarget->updateTarget($rep, $target, $request);
        alert()->success('Rep target update successfully!', 'Success')->persistent();
        return redirect()->route('setting.rep.show', [$rep]);
    }
}
