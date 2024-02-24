<?php

namespace App\Repositories\Settings;

use App\{
    User
};
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserRepository
 * @package App\Repositories\Settings
 */
class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     * @param User|null $user
     */
    public function __construct(User $user = null)
    {
        $this->setModel($user ?? new User());
        $this->setCodePrefix('STF');
    }

    /**
     * Get data to data table
     * @param Request $request
     * @return array
     */
    public function dataTable(Request $request): array
    {
        $columns = ['name', 'is_active'];
        $searchingColumns = ['name', 'is_active'];
        $relationColumns = [
            'role' => [
                [
                    'column' => 'name', 'as' => 'role_name'
                ]
            ]
        ];
        $data = $this->getTableData($request, $columns, $searchingColumns, $relationColumns);
        /** The Current User Can do the action */
        $can = [
            'show' => can('show', $this->model),
        ];

        $data['data'] = array_map(function ($item) use ($can) {
            if (array_get($can, 'show')) {
                $item['name'] = '<a href="' . route('setting.user.show', $item['id']) . '">' . $item['name'] . '</a>';
                $item['action'] = "<div class=\"button-group\">";
                $item['action'] .= actionBtn('Show', null, ['setting.user.show', [$item['id']]], ['class' => 'btn-success']);
                $item['action'] .= "</div>";
            }
            return $item;
        }, $data['data']);
        return $data;
    }

    /**
     * Get the breadcrumbs of the user module
     * @param string $method
     * @param User|null $user
     * @return array|mixed
     */
    public function breadcrumbs(string $method, User $user = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Users'],
            ],

            'show' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => 'Users', 'route' => 'setting.user.index'],
                ['text' => $user->name ?? ''],
            ],

        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}