<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\BusinessTypeStoreRequest;
use App\Repositories\BaseRepository;
use App\BusinessType;
use Illuminate\Http\Request;

/**
 * Class BusinessTypeRepository
 * @package App\Repositories\Settings
 */
class BusinessTypeRepository extends BaseRepository
{
    /**
     * BusinessTypeRepository constructor.
     * @param BusinessType|null $businessType
     */
    public function __construct(BusinessType $businessType = null)
    {
        $this->setModel($businessType ?? new BusinessType());
        $this->setCodePrefix('BT');
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['code', 'name', 'notes', 'is_active'];
        $searchingColumns = ['code', 'name', 'notes', 'is_active'];
        $data = $this->getTableData($request, $columns, $searchingColumns);
        $data['data'] = array_map(function ($item) {
            $item['code'] = '<a href="' . route('setting.business.type.show', $item['id']) . '">' . $item['code'] . '</a>';
            $item['action'] = "<div class=\"button-group\">";
            if (can('show', $this->getModel())) {
                $item['action'] .= actionBtn('Show', null, ['setting.business.type.show', [$item['id']]], ['class' => 'btn-success']);
            }
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, ['setting.business.type.edit', [$item['id']]]);
            }
            if (can('delete', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-business-type']);
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
        $types = BusinessType::orderBy('created_at', 'desc');
        if ($search) {
            $types->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%');
            });
        }

        switch ($filter) {
            case 'Active':
                $types->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $types->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $types->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $types->where('updated_at', '>', $lastWeek);
                break;
        }

        return $types->paginate(12)->toArray();
    }

    public function save(BusinessTypeStoreRequest $request)
    {
        $request->merge(['code' => $this->getCode()]);
        $businessType = $this->model->fill($request->toArray());
        $businessType->save();
        return $businessType;
    }

    /**
     * @param BusinessTypeStoreRequest $request
     * @param BusinessType $businessType
     * @return BusinessType
     */
    public function update(BusinessTypeStoreRequest $request, BusinessType $businessType)
    {
        $request->merge(['code' => $businessType->code]);
        $this->setModel($businessType);
        $this->model->update($request->toArray());
        return $businessType;
    }

    /**
     * @param BusinessType $businessType
     * @return array
     * @throws \Exception
     */
    public function delete(BusinessType $businessType): array
    {
        $businessType->delete();
        return ['success' => true];
    }

    /**
     * Get the breadcrumbs of the business type module
     * @param string $method
     * @param BusinessType|null $businessType
     * @return array|mixed
     */
    public function breadcrumbs(string $method, BusinessType $businessType = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Business Types'],
            ],
            'create' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Business Types', 'route' => 'setting.business.type.index'],
                ['text' => 'Create'],
            ],
            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Business Types', 'route' => 'setting.business.type.index'],
                ['text' => $businessType->name ?? ''],
            ],
            'edit' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Business Types', 'route' => 'setting.business.type.index'],
                ['text' => $businessType->name ?? ''],
                ['text' => 'Edit'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}