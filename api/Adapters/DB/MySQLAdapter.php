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
     *
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
            $this->handler->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            throw $e;
        }
    }


    /**
     * MySQLAdapter Destructor.
     */
    public function __destruct()
    {
        $this->handler = null;
    }


// ______________________________________________________________________________________________________________ public


    /**
     * Common SELECT from a single table.
     *
     * @param $table
     * @return string
     */
    public function select($table)
    {
        return "Hello from MySQL " . $table;
    }


    /**
    * Common INSERT in to a single table.
    */
    public function insert()
    {

    }


    /**
    * Common UPDATE to a single table.
    */
    public function update()
    {

    }


    /**
    * Common DELETE from a single table.
    */
    public function delete()
    {

    }


    /**
    * Execute a raw query.
    */
    public function query($statement)
    {
        $query = $this->handler->query($statement);

        return $query->fetchAll();
    }


// _____________________________________________________________________________________________________________ private


    /**
     * Begin a transaction.
     */
    private function transactionBegin()
    {
        // check already in a transaction and if not start the transaction
        if(!$this->handler->inTransaction())
        {
            $this->handler->beginTransaction();
        }
    }


    /**
     * Commit the transaction.
     */
    private function transactionCommit()
    {
        $this->handler->commit();
    }


    /**
     * Rollback the transaction.
     */
    private function transactionRollback()
    {
        $this->handler->rollBack();
    }

}
