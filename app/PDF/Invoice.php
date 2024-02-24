<?php

namespace App\PDF;


use App\PDF\Templates\Template;

class Invoice extends PDF implements Template
{
    public function __construct()
    {
        parent::__construct('Invoice');
    }
}