<?php

namespace App\Policies\Sales;

use App\Invoice;
use App\Policies\Policy;

/**
 * Class InvoiceOrderPolicy
 * @package App\Policies\Sales
 * @property bool $check
 * @property array $replace
 * @property array $policies
 * @property Invoice $model
 */
class InvoicePolicy extends Policy
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
        'generateInvoice' => 'create',
        'recordPayment' => 'create',
        'cancelInvoice' => 'edit',
        'printView' => 'print',
        'cancel' => 'edit',
        'refund' => 'edit'
    ];
    /**
     * @var array $policies The attributes that are main methods .
     */
    public $policies = [
        'index', 'create', 'edit', 'view', 'delete', 'print', 'export'
    ];
    /**
     * @var Invoice $model The attributes that are related model class .
     */
    public $model = Invoice::class;

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
    public function generateInvoice()
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
    public function cancel()
    {
        return $this->edit();
    }

    /**
     * @return bool
     */
    public function refund()
    {
        return $this->edit();
    }

    /**
     * @return bool
     */
    public function printView()
    {
        return $this->print();
    }
}
