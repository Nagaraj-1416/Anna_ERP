<?php

namespace App\Policies\Sales;

use App\CustomerCreditRefund;
use App\Policies\Policy;

/**
 * Class CustomerCreditRefundPolicy
 * @package App\Policies\Sales
 * @property bool $check
 * @property array $replace
 * @property array $policies
 * @property CustomerCreditRefund $model
 */
class CustomerCreditRefundPolicy extends Policy
{
    /**
     * @var array $replace The attributes that are replacement methods.
     */
    protected $replace = [
        'store' => 'create',
        'update' => 'edit',
        'show' => 'view',
        'printView' => 'print'
    ];
    /**
     * @var array $policies The attributes that are main methods .
     */
    public $policies = [
        'index', 'create', 'edit', 'view', 'delete', 'print', 'export',
    ];
    /**
     * @var CustomerCreditRefund $model The attributes that are related model class .
     */
    public $model = CustomerCreditRefund::class;

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
     * Check the permission to print
     * @return bool
     */
    public function print()
    {
        return $this->check;
    }

    /**
     * Check the permission to export
     * @return bool
     */
    public function export()
    {
        return $this->check;
    }


    /**
     * @return bool
     */
    public function printView()
    {
        return $this->print();
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
