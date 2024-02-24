<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\WorkHourStoreRequest;
use App\Repositories\BaseRepository;
use App\WorkHour;
use App\User;
use Illuminate\Http\Request;

/**
 * Class UserRepository
 * @package App\Repositories\Settings
 */
class WorkHourRepository extends BaseRepository
{
    /**
     * WorkHourRepository constructor.
     * @param WorkHour|null $workHour
     */
    public function __construct(WorkHour $workHour = null)
    {
        $this->setModel($workHour ?? new WorkHour());
    }

    /**
     * @return mixed
     */
    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $workHours = WorkHour::orderBy('id', 'desc')
            ->with('user', 'staff', 'allocatedBy', 'terminatedBy', 'company');
        if ($search) {

        }

        switch ($filter) {
            case 'recentlyCreated':
                $workHours->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $workHours->where('updated_at', '>', $lastWeek);
                break;
        }

        return $workHours->paginate(12)->toArray();
    }

    /**
     * @param WorkHourStoreRequest $request
     * @return WorkHour
     */
    public function save(WorkHourStoreRequest $request): WorkHour
    {
        $user = User::where('id', $request->input('user_id'))->first();
        $staff = $user->staffs()->first();

        $company = userCompany($user);

        $request->merge(['staff' => 'Allocated']);
        $request->merge(['staff_id' => $staff->id]);
        $request->merge(['allocated_by' => auth()->id()]);
        $request->merge(['company_id' => $company->id ?? null]);

        $workHour = $this->model->fill($request->toArray());
        $workHour->save();

        return $workHour;
    }

    /**
     * @param WorkHour $workHour
     * @return array
     */
    public function statusChange(WorkHour $workHour)
    {
        $workHour->status = 'Terminated';
        $workHour->save();
        return ['success' => true];
    }

    /**
     * Get the breadcrumbs of the user module
     * @param string $method
     * @param WorkHour|null $workHour
     * @return array|mixed
     */
    public function breadcrumbs(string $method, WorkHour $workHour = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Work Hours'],
            ]

        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}