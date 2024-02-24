<?php

namespace App\Policies\Sales;

use App\InvoicePayment;
use App\Policies\Policy;

/**
 * Class InvoicePaymentPolicy
 * @package App\Policies\Sales
 * @property bool $check
 * @property array $replace
 * @property array $policies
 * @property InvoicePayment $model
 */
class InvoicePaymentPolicy extends Policy
{
    /**
     * @var array $replace The attributes that are replacement methods.
     */
    protected $replace = [
        'store' => 'create',
        'show' => 'view',
        'update' => 'edit',
        'cancel' => 'edit',
        'refund' => 'edit'
    ];
    /**
     * @var array $policies The attributes that are main methods .
     */
    public $policies = [
        'index', 'create', 'view', 'edit', 'export', 'print'
    ];
    /**
     * @var InvoicePayment $model The attributes that are related model class .
     */
    public $model = InvoicePayment::class;

    /**
     * @return bool
     */
    public function index()
    {
        return $this->check;
    }

    /**
     * @return bool
     */
    public function create()
    {
        return $this->check;
    }

    /**
     * @return bool
     */
    public function store()
    {
        return $this->create();
    }

    /**
     * @return bool
     */
    public function view()
    {
        return $this->check;
    }

    /**
     * @return bool
     */
    public function show()
    {
        return $this->view();
    }

    /**
     * @return bool
     */
    public function edit()
    {
        return $this->check;
    }

    /**
     * @return bool
     */
    public function update()
    {
        return $this->edit();
    }

    /**
     * @return bool
     */
    public function export()
    {
        return $this->check;
    }

    /**
     * @return bool
     */
    public function print()
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
}