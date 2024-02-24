<?php

namespace App\Http\Controllers\General;

use App\Http\Requests\General\ProfileRequest;
use App\User;
use App\Repositories\General\ProfileRepository;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /** @var ProfileRepository */
    protected $profile;

    /**
     * ProfileController constructor.
     * @param ProfileRepository $profile
     */
    public function __construct(ProfileRepository $profile)
    {
        $this->profile = $profile;
    }

    public function index()
    {
        $user = auth()->user();
        $staff = $user->staffs()->first();
        $address = $staff->addresses()->first();
        $staff->setAttribute('street_one', $address->street_one ?? null);
        $staff->setAttribute('street_two', $address->street_two ?? null);
        $staff->setAttribute('city', $address->city ?? null);
        $staff->setAttribute('province', $address->province ?? null);
        $staff->setAttribute('postal_code', $address->postal_code ?? null);
        $staff->setAttribute('country_id', $address->country_id ?? null);

        $breadcrumb = $this->profile->breadcrumbs('index', $user);
        return view('general.profile.index', compact('breadcrumb', 'user', 'staff', 'address'));
    }

    /**
     * @param ProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
        $expense = $this->profile->update($request, $user);
        alert()->success('Profile updated successfully', 'Success')->persistent();
        return redirect()->route('profile.index');
    }

}
