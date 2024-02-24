<?php

namespace App\Mail\Templates;

interface Template
{
    public function getContent();

    public function getLinks();

    public function getLoops();

    public function getVariables();

    public function getTemplate();
}