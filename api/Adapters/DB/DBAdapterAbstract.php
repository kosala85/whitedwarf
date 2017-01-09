<?php

namespace Api\Adapters\DB;

abstract class DBAdapterAbstract
{
    protected $host;
    protected $user;
    protected $password;
    protected $schema;


    /**
     * DBAdapterAbstract constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->schema = $config['schema'];
    }

}
