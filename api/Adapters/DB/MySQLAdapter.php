<?php

namespace Api\Adapters\DB;

use \PDO;

class MySQLAdapter extends DBAdapterAbstract
{
    const QUERY_TYPE_SELECT = 'S';
    const QUERY_TYPE_INSERT = 'I';
    const QUERY_TYPE_UPDATE = 'U';
    const QUERY_TYPE_DELETE = 'D';

    protected $handler = null;


// _______________________________________________________________________________________________________ magic methods


    /**
     * MySQLAdapter constructor.
     *
     * @param $arrConfig
     */
    public function __construct($arrConfig)
    {
        parent::__construct($arrConfig);

        // create the connection string
        $connectionString = "mysql:host={$this->strHost};dbname={$this->strSchema}";

        // create the db handler
        $this->handler = new PDO($connectionString, $this->strUser, $this->strPassword);

        $this->handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->handler->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
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
    public function select($strTable, array $arrWhere = [], array $arrOrder = [], array $arrLimit = [], array $arrColumns = [])
    {
        $strColumns = empty($arrColumns) ? '*' : $this->generateColumns($arrColumns);
        $strConditions = empty($arrWhere) ? null : $this->generateWhereClause($arrWhere);
        $strOrder = empty($arrOrder) ? null : $this->generateOrdering($arrOrder);
        $strLimit = empty($arrLimit) ? null : $this->generateLimit($arrLimit);
        $arrValues = null;

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

        // @TODO: merge all value arrays in to a single associative array
        $arrValues[0] = $arrWhere;
        $arrValues[1] = $arrOrder;
        $arrValues[2] = $arrLimit;

        $arrValues = $this->generateValuesArray($arrValues);
        $statement->execute($arrValues);

        return $statement->fetchAll();
    }


    /**
    * Common INSERT in to a single table.
    * $arrColumns ['column_1', 'column_2', ...]
    * $arrValues [[value_1, value_2, ...], [value_1, value_2, ...]]
    */
    public function insert($strTable, array $arrColumns = [], array $arrValues = [])
    {
        $strColumns = empty($arrColumns) ? '' : $this->generateColumns($arrColumns);
        $strPlaceholders = '';

        foreach($arrColumns as $strColumn)
        {
            $strPlaceholders .= ':' . $strColumn . ',';
        }

        $strPlaceholders = rtrim($strPlaceholders, ',');
        
        $query = 'INSERT INTO ' . $strTable . '(' . $strColumns . ')' . ' VALUES (' . $strPlaceholders . ')';

        $statement = $this->handler->prepare($query);

        foreach($arrValues as $arrRow)
        {
            $statement->execute($arrRow);
        }
    }


    /**
    * Common UPDATE to a single table.
    * $arrColumns ['column_1', 'column_2', ...]
    * $arrValues [value_1, value_2, ...]
    */
    public function update($strTable, array $arrWhere, array $arrColumns = [], array $arrValues = [])
    {
        $strColumns = empty($arrColumns) ? '' : $this->generateColumns($arrColumns);
        $strConditions = empty($arrWhere) ? null : $this->generateWhereClause($arrWhere);
        $strPlaceholders = '';

        foreach($arrColumns as $strColumn)
        {
            $strPlaceholders .= $strColumn . '= :' . $strColumn . ',';
        }

        $strPlaceholders = rtrim($strPlaceholders, ',');
        
        $query = 'UPDATE ' . $strTable . ' SET ' . $strPlaceholders;

        if(!is_null($strConditions))
        {
            $query .= ' WHERE ' . $strConditions;
        }

        $statement = $this->handler->prepare($query);

        $statement->execute($arrValues);
        
        // return affected raw count
    }


    /**
    * Common DELETE from a single table.
    */
    public function delete($strTable, $arrWhere)
    {
        $strConditions = empty($arrWhere) ? null : $this->generateWhereClause($arrWhere);
        $strPlaceholders = '';

        $query = 'DELETE FROM ' . $strTable;

        if(!is_null($strConditions))
        {
            $query .= ' WHERE ' . $strConditions;
        }

        $statement = $this->handler->prepare($query);

        $statement->execute();

        // return affected raw count
    }


    /**
     * Execute a raw query.
     *
     * @param $strQuery
     * @param $arrValues
     * @return array
     */
    public function query($strQuery, $arrValues)
    {
        $statement = $this->handler->prepare($strQuery);

        $statement->execute($arrValues);

        // check query type
        if(strtoupper(substr(ltrim($strQuery), 0, 1)) === self::QUERY_TYPE_SELECT)
        {
            // if SELECT return all results as an associative array
            return $statement->fetchAll();
        }

        // if INSERT, UPDATE or DELETE return affected rows
        return ['affected_rows' => $statement->rowCount()];
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
        $strColumns = '';

        foreach($arrColumns as $strColumn)
        {
            $strColumns .= $strColumn . ',';
        }

        // remove the trailing comma and return
        return rtrim($strColumns, ',');
    }


    /*
    *   [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
    *   ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
    */
    private function generateWhereClause(array $arrWhere)
    {
        $strWhere = '';
        $strTemp = ''; // holds IN values temporeraly

        foreach($arrWhere as $arrCondition)
        {
            // if $arrCondition has a value at index 3 treat as an 'OR'
            !is_null($arrCondition[3]) ? $strWhere .= ' AND ' : $strWhere .= ' OR ';

            if($arrCondition[1] == 'IN')
            {
                // clean before starting
                $strTemp = '';

                foreach($arrCondition[2] as $arrInValue)
                {
                    $strTemp .= ':' . $arrInValue . ','; // @TODO: need to generate a unique key value for the placeholder
                }

                $strTemp = rtrim($strTemp, ',');

                $strWhere .= $arrCondition[0] . ' ' . $arrCondition[1] . ' (' . $strTemp . ')';
            }
            elseif($arrCondition[1] == 'BETWEEN')
            {
                $strWhere .= $arrCondition[0] . ' ' . $arrCondition[1] . ' :from' . $arrCondition[0] . ', :to' . $arrCondition[0]; // set placeholder
            }
            else
            {
                $strWhere .= $arrCondition[0] . ' ' . $arrCondition[1] . ' :' . $arrCondition[0]; // set placeholder
            }
            
            // trim leading ' AND '
            $strWhere = ltrim($strWhere, ' AND ');
            
            return $strWhere;
        }
    }


    /*
    *   [['column_1' => 'ASC'], ['column_2', 'DESC']]
    */
    private function generateOrdering(array $arrOrder)
    {
        $strOrder = '';

        foreach($arrOrder as $arrCriteria)
        {
            $strOrder .= $arrCriteria[0] . ' ' . $arrCriteria[1] . ',';
        }

        return rtrim($strOrder, ',');
    }


    /**
     * Generate query limit boundaries.
     *
     * @param array $arrLimit ['offset' => offset, 'limit' => limit]
     * @return string
     */
    private function generateLimit(array $arrLimit)
    {
        // set array keys as placeholders
        return ':' . $arrLimit[0] . ', :' . $arrLimit[1];
    }

}
