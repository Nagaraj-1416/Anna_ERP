<?php

namespace App\Policies\Settings;

use App\Policies\Policy;
use App\Rep;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class RepPolicy
 * @package App\Policies\Settings
 * @property bool $check
 * @property array $replace
 * @property array $policies
 * @property Rep $model
 */
class RepPolicy extends Policy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    /**
     * @var array $replace The attributes that are replacement methods.
     */
    protected $replace = [
        'store' => 'create',
        'update' => 'edit',
        'destroy' => 'delete',
        'show' => 'view',
    ];
    /**
     * @var array $policies The attributes that are main methods .
     */
    public $policies = ['index', 'create', 'edit', 'view', 'delete'];
    /**
     * @var Rep $model The attributes that are related model class .
     */
    public $model = Rep::class;

    /**
     * Check the permission to index
     * @return bool
     */
    public function index()
    {
        return $this->check;
    }

    /**
     * Check the permission to create
     * @return bool
     */
    public function create()
    {
        return $this->check;
    }

    /**
     * Check the permission to store
     * @return bool
     */
    public function store()
    {
        return $this->create();
    }

    /**
     * Check the permission to edit
     * @return bool
     */
    public function edit()
    {
        return $this->check;
    }

    /**
     * Check the permission to update
     * @return bool
     */
    public function update()
    {
        return $this->edit();
    }

    /**
     * Check the permission to delete
     * @return bool
     */
    public function delete()
    {
        return $this->check;
    }

    /**
     * Check the permission to destroy
     * @return bool
     */
    public function destroy()
    {
        return $this->delete();
    }

    /**
     * Check the permission to show
     * @return bool
     */
    public function view()
    {
        return $this->check;
    }

    /**
     * Check the permission to show
     * @return bool
     */
    public function show()
    {
        return $this->view();
    }

}
