<?php

namespace App\Http\Controllers\Setting;

use App\Http\Requests\Setting\MileageStoreRequest;
use App\MileageRate;
use App\Repositories\Settings\MileageRateRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class MileageRateController
 * @package App\Http\Controllers\Setting
 */
class MileageRateController extends Controller
{
    /** @var MileageRateRepository */
    protected $mileageRate;

    /**
     * MileageRateController constructor.
     * @param MileageRateRepository $mileageRate
     */
    public function __construct(MileageRateRepository $mileageRate)
    {
        $this->mileageRate = $mileageRate;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $breadcrumb = $this->mileageRate->breadcrumbs();
        return view('settings.mileage-rate.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()) {
            return response()->json($this->mileageRate->dataTable($request));
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $breadcrumb = $this->mileageRate->breadcrumbs();
        return view('settings.mileage-rate.create', compact('breadcrumb'));
    }

    /**
     * @param MileageStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MileageStoreRequest $request)
    {
        $this->mileageRate->store($request);
        alert()->success('Mileage rate created successfully', 'Success')->persistent();
        return redirect()->route('setting.mileage.rate.index');
    }

    /**
     * @param MileageRate $mileageRate
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(MileageRate $mileageRate)
    {
        $breadcrumb = $this->mileageRate->breadcrumbs();
        return view('settings.mileage-rate.edit', compact('breadcrumb', 'mileageRate'));
    }

    /**
     * @param MileageRate $mileageRate
     * @param MileageStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MileageRate $mileageRate, MileageStoreRequest $request)
    {
        $this->mileageRate->setModel($mileageRate);
        $this->mileageRate->update($request);
        alert()->success('Mileage rate created successfully', 'Success')->persistent();
        return redirect()->route('setting.mileage.rate.index');
    }

    /**
     * @param MileageRate $mileageRate
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(MileageRate $mileageRate)
    {
        $this->mileageRate->setModel($mileageRate);
        return response()->json(
            $this->mileageRate->delete()
        );
    }

    /**
     * Search the mileage rate
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null): JsonResponse
    {
        $response = $this->mileageRate->search($q, 'date', ['date']);
        return response()->json($response);
    }
}
