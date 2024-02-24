<?php

namespace App\Repositories\Settings;

use App\Rep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;

class RepRepository extends BaseRepository
{
    /**
     * RepRepository constructor.
     * @param Rep|null $rep
     */
    public function __construct(Rep $rep = null)
    {
        $this->setModel($rep ?? new Rep());
        $this->setCodePrefix('REP');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['code', 'name', 'notes', 'is_active'];
        $searchingColumns = ['code', 'name', 'notes', 'is_active'];
        $relationColumns = [
            'staff' => [
                [
                    'column' => 'short_name', 'as' => 'staff_name'
                ]
            ],
        ];
        $data = $this->getTableData($request, $columns, $searchingColumns, $relationColumns);
        $data['data'] = array_map(function ($item) {
            $item['code'] = '<a href="' . route('setting.rep.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('show', $this->getModel())) {
                $item['action'] .= actionBtn('Show', null, ['setting.rep.show', [$item['id']]], ['class' => 'btn-success']);
            }
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $reps = Rep::orderBy('id', 'desc')->with('staff');
        if ($search) {
            $reps->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('notes', 'LIKE', '%' . $search . '%')
                    ->orwhere(function ($query) use ($search) {
                        $query->whereHas('staff', function ($q) use ($search) {
                            $q->where('first_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('full_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('short_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('phone', 'LIKE', '%' . $search . '%')
                                ->orWhere('mobile', 'LIKE', '%' . $search . '%');
                        });
                    });
            });
        }
        switch ($filter) {
            case 'Active':
                $reps->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $reps->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $reps->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $reps->where('updated_at', '>', $lastWeek);
                break;
        }
        return $reps->paginate(12)->toArray();
    }

    /**
     * @param $request
     * @return Model
     */
    public function save($request): Model
    {
        $request->merge(['code' => $this->getCode()]);
        $rep = $this->model->fill($request->toArray());
        $rep->save();
        return $rep;
    }

    /**
     * @param $request
     * @param $rep
     */
    public function update($request, $rep)
    {
        $request->merge(['code' => $rep->code]);
        $this->setModel($rep);
        $this->model->update($request->toArray());
    }

    /**
     * @param Rep $rep
     * @return array
     * @throws \Exception
     */
    public function delete(Rep $rep): array
    {
        $rep->delete();
        return ['success' => true];
    }

    /**
     * @param string $method
     * @param Rep|null $rep
     * @return array|mixed
     */
    public function breadcrumbs(string $method, Rep $rep = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Sales Reps'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Sales Reps', 'route' => 'setting.rep.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Sales Reps', 'route' => 'setting.rep.index'],
                ['text' => $rep->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Sales Reps', 'route' => 'setting.rep.index'],
                ['text' => $rep->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}