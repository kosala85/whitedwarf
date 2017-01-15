<?php

namespace Api\Adapters\DB;

abstract class DBAdapterAbstract
{
    protected $strHost;
    protected $strUser;
    protected $strPassword;
    protected $strSchema;


    /**
     * DBAdapterAbstract constructor.
     *
     * @param $arrConfig
     */
    public function __construct($arrConfig)
    {
        $this->strHost = $arrConfig['host'];
        $this->strUser = $arrConfig['user'];
        $this->strPassword = $arrConfig['password'];
        $this->strSchema = $arrConfig['schema'];
    }

}
