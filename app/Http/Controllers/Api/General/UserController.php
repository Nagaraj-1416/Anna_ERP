<?php

namespace App\Http\Controllers\API\General;


use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Support\Facades\Storage;

/**
 * Class UserController
 * @package App\Http\Controllers\API\General
 */
class UserController extends ApiController
{
    /**
     * Get auth user
     * @return UserResource
     */
    public function index()
    {
        $user = auth()->user()->load('role', 'staffs.addresses', 'staffs.companies.addresses', 'staffs.rep');
        return new UserResource($user);
    }

    public function image()
    {
        $user = auth()->user();
        $staff = $user->staffs->first();
        $imagePath = storage_path('app/staff-profile-images/' . $staff->profile_image);
        if (!$staff->profile_image || !file_exists($imagePath)){
            $imagePath = storage_path('app/data/default.png');
        }
        return response()->file($imagePath);
    }


    public function getUserImage(User $user)
    {
        $staff = $user->staffs->first();
        $imagePath = storage_path('app/staff-profile-images/' . $staff->profile_image);
        if (!$staff->profile_image || !file_exists($imagePath)){
            $imagePath = storage_path('app/data/default.png');
        }
        return response()->file($imagePath);
    }
}
