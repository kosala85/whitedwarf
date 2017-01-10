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
     * @param $strTable
     * @param array $arrWhere
     * @param array $arrOrder
     * @param array $arrLimit
     * @param array $arrColumns
     * @return array
     */
    public function select($strTable,
                           array $arrWhere = [['column_1', '=', 'value'],['column_2', '=', 'value', 'or']],
                           array $arrOrder = [['column_1' => 'ASC'], ['column_2', 'DESC']],
                           array $arrLimit = [],
                           array $arrColumns = []
    )
    {
        $strColumns = empty($arrColumns) ? '*' : $this->generateColumns($arrColumns);
        $strConditions = empty($arrWhere) ? null : $this->generateWhereClause($arrWhere);
        $strOrder = empty($arrOrder) ? null : $this->generateOrdering($arrOrder);
        $strLimit = empty($arrLimit) ? null : $this->generateLimit($arrLimit);

        $query = 'SELECT ' . $strColumns . ' FROM ' . $strTable;

        if(!is_null($strConditions))
        {
            $query .= ' WHERE ' . $strConditions;
        }

        if(!is_null($strOrder))
        {
            $query .= ' ORDER BY ' . $strOrder;
        }

        if(!is_null($strLimit))
        {
            $query .= ' LIMIT ' . $strLimit;
        }

        $statement = $this->handler->prepare($query);
        $statement->execute();

        return $statement->fetchAll();
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


    /**
     * Generate columns list.
     *
     * @param array $arrColumns
     * @return string
     */
    private function generateColumns(array $arrColumns)
    {
        $strColumns = "";

        foreach($arrColumns as $column)
        {
            $strColumns .= $column . ',';
        }

        // remove the trailing comma and return
        return rtrim($strColumns, ',');
    }


    private function generateWhereClause(array $arrWhere)
    {

    }


    private function generateOrdering(array $arrOrder)
    {

    }


    /**
     * Generate query limit boundaries.
     *
     * @param array $arrLimit
     * @return string
     */
    private function generateLimit(array $arrLimit)
    {
        return $arrLimit[0] . ', ' . $arrLimit[1];
    }

}
