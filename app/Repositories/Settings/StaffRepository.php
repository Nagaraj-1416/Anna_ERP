<?php

namespace App\Repositories\Settings;

use App\{
    Address, Rep, Staff, User, Vehicle
};
use App\Http\Requests\Setting\{
    StaffStoreRequest, StaffUpdateRequest
};
use App\Repositories\BaseRepository;
use App\Repositories\Finance\AccountRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use function PhpParser\canonicalize;

/**
 * Class StaffRepository
 * @package App\Repositories\Settings
 */
class StaffRepository extends BaseRepository
{
    public $imagePath = 'staff-profile-images/';
    protected $account;

    /**
     * StaffRepository constructor.
     * @param AccountRepository $account
     * @param Staff|null $staff
     */
    public function __construct(AccountRepository $account, Staff $staff = null)
    {
        $this->setModel($staff ?? new Staff());
        $this->setCodePrefix('STF');

        $this->account = $account;
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['code', 'salutation', 'first_name', 'last_name', 'full_name', 'short_name', 'gender', 'dob', 'email', 'phone', 'mobile',
            'joined_date', 'designation', 'resigned_date', 'bank_name', 'branch', 'account_name', 'account_no', 'epf_no',
            'etf_no', 'pay_rate', 'notes', 'is_active'];
        $searchingColumns = ['code', 'salutation', 'first_name', 'last_name', 'full_name', 'short_name', 'gender', 'dob', 'email', 'phone', 'mobile',
            'joined_date', 'designation', 'resigned_date', 'bank_name', 'branch', 'account_name', 'account_no', 'epf_no',
            'etf_no', 'pay_rate', 'notes', 'is_active'];
        $data = $this->getTableData($request, $columns, $searchingColumns);
        /** The Current User Can do the action */
        $can = [
            'show' => can('show', $this->model),
            'edit' => can('edit', $this->model),
            'delete' => can('delete', $this->model),
        ];

        $data['data'] = array_map(function ($item) use ($can) {
            if (array_get($can, 'show')) {
                $item['code'] = '<a href="' . route('setting.staff.show', $item['id']) . '">' . $item['code'] . '</a>';
            }

            $item['action'] = "<div class=\"button-group\">";
            /** The Current User Can show The staff and generate staff show button for index */
            if (array_get($can, 'show')) {
                $item['action'] .= actionBtn('Show', null, ['setting.staff.show', [$item['id']]], ['class' => 'btn-success']);
            }
            /** The Current User Can edit The staff and generate staff edit button for index */
            if (array_get($can, 'edit')) {
                $item['action'] .= actionBtn('Edit', null, ['setting.staff.edit', [$item['id']]]);
            }
            /** The Current User Can delete The staff and generate staff delete button for index */
            if (array_get($can, 'delete')) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-staff']);
            }
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    /**
     * store the new staff
     * @param StaffStoreRequest $request
     * @return Model
     */
    public function store(StaffStoreRequest $request): Model
    {
        $request->merge(['code' => $this->getCode()]);
        $staff = $this->model->fill($request->toArray());
        $staff->save();

        /** associate address */
        $address = Address::create($request->toArray());
        $staff->addresses()->save($address);

        /** create system user */
        if ($request->input('create_user') == 'Yes') {
            $request->merge(['password' => Hash::make($request->input('password'))]);
            $request->merge(['prefix' => $request->input('prefix_first') . '/' . $request->input('prefix_last')]);
            $request->merge(['is_active' => $request->input('is_active')]);
            $request->merge(['tfa' => 'No']);
            $user = User::create($request->toArray());
            $staff->user()->associate($user);
            $staff->save();

            /** associate business types */
            $businessTypes = $request->input('business_type');
            $businessTypesArray = explode(',', $businessTypes);
            $staff->businessTypes()->attach($businessTypesArray);
        }

        /** upload staff image to storage - if image attached only */
        $staffImg = $request->file('staff_image');
        if ($staffImg) {
            $staffImgType = $staffImg->getClientOriginalExtension();
            $staffImgName = $staff->getAttribute('code') . '.' . $staffImgType;
            Storage::put($this->imagePath . $staffImgName, file_get_contents($staffImg));

            /** update staff image name to row item */
            $staff->setAttribute('profile_image', $staffImgName);
            $staff->save();
        }

        /** associate staff with reps table */
        if ($request->input('is_sales_rep') == 'Yes') {
            $rep = new Rep();
            $rep->setAttribute('code', $staff->getAttribute('code'));
            $rep->setAttribute('name', $staff->getAttribute('full_name'));
            $rep->setAttribute('staff_id', $staff->getAttribute('id'));
            $rep->setAttribute('vehicle_id', $request->input('vehicle_id'));
            $rep->setAttribute('cl_amount', $request->input('cl_amount'));
            $rep->setAttribute('cl_notify_rate', $request->input('cl_notify_rate'));
            $rep->save();

            /** associate rep with a route */
            $route = explode(',', $request->input('route_id'));
            $rep->routes()->attach($route);
        }

        return $staff;
    }

    /**
     * Update Staff details
     * @param StaffUpdateRequest $request
     * @param Staff $staff
     * @return Staff
     */
    public function update(StaffUpdateRequest $request, Staff $staff): Staff
    {
        // merge existing unique code
        $request->merge(['code' => $staff->code]);
        /**
         * Save the staff
         * @var Staff $staff
         */
        $this->setModel($staff);
        $this->model->update($request->toArray());
        // save address
        /** @var Address $address */
        $address = $staff->addresses->first();
        if ($address) {
            $address->update($request->toArray());
        }
        // create user
        if ($request->input('create_user') == 'Yes') {
            $user = $staff->user;
            if ($user) {
                // Update existing user
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->role_id = $request->input('role');
                $user->prefix = $request->input('prefix_first') . '/' . $request->input('prefix_last');
                if ($request->input('password')) {
                    $user->password = Hash::make($request->input('password'));
                }
                $user->is_active = $request->input('is_active');
                $user->save();

                /** remove available business types */
                $staff->businessTypes()->detach();

                /** associate business types */
                $businessTypes = $request->input('business_type');
                $businessTypesArray = explode(',', $businessTypes);
                $staff->businessTypes()->attach($businessTypesArray);

            } else {
                // create new user
                $request->merge(['password' => Hash::make($request->input('password'))]);
                $request->merge(['prefix' => $request->input('prefix_first') . '/' . $request->input('prefix_last')]);
                $request->merge(['is_active' => $request->input('is_active')]);
                $user = User::create($request->toArray());
                $staff->user()->associate($user);
                $staff->save();

                /** associate business types */
                $businessTypes = $request->input('business_type');
                $businessTypesArray = explode(',', $businessTypes);
                $staff->businessTypes()->attach($businessTypesArray);
            }
        } else {
            // delete existing user
            $user = $staff->user;
            if ($user) {
                $user->delete();
            }
        }

        /** upload staff profile image to storage - if image attached only */
        $staffImg = $request->file('staff_image');
        if ($staffImg) {
            /** remove already available staff image if new image attached */
            //Storage::delete($this->imagePath . $staff->getAttribute('profile_image'));
            //$staff->setAttribute('profile_image', null);
            //$staff->save();

            /** upload the new staff image to storage and update raw data item */
            $staffImgType = $staffImg->getClientOriginalExtension();
            $staffImgName = $staff->getAttribute('code') . '.' . $staffImgType;
            Storage::put($this->imagePath . $staffImgName, file_get_contents($staffImg));

            /** update profile image name to row item */
            $staff->setAttribute('profile_image', $staffImgName);
            $staff->save();
        }

        /** associate staff with reps table */
        if ($request->input('is_sales_rep') == 'Yes') {
            $rep = $staff->rep;
            if ($rep) {
                /** update if rep already existing */
                $rep->name = $request->input('full_name');
                $rep->vehicle_id = $request->input('vehicle_id');
                $rep->cl_amount = $request->input('cl_amount');
                $rep->cl_notify_rate = $request->input('cl_notify_rate');
                $rep->save();

                /** associate rep with a route */
                $route = explode(',', $request->input('route_id'));
                $rep->routes()->sync($route);

            } else {
                /** create new if rep not existing */
                $rep = new Rep();
                $rep->setAttribute('code', $staff->getAttribute('code'));
                $rep->setAttribute('name', $staff->getAttribute('full_name'));
                $rep->setAttribute('staff_id', $staff->getAttribute('id'));
                $rep->setAttribute('vehicle_id', $request->input('vehicle_id'));
                $rep->setAttribute('cl_amount', $request->input('cl_amount'));
                $rep->setAttribute('cl_notify_rate', $request->input('cl_notify_rate'));
                $rep->save();

                /** associate rep with a route */
                $route = explode(',', $request->input('route_id'));
                $rep->routes()->attach($route);
            }
        } else {
            /** delete existing rep details  */
            $rep = $staff->rep;
            if ($rep) {
                $rep->delete();
            }
        }
        return $staff;
    }

    /**
     * Delete the staff
     * @param Staff $staff
     * @return array
     * @throws \Exception
     */
    public function delete(Staff $staff): array
    {
        $staff->rep()->delete();
        $staff->user()->delete();
        $staff->delete();
        return ['success' => true, 'message' => 'Staff deleted successfully!'];
    }

    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Get the breadcrumbs of the staff module
     * @param string $method
     * @param Staff|null $staff
     * @return array|mixed
     */
    public function breadcrumbs(string $method, Staff $staff = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Staff'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Staff', 'route' => 'setting.staff.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Staff', 'route' => 'setting.staff.index'],
                ['text' => $staff->full_name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Staff', 'route' => 'setting.staff.index'],
                ['text' => $staff->full_name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }

    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $designation = \request()->input('designation');
        $company = \request()->input('company');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $staffs = Staff::with('designation', 'user', 'user.role', 'companies')->orderBy('id', 'desc');
        if ($search) {
            $staffs->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('full_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('short_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $search . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $search . '%');
            });
        }
        switch ($filter) {
            case 'Active':
                $staffs->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $staffs->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $staffs->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $staffs->where('updated_at', '>', $lastWeek);
                break;
        }
        if ($designation) {
            $staffs->where('designation_id', $designation);
        }
        if ($company) {
            $staffs->whereHas('companies', function ($q) use ($company) {
                $q->where('id', $company);
            });
        }
        return $staffs->paginate(15)->toArray();
    }
}