<?php

namespace App\Policies\Purchase;

use App\Bill;
use App\BillPayment;
use App\Policies\Policy;
use App\BusinessType;
use App\PurchaseOrder;

/**
 * Class BillPaymentPolicy
 * @package App\Policies\Purchase
 * @property bool $check
 * @property array $replace
 * @property array $policies
 * @property BillPayment $model
 */
class BillPaymentPolicy extends Policy
{
    /**
     * @var array $replace The attributes that are replacement methods.
     */
    protected $replace = [
        'store' => 'create',
        'update' => 'edit',
        'printView' => 'print',
    ];
    /**
     * @var array $policies The attributes that are main methods .
     */
    public $policies = [
        'create', 'edit', 'delete', 'print', 'export', 'refund', 'cancel'
    ];
    /**
     * @var BusinessType $model The attributes that are related model class .
     */
    public $model = BillPayment::class;

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
    public function cancel()
    {
        return $this->check;
    }

    /**
     * @return bool
     */
    public function refund()
    {
        return $this->check;
    }

    public function printView()
    {
        return $this->print();
    }
}
