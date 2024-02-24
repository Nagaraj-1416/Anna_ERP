<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\WorkHourStoreRequest;
use App\Repositories\Settings\WorkHourRepository;
use App\WorkHour;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class WorkHourController
 * @package App\Http\Controllers\Setting
 */
class WorkHourController extends Controller
{
    /**
     * @var WorkHourRepository
     */
    protected $workHour;

    /**
     * WorkHourController constructor.
     * @param WorkHourRepository $workHour
     */
    public function __construct(WorkHourRepository $workHour)
    {
        $this->workHour = $workHour;
    }

    /**
     * User index page
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        if (\request()->ajax()) {
            $workHours = $this->workHour->grid();
            return response()->json($workHours);
        }
        $breadcrumb = $this->workHour->breadcrumbs('index');
        return view('settings.work-hour.index', compact('breadcrumb'));
    }

    /**
     * @param WorkHourStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(WorkHourStoreRequest $request)
    {
        $this->workHour->save($request);
        alert()->success('Work hour is allocated successfully', 'Success')->persistent();
        return redirect()->route('setting.work.hour.index');
    }

    /**
     * @param WorkHour $workHour
     * @return JsonResponse
     */
    public function statusChange(WorkHour $workHour)
    {
        $data = $this->workHour->statusChange($workHour);
        return response()->json($data);
    }

}
