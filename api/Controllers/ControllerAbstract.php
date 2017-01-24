<?php

namespace Api\Controllers;

abstract class ControllerAbstract
{
    protected $validator;

    public function __construct()
    {
        $this->validator = $GLOBALS['validator'];
    }
}