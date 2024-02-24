<?php

namespace App\Http\Controllers\Setting;

use App\Repositories\Settings\UserRepository;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class UserController
 * @package App\Http\Controllers\Setting
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * UserController constructor.
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * User index page
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('index', $this->user->getModel());
        $breadcrumb = $this->user->breadcrumbs('index');
        return view('settings.user.index', compact('breadcrumb'));
    }

    /**
     * Handle the index page data table data
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function dataTableData(Request $request): JsonResponse
    {
        $this->authorize('index', $this->user->getModel());
        if (\request()->ajax()) {
            return response()->json($this->user->dataTable($request));
        }
    }

    /**
     * User show
     * @param User $user
     * @return View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user): View
    {
        $this->authorize('show', $user);
        $breadcrumb = $this->user->breadcrumbs('show', $user);
        $staff = $user->staffs->first();
        return view('settings.user.show', compact('breadcrumb', 'user', 'staff'));
    }

    /**
     * @param null $q
     * @return JsonResponse
     */
    public function search($q = null)
    {
        $response = $this->user->search($q, 'name', ['name'], ['is_active' => ['No']]);
        return response()->json($response);
    }

    public function searchWithoutRep($q = null)
    {
        $response = $this->user->search($q, 'name', ['name'], ['is_active' => ['No'], 'role_id' => [env('REP_ID', 17)]]);
        return response()->json($response);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function loginAs()
    {
        $this->authorize('loginAs', auth()->user());
        $request = request();
        $request->validate([
            'user_id' => 'required'
        ]);
        if ($request->input('user_id')) {
            $user = User::find($request->input('user_id'));
            auth()->logout();
            auth()->login($user);
        }
        return response()->json(['success' => true]);
    }
}
