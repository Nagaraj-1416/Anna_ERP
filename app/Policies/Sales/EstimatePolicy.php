<?php

namespace App\Policies\Sales;

use App\Estimate;
use App\InvoicePayment;
use App\Policies\Policy;

/**
 * Class EstimatePolicy
 * @package App\Policies\Sales
 * @property bool $check
 * @property array $replace
 * @property array $policies
 * @property Estimate $model
 */
class EstimatePolicy extends Policy
{
    /**
     * @var array $replace The attributes that are replacement methods.
     */
    protected $replace = [
        'store' => 'create',
        'show' => 'view',
        'update' => 'edit',
        'copy' => 'clone',
        'printView' => 'print'
    ];
    /**
     * @var array $policies The attributes that are main methods .
     */
    public $policies = [
        'index', 'create', 'view', 'edit', 'export', 'print', 'clone', 'send', 'accept', 'decline'
    ];
    /**
     * @var Estimate $model The attributes that are related model class .
     */
    public $model = Estimate::class;

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
     *
     */
    public function clone()
    {
        $this->check;
    }

    /**
     * @return bool
     */
    public function printView()
    {
        return $this->print();
    }

    /**
     * @return bool
     */
    public function send()
    {
        return $this->check;
    }

    /**
     * @return bool
     */
    public function accept()
    {
        return $this->check;
    }

    /**
     * @return bool
     */
    public function decline()
    {
        return $this->check;
    }
}