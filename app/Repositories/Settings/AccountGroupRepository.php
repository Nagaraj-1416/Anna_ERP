<?php

namespace App\Repositories\Settings;

use App\AccountGroup;
use App\Repositories\BaseRepository;

/**
 * Class AccountGroupRepository
 * @package App\Repositories\Settings
 */
class AccountGroupRepository extends BaseRepository
{
    /**
     * AccountGroupRepository constructor.
     * @param AccountGroup|null $group
     */
    public function __construct(AccountGroup $group = null)
    {
        $this->setModel($group ?? new AccountGroup());
    }

    /**
     * @return mixed
     */
    public function grid()
    {
        $search = \request()->input('search');
        $filter = \request()->input('filter');
        $lastWeek = carbon()->subWeek();
        $groups = $this->model->orderBy('id', 'desc')->with('parent', 'category');
        if ($search) {
            $groups->where(function ($q) use ($search) {
                $q->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        switch ($filter) {
            case 'Active':
                $groups->where('is_active', 'Yes');
                break;
            case 'Inactive':
                $groups->where('is_active', 'No');
                break;
            case 'recentlyCreated':
                $groups->where('created_at', '>', $lastWeek);
                break;
            case 'recentlyUpdated':
                $groups->where('updated_at', '>', $lastWeek);
                break;
        }

        return $groups->paginate(12)->toArray();
    }

    /**
     * @param AccountGroup $accountGroup
     * @return array
     */
    public function delete(AccountGroup $accountGroup)
    {
        try {
            if (count($accountGroup->children)|| count($accountGroup->accounts)){
                return ['success' => false, 'message' => 'This group associated with items'];
            }
            $accountGroup->delete();
            return ['success' => true, 'message' => 'deleted success'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'deleted failed'];
        }
    }

    /**
     * Get the breadcrumbs of the user module
     * @param string $method
     * @param AccountGroup|null $group
     * @return array|mixed
     */
    public function breadcrumbs(AccountGroup $group = null, string $method = null): array
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
                ['text' => 'Account Group'],
            ]),
            'create' => array_merge($base, [
                ['text' => 'Account Group', 'route' => 'setting.account.group.index'],
                ['text' => 'Create']
            ]),
            'show' => array_merge($base, [
                ['text' => 'Account Group', 'route' => 'setting.account.group.index'],
                ['text' => $group->name ?? ''],
            ]),
            'edit' => array_merge($base, [
                ['text' => 'Account Group', 'route' => 'setting.account.group.index'],
                ['text' => $group->name ?? ''],
                ['text' => 'Edit'],
            ])
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}