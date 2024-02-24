<?php

namespace App\Http\Controllers\Setting;

use App\Company;
use App\Http\Requests\Setting\RepStoreRequest;
use App\Rep;
use App\Repositories\Settings\RepRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class RepController extends Controller
{
    /**
     * @var RepRepository
     */
    protected $rep;

    /**
     * RepController constructor.
     * @param RepRepository $rep
     */
    public function __construct(RepRepository $rep)
    {
        $this->rep = $rep;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', $this->rep->getModel());
        $breadcrumb = $this->rep->breadcrumbs('index');
        if (\request()->ajax()) {
            $reps = $this->rep->grid();
            return response()->json($reps);
        }
        return view('settings.rep.index', compact('breadcrumb'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTableData(Request $request)
    {
        if (\request()->ajax()) {
            return $this->rep->dataTable($request);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->rep->getModel());
        $breadcrumb = $this->rep->breadcrumbs('create');
        return view('settings.rep.create', compact('breadcrumb'));
    }

    /**
     * @param RepStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(RepStoreRequest $request)
    {
        $this->authorize('store', $this->rep->getModel());
        $this->rep->save($request);
        return redirect()->route('setting.rep.index');
    }

    /**
     * @param Rep $rep
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Rep $rep): View
    {
        $this->authorize('show', $this->rep->getModel());
        $staff = $rep->staff;
        $breadcrumb = $this->rep->breadcrumbs('show', $rep);
        return view('settings.rep.show', compact('breadcrumb', 'rep', 'staff'));
    }

    /**
     * @param Rep $rep
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Rep $rep): View
    {
        $this->authorize('edit', $this->rep->getModel());
        $breadcrumb = $this->rep->breadcrumbs('edit', $rep);
        return view('settings.rep.edit', compact('breadcrumb', 'rep'));
    }

    /**
     * @param RepStoreRequest $request
     * @param Rep $rep
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(RepStoreRequest $request, Rep $rep)
    {
        $this->authorize('update', $this->rep->getModel());
        $this->rep->update($request, $rep);
        return redirect()->route('setting.rep.index');
    }

    /**
     * @param Rep $rep
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Rep $rep)
    {
        $this->authorize('delete', $this->rep->getModel());
        $response = $this->rep->delete($rep);
        return response()->json($response);
    }

    /**
     * @param null $q
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($q = null)
    {
        $response = $this->rep->search($q, 'name', ['name'], ['is_active' => ['No']]);
        return response()->json($response);
    }

    public function searchByCompany(Company $company, $q = null)
    {
        if ($q == null) {
            $reps = $company->reps()->get(['id', 'name', 'code'])->toArray();
        } else {
            $reps = $company->reps()->where('name', 'LIKE', '%' . $q . '%')->get()->toArray();
        }
        $reps = array_map(function ($obj) {
            return ["name" => $obj['name'] . ' (' . $obj['code'] . ')', "value" => $obj['id']];
        }, $reps);
        return response()->json(["success" => true, "results" => $reps]);
    }

}
