<?php

namespace App\Policies\Purchase;

use App\Bill;
use App\Policies\Policy;
use App\BusinessType;

/**
 * Class BillPolicy
 * @package App\Policies\Purchase
 * @property bool $check
 * @property array $replace
 * @property array $policies
 * @property Bill $model
 */
class BillPolicy extends Policy
{
    /**
     * @var array $replace The attributes that are replacement methods.
     */
    protected $replace = [
        'store' => 'create',
        'update' => 'edit',
        'destroy' => 'delete',
        'show' => 'view',
        'confirm' => 'create',
        'generateBill' => 'create',
        'recordPayment' => 'create',
        'cancelBill' => 'edit',
        'printView' => 'print'
    ];
    /**
     * @var array $policies The attributes that are main methods .
     */
    public $policies = [
        'index', 'create', 'edit', 'view', 'delete', 'print', 'export'
    ];
    /**
     * @var BusinessType $model The attributes that are related model class .
     */
    public $model = Bill::class;

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

    /**
     * Check the permission to conform order
     * @return bool
     */
    public function confirm()
    {
        return $this->create();
    }

    /**
     * Check the permission to generate bill
     * @return bool
     */
    public function generateBill()
    {
        return $this->create();
    }

    /**
     * Check the permission to record payment
     * @return bool
     */
    public function recordPayment()
    {
        return $this->create();
    }

    /**
     * Check the permission to record payment
     * @return bool
     */
    public function clone()
    {
        return $this->check;
    }

    /**
     * Check the permission to approve
     * @return bool
     */
    public function approve()
    {
        return $this->check;
    }

    /**
     * Check the permission to convert
     * @return bool
     */
    public function convert()
    {
        return $this->check;
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
    public function cancelBill()
    {
        return $this->update();
    }

}
