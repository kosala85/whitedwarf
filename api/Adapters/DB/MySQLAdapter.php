<?php

namespace Api\Adapters\DB;

use \PDO;
use \PDOException;

class MySQLAdapter extends DBAdapterAbstract
{
    protected $handler = null;


// _______________________________________________________________________________________________________ magic methods


    /**
     * MySQLAdapter constructor.
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct($config);

        // create the connection string
        $connectionString = "mysql:host={$this->host};dbname={$this->schema}";

        // create the db handler
        try
        {
            $this->handler = new PDO($connectionString, $this->user, $this->password);
            $this->handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            throw $e;
        }
    }


    /**
     * MySQLAdapter Destructor
     */
    public function __destruct()
    {
        $this->handler = null;
    }


// ______________________________________________________________________________________________________________ public


    /**
     * Common SELECT from the database
     */
    public function select()
    {

    }

    public function insert()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function query()
    {

    }


// _____________________________________________________________________________________________________________ private


    private function connect()
    {

    }

    private function disconnect()
    {

    }

    private function transactionBegin()
    {

    }

    private function transactionCommit()
    {

    }

    private function transactionRollback()
    {

    }

}
