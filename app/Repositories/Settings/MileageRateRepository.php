<?php

namespace App\Repositories\Settings;


use App\Repositories\BaseRepository;
use App\{
    MileageRate
};
use Illuminate\Http\Request;

/**
 * Class MileageRateRepository
 * @package App\Repositories\Settings
 */
class MileageRateRepository extends BaseRepository
{
    /**
     * MileageRateRepository constructor.
     * @param MileageRate|null $mileageRate
     */
    public function __construct(MileageRate $mileageRate = null)
    {
        $this->setModel($mileageRate ?? new MileageRate());
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['date', 'rate'];
        $searchingColumns = ['date', 'rate'];
        $data = $this->getTableData($request, $columns, $searchingColumns);
        $data['data'] = array_map(function ($item) {
            $item['action'] = "<div class=\"button-group\">";
            $item['action'] .= actionBtn('Edit', null, ['setting.mileage.rate.edit', [$item['id']]]);
            $item['action'] .= actionBtn('Delete', null, [], ['data-id' => $item['id'], 'class' => 'btn-danger delete-role']);
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Request $request){
        $request->merge(['prepared_by' => auth()->id()]);
        return $this->storeItem($request->toArray());
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function update(Request $request){
        return $this->updateItem($this->model->id, $request->toArray());
    }

    /**
     * @return array
     */
    public function delete(){
        return $this->destroy();
    }

    /**
     * Get the breadcrumbs of the mileage rate module
     * @param string $method
     * @param MileageRate|null $mileageRate
     * @return array|mixed
     */
    public function breadcrumbs(MileageRate $mileageRate = null, string $method = null): array
    {
        if (!$method) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $method = $backtrace[1]['function'] ?? null;
        }
        $base = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Settings', 'route' => 'setting.index'],
        ];
        $breadcrumbs = [
            'index' => array_merge($base, [
                ['text' => 'Mileage Rate'],
            ]),
            'create' => array_merge($base, [
                ['text' => 'Mileage Rate', 'route' => 'setting.mileage.rate.index'],
                ['text' => 'Create']
            ]),
            'show' => array_merge($base, [
                ['text' => 'Mileage Rate', 'route' => 'setting.mileage.rate.index'],
                ['text' => $mileageRate->date ?? ''],
            ]),
            'edit' => array_merge($base, [
                ['text' => 'Mileage Rate', 'route' => 'setting.mileage.rate.index'],
                ['text' => $mileageRate->date ?? ''],
                ['text' => 'Edit'],
            ])
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}