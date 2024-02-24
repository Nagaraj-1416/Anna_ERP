<?php

namespace App\Repositories\General;

use App\Address;
use App\Http\Requests\General\ProfileRequest;
use App\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProfileRepository
 * @package App\Repositories\General
 */
class ProfileRepository extends BaseRepository
{
    protected $imagePath = 'staff-profile-images/';
    /**
     * ProfileRepository constructor.
     * @param User|null $user
     */
    public function __construct(User $user = null)
    {
        $this->setModel($user ?? new User());
    }

    /**
     * @param ProfileRequest $request
     * @param User $user
     */
    public function update(ProfileRequest $request, $user)
    {

        $staff = $user->staffs->first();
        $this->setModel($staff);
        $this->model->update($request->toArray());
        // save address
        /** @var Address $address */
        $address = $staff->addresses->first();
        if ($address) {
            $address->update($request->toArray());
        } else {
            $address = Address::create($request->toArray());
            $staff->addresses()->save($address);
        }

        $user->name = $request->input('short_name');
        $user->save();

        $rep = $staff->rep;
        if ($rep) {
            $rep->name = $request->input('short_name');
            $rep->save();
        }

        $staffImg = $request->file('staff_image');
        if ($staffImg) {
            /** upload the new staff image to storage and update raw data item */
            $staffImgType = $staffImg->getClientOriginalExtension();
            $staffImgName = $staff->getAttribute('code') . '.' . $staffImgType;
            Storage::put($this->imagePath . $staffImgName, file_get_contents($staffImg));

            /** update profile image name to row item */
            $staff->setAttribute('profile_image', $staffImgName);
            $staff->save();
        }
    }

    public function breadcrumbs(string $method, User $user = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Profile (' . $user->name . ')'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

}