<?php

namespace App\Http\Controllers\Setting;

use App\Exports\StaffExport;
use App\Http\Requests\Setting\{
    StaffStoreRequest, StaffUpdateRequest
};
use App\Repositories\Settings\StaffRepository;
use App\Staff;
use Illuminate\Http\{
    JsonResponse, RedirectResponse, Request
};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

/**
 * Class StaffController
 * @package App\Http\Controllers\Setting
 */
class StaffController extends Controller
{
    /**
     * @var StaffRepository
     */
    protected $staff;
    protected $profileImagePath;

    /**
     * StaffController constructor.
     * @param StaffRepository $staff
     */
    public function __construct(StaffRepository $staff)
    {
        $this->staff = $staff;
        $this->profileImagePath = $staff->getImagePath();
    }

    /**
     * Load the index page view
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->staff->getModel());
        if (\request()->ajax()) {
            $staffs = $this->staff->grid();
            return response()->json($staffs);
        }
        $breadcrumb = $this->staff->breadcrumbs('index');
        return view('settings.staff.index', compact('breadcrumb'));
    }

    /**
     * Handle the index page data table data
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function dataTableData(Request $request): JsonResponse
    {
        $this->authorize('index', $this->staff->getModel());
        if (\request()->ajax()) {
            return response()->json($this->staff->dataTable($request));
        }
    }

    /**
     * Load the staff create view
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', $this->staff->getModel());
        $breadcrumb = $this->staff->breadcrumbs('create');
        return view('settings.staff.create', compact('breadcrumb'));
    }

    /**
     * Store the new staff
     * @param StaffStoreRequest $request
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StaffStoreRequest $request): RedirectResponse
    {
        $this->authorize('store', $this->staff->getModel());
        $this->staff->store($request);
        alert()->success('Staff created successfully', 'Success')->persistent();
        return redirect()->route('setting.staff.index');
    }

    /**
     * Show the staff
     * @param Staff $staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|JsonResponse|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Staff $staff)
    {
        $this->authorize('show', $staff);
        $breadcrumb = $this->staff->breadcrumbs('show', $staff);
        $staff->load(['user.role', 'addresses', 'comments']);
        $comments = $staff->comments()->get();
        $address = $staff->addresses->first();
        $associatedTypes = $staff->businessTypes()->get()->pluck('name')->toArray();
        $associatedTypes = implode(', ', $associatedTypes);
        if (\request()->ajax()) {
            return response()->json(['item' => $staff, 'comments' => $comments]);
        }
        return view('settings.staff.show', compact('breadcrumb', 'staff', 'address', 'associatedTypes'));
    }

    /**
     * Load the staff edit view
     * @param Staff $staff
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Staff $staff): View
    {
        $this->authorize('edit', $staff);
        $breadcrumb = $this->staff->breadcrumbs('edit', $staff);
        $staff->setAttribute('role', $staff->user ? $staff->user->role_id : '');
        $address = $staff->addresses->first();
        $staff->setAttribute('street_one', $address->street_one ?? null);
        $staff->setAttribute('street_two', $address->street_two ?? null);
        $staff->setAttribute('city', $address->city ?? null);
        $staff->setAttribute('province', $address->province ?? null);
        $staff->setAttribute('postal_code', $address->postal_code ?? null);
        $staff->setAttribute('country_id', $address->country_id ?? null);

        if ($staff->user && $staff->user->prefix) {
            $prefix = explode('/', $staff->user->prefix);
            $staff->setAttribute('prefix_first', array_get($prefix, 0));
            $staff->setAttribute('prefix_last', array_get($prefix, 1));
        }
        $associatedTypes = $staff->businessTypes()->get()->pluck('id')->toArray();
        $associatedTypes = implode(',', $associatedTypes);
        return view('settings.staff.edit', compact('breadcrumb', 'staff', 'address', 'associatedTypes'));
    }

    /**
     * Update the staff information
     * @param StaffUpdateRequest $request
     * @param Staff $staff
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(StaffUpdateRequest $request, Staff $staff): RedirectResponse
    {
        $this->authorize('update', $staff);
        $this->staff->update($request, $staff);
        alert()->success('Staff created successfully', 'Success')->persistent();
        return redirect()->route('setting.staff.index');
    }

    /**
     * Delete the staff
     * @param Staff $staff
     * @return JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Staff $staff): JsonResponse
    {
        $this->authorize('delete', $staff);
        $response = $this->staff->delete($staff);
        return response()->json($response);
    }

    /**
     * Search the staffs
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null): JsonResponse
    {
        $response = $this->staff->search($q, 'short_name', ['short_name', 'first_name', 'last_name'], ['is_active' => ['No']]);
        return response()->json($response);
    }

    /**
     * @param Staff $staff
     * @return mixed
     */
    public function getImage(Staff $staff)
    {
        if ($staff->getAttribute('profile_image')) {
            $file = $this->profileImagePath . $staff->getAttribute('profile_image');
            $imagePath = Storage::get(Storage::exists($file) ? $file : 'data/default.png');
        } else {
            $imagePath = Storage::get('data/default.png');
        }
        return response($imagePath)->header('Content-Type', 'image/jpg');
    }

    /**
     * @param Staff|null $staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|JsonResponse|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function newIndex(Staff $staff = null)
    {
        $this->authorize('index', $this->staff->getModel());
        $breadcrumb = $this->staff->breadcrumbs('index');
        $staff = $staff ? $staff->toArray() : [];
        if (\request()->ajax()) {
            $staffs = Staff::all();
            return response()->json(['datas' => $staffs->toArray(), 'selected' => $staff]);
        }
        return view('settings.staff.index-new', compact('breadcrumb'));
    }

    /**
     * @return mixed
     */
    public function export()
    {
        if (\request()->input('type') == 'excel') {
            return $this->excelDownload();
        }
        $staffs = Staff::all();
        $data = [];
        $data['staffs'] = $staffs;
        ini_set("pcre.backtrack_limit", "2000000");
        ini_set('memory_limit', '256M');
        $pdf = PDF::loadView('settings.staff.export', $data);
        return $pdf->download(env('APP_NAME') . ' - Staff.pdf');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function excelDownload()
    {
        return Excel::download(new StaffExport(), env('APP_NAME') . ' - Staff.xlsx', 'Xlsx');
    }

    /**
     * @param Staff $staff
     * @return JsonResponse
     */
    public function uploadImage(Staff $staff)
    {
        request()->validate(['image' => 'required|image']);
        $staffImg = request()->file('image');
        if ($staffImg) {
            $staffImgType = $staffImg->getClientOriginalExtension();
            $staffImgName = $staff->getAttribute('code') . '.' . $staffImgType;
            Storage::put($this->staff->imagePath . $staffImgName, file_get_contents($staffImg));

            /** update staff image name to row item */
            $staff->setAttribute('profile_image', $staffImgName);
            $staff->save();
        }

        return response()->json(['success' => true]);
    }
}
