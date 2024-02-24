<?php

namespace App\Http\Controllers\Setting;

use App\Department;
use App\Http\Controllers\Controller;

use App\Http\Requests\Setting\AssignStaffRequest;
use App\Http\Requests\Setting\DepartmentStoreRequest;
use App\Repositories\Settings\DepartmentRepository;
use App\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * @var DepartmentRepository
     */
    protected $department;

    /**
     * DepartmentController constructor.
     * @param DepartmentRepository $department
     */
    public function __construct(DepartmentRepository $department)
    {
        $this->department = $department;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
        public function index()
        {
            //Customized to accpet the postman request
            if (\request()->ajax() || \request()->header('User-Agent') == 'Postman') {
                $departments = $this->department->grid();
                return response()->json($departments);
            }
            $this->authorize('index', $this->department->getModel());
            $breadcrumb = $this->department->breadcrumbs('index');
            return view('settings.department.index', compact('breadcrumb'));
        }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)//: array
    {
        // \Log::info('Form submitted with data:', $request->all());
        if (\request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            return $this->department->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('create', $this->department->getModel());
        $breadcrumb = $this->department->breadcrumbs('create');
        return view('settings.department.create', compact('breadcrumb'));
    }

    /**
     * @param DepartmentStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(DepartmentStoreRequest $request)//: RedirectResponse
    {
        //\Log::info('Form submitted with data:', $request->all());
        //return response()->json('success');
        if ( \request()->header('User-Agent') == 'Postman') {
            $department=$this->department->save($request);
            return response()->json($department);
        }

        $this->authorize('store', $this->department->getModel());
        $this->department->save($request);
        alert()->success('Department created successfully', 'Success')->persistent();
        return redirect()->route('setting.department.index');
    }

    /**
     * @param Department $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Department $department)//: View
    {
        if ( \request()->header('User-Agent') == 'Postman') {
            return response()->json($department);
        }
        $this->authorize('show', $this->department->getModel());
        $breadcrumb = $this->department->breadcrumbs('show', $department);
        return view('settings.department.show', compact('breadcrumb', 'department'));
    }

    /**
     * @param Department $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Department $department)//: View
    {
        //\Log::info('Form submitted with data:', $department->toArray());
        if ( \request()->header('User-Agent') == 'Postman') {
            $department->company_id = $department->company ? $department->company->id : '';
            return response()->json($department);
        }
        $this->authorize('edit', $this->department->getModel());
        $breadcrumb = $this->department->breadcrumbs('edit', $department);
        $department->company_id = $department->company ? $department->company->id : '';
        return view('settings.department.edit', compact('breadcrumb', 'department'));
    }

    /**
     * @param DepartmentStoreRequest $request
     * @param Department $department
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(DepartmentStoreRequest $request, Department $department) //: RedirectResponse
    {
        //\Log::info('Form submitted with data:', $request->toArray());
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $single_department=$this->department->update($request, $department);
            return response()->json($single_department);
        }
        $this->authorize('update', $this->department->getModel());
        $this->department->update($request, $department);
        alert()->success('Department updated successfully', 'Success')->persistent();
        return redirect()->route('setting.department.index');
    }

    /**
     * @param Department $department
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Department $department): JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $response = $this->department->delete($department);
            return response()->json($response);
        }
        $this->authorize('delete', $this->department->getModel());
        $response = $this->department->delete($department);
        return response()->json($response);
    }

    /**
     * assign staff to Department
     * @param AssignStaffRequest $request
     * @param Department $department
     * @return RedirectResponse
     */
    public function assignStaff(AssignStaffRequest $request, Department $department)//: RedirectResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $this->department->setModel($department);
            $this->department->assignStaff($request);
            return response()->json('Staff assigned successfully');
        }
        $this->department->setModel($department);
        $this->department->assignStaff($request);
        alert()->success('Staff assigned successfully', 'Success')->persistent();
        return redirect()->back();
    }

    /**
     * Remove staff from department
     * @param Department $department
     * @param Staff $staff
     * @return JsonResponse
     */
    public function removeStaff(Department $department, Staff $staff): JsonResponse
    {
        if ( \request()->ajax() || \request()->header('User-Agent') == 'Postman') {
            $this->department->setModel($department);
            $response = $this->department->removeStaff($staff);
            return response()->json($response);
        }
        $this->department->setModel($department);
        $response = $this->department->removeStaff($staff);
        return response()->json($response);
    }

    /**
     * Search staff from department
     * @param Department $department
     * @param null $q
     * @return JsonResponse
     */
    public function searchStaff(Department $department, $q = null): JsonResponse
    {
        $this->department->setModel($department);
        $response = $this->department->searchStaff($q);
        return response()->json($response);
    }
}
