<?php

namespace Api\Logic;

abstract class LogicAbstract
{
    protected $db;

    public function __construct()
    {
        // NOTE: only use the db object to manage transactions in the Logic layer and avoid using it to issue database
        //       queries directly. Do those in the Repository layer.
        $this->db = $GLOBALS['db'];
    }
}