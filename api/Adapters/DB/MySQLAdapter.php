<?php

namespace Api\Adapters\DB;

use \PDO;

class MySQLAdapter extends DBAdapterAbstract
{
    const QUERY_TYPE_SELECT = 'S';
    const QUERY_TYPE_INSERT = 'I';
    const QUERY_TYPE_UPDATE = 'U';
    const QUERY_TYPE_DELETE = 'D';

    const DATA_TYPE_STRING = 'string';
    const DATA_TYPE_INTEGER = 'integer';
    const DATA_TYPE_DOUBLE = 'double';
    const DATA_TYPE_BOOLEAN = 'boolean';
    const DATA_TYPE_NULL = 'NULL';

    protected $handler = null;

    // track nested transactions
    private $transactionCounter = 0;


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
        $connectionString = "mysql:host={$this->strHost};dbname={$this->strSchema};charset=utf8";

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
     * SELECT from a table.
     *      (NOTE: Use this for simple to moderate queries)
     *
     * @param $strTable
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @param array $arrOrder [['column_1' => 'ASC'], ['column_2', 'DESC']]
     * @param array $arrLimit [offset, limit]
     * @param array $arrColumns ['column_1', 'column_2', ...]
     * @return array
     */
    public function select($strTable, array $arrWhere = [], array $arrOrder = [], array $arrLimit = [], array $arrColumns = [])
    {
        $strColumns = empty($arrColumns) ? '*' : $this->generateColumns($arrColumns);
        $strConditions = empty($arrWhere) ? null : $this->generateWhereClause($arrWhere);
        $strOrder = empty($arrOrder) ? null : $this->generateOrdering($arrOrder);
        $strLimit = empty($arrLimit) ? null : $this->generateLimit($arrLimit);
        $arrValues = [];

        $strQuery = 'SELECT ' . $strColumns . ' FROM ' . $strTable;

        if(!is_null($strConditions))
        {
            $strQuery .= $strConditions;
            $arrValues['where'] = $arrWhere;
        }

        if(!is_null($strOrder))
        {
            $strQuery .= $strOrder;
        }

        if(!is_null($strLimit))
        {
            $strQuery .= $strLimit;
            $arrValues['limit'] = $arrLimit;
        }

        $arrValues = $this->generateBindArray(self::QUERY_TYPE_SELECT, $arrValues);

        return $this->query($strQuery, $arrValues);
    }


    /**
     * INSERT a single record in to a table.
     *
     * @param $strTable
     * @param array $arrRecord ['column_1' = > value_1, 'column_2' => value_2, ...]
     * @return array
     */
    public function insert($strTable, array $arrRecord)
    {
        $arrColumns = array_keys($arrRecord);
        $arrValues[0] = array_values($arrRecord);

        return $this->insertBulk($strTable, $arrColumns, $arrValues);
    }


    /**
     * INSERT multiple records in to a table.
     *
     * @param $strTable
     * @param array $arrColumns ['column_1', 'column_2', ...]
     * @param array $arrValues [[value_1, value_2, ...], [value_1, value_2, ...]]
     * @return array
     */
    public function insertBulk($strTable, array $arrColumns, array $arrValues)
    {
        $strColumns = empty($arrColumns) ? '' : $this->generateColumns($arrColumns);
        $strPlaceholders = '';
        $intAffectedRows = 0;
        $arrInsertRow['columns'] = $arrColumns;
        $arrInsertRow['values'] = null;

        foreach($arrColumns as $strColumn)
        {
            $strPlaceholders .= ':' . $strColumn . ',';
        }

        $strPlaceholders = rtrim($strPlaceholders, ',');

        $strQuery = 'INSERT INTO ' . $strTable . '(' . $strColumns . ')' . ' VALUES (' . $strPlaceholders . ')';

        $pdoStatement = $this->handler->prepare($strQuery);

        foreach($arrValues as $arrRow)
        {
            $arrInsertRow['values'] = $arrRow;

            $this->bindValues($pdoStatement, $this->generateBindArray(self::QUERY_TYPE_INSERT, $arrInsertRow));
            $pdoStatement->execute();

            $intAffectedRows++;
        }

        return ['affected_rows' => $intAffectedRows];
    }


    /**
     * UPDATE records in a table.
     *
     * @param $strTable
     * @param array $arrSet ['column_1' => value_1, 'column_2' => value_2, ...]
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @return array
     */
    public function update($strTable, array $arrSet, array $arrWhere)
    {
        $strConditions = empty($arrWhere) ? null : $this->generateWhereClause($arrWhere);
        $strPlaceholders = '';
        $arrValues['set'] = $arrSet;
        $arrValues['where'] = $arrWhere;

        foreach($arrSet as $strKey => $mixValue)
        {
            $strPlaceholders .= $strKey . '= :' . $strKey . ', ';
        }

        $strPlaceholders = rtrim($strPlaceholders, ', ');

        $strQuery = 'UPDATE ' . $strTable . ' SET ' . $strPlaceholders;

        if(!is_null($strConditions))
        {
            $strQuery .= $strConditions;
        }

        $arrValues = $this->generateBindArray(self::QUERY_TYPE_UPDATE, $arrValues);

        return $this->query($strQuery, $arrValues);
    }


    /**
     * DELETE records from a table.
     *
     * @param $strTable
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @return array
     * @throws \Exception
     */
    public function delete($strTable, array $arrWhere)
    {
        if(empty($arrWhere))
        {
            throw new \Exception("Cannot delete with an empty WHERE condition");
        }

        $arrValues['where'] = $arrWhere;

        $strConditions = $this->generateWhereClause($arrWhere);

        $strQuery = 'DELETE FROM ' . $strTable . $strConditions;

        $arrValues = $this->generateBindArray(self::QUERY_TYPE_DELETE, $arrValues);

        return $this->query($strQuery, $arrValues);
    }


    /**
     * Execute a raw query.
     *      (NOTE: use this to execute complex queries, and make sure to use placeholders for all variables)
     *
     * @param $strQuery
     * @param $arrValues [':name_1' => value_1, ':name_2' => value_2, ...]
     * @return array
     */
    public function query($strQuery, $arrValues = [])
    {
        $pdoStatement = $this->handler->prepare($strQuery);

        if(!empty($arrValues))
        {
            $this->bindValues($pdoStatement, $arrValues);
        }

        $pdoStatement->execute();

        // check query type
        if(strtoupper(substr(ltrim($strQuery), 0, 1)) === self::QUERY_TYPE_SELECT)
        {
            // if SELECT return all results as an associative array
            return $pdoStatement->fetchAll();
        }

        // if INSERT, UPDATE or DELETE return affected rows
        return ['affected_rows' => $pdoStatement->rowCount()];
    }


    /**
     * Begin a transaction.
     */
    public function transBegin()
    {
        // check already in a transaction and if not start the transaction
        if(!$this->handler->inTransaction())
        {
            $this->handler->beginTransaction();
        }

        // increment transaction counter
        $this->transactionCounter++;
    }


    /**
     * Commit the transaction.
     */
    public function transCommit()
    {
        // decrement the transaction counter
        $this->transactionCounter--;

        if($this->transactionCounter === 0)
        {
            $this->handler->commit();
        }
    }


    /**
     * Rollback the transaction.
     */
    public function transRollback()
    {
         // @TODO: The rollback works fine even without checking for inTransaction. But if this check was not done
         //        before rolling back a 'no active transaction' error is thrown. Need to find out why.

        if($this->handler->inTransaction())
        {
            $this->handler->rollBack();
        }

        // reset transaction counter
        $this->transactionCounter = 0;
    }


// _____________________________________________________________________________________________________________ private


    /**
     * Generate columns list.
     *
     * @param array $arrColumns ['column_1', 'column_2', ...]
     * @return string
     */
    private function generateColumns(array $arrColumns)
    {
        $strColumns = '';

        foreach($arrColumns as $strColumn)
        {
            $strColumns .= $strColumn . ', ';
        }

        // remove the trailing comma and return
        return rtrim($strColumns, ', ');
    }


    /**
     * Generate the WHERE clause
     *
     * @param $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                   ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @return string
     */
    private function generateWhereClause(array $arrWhere)
    {
        $strWhere = '';
        $strTemp = ''; // holds IN values temporarily

        foreach($arrWhere as $arrCondition)
        {
            // if $arrCondition has a 'true' value at index 3 treat as an 'OR'
            (isset($arrCondition[3]) && $arrCondition[3] === true) ? $strWhere .= ' OR ' : $strWhere .= ' AND ';

            if($arrCondition[1] == 'IN')
            {
                // clean before starting
                $strTemp = '';

                foreach($arrCondition[2] as $arrInValue)
                {
                    $strTemp .= ':' . $arrCondition[0] . $arrInValue . ', ';
                }

                $strTemp = rtrim($strTemp, ', ');

                $strWhere .= $arrCondition[0] . ' ' . $arrCondition[1] . ' (' . $strTemp . ')';
            }
            elseif($arrCondition[1] == 'BETWEEN')
            {
                $strWhere .= $arrCondition[0] . ' ' . $arrCondition[1] . ' :from' . $arrCondition[0] . ' AND :to' . $arrCondition[0]; // set placeholder
            }
            else
            {
                $strWhere .= $arrCondition[0] . ' ' . $arrCondition[1] . ' :' . $arrCondition[0]; // set placeholder
            }
        }

        // trim leading ' AND '
        $strWhere = ltrim($strWhere, ' AND ');

        return ' WHERE ' . $strWhere;
    }


    /**
     * Generate resultset ordering.
     *
     * @param array $arrOrder [['column_1' => 'ASC'], ['column_2', 'DESC']]
     * @return string
     */
    private function generateOrdering(array $arrOrder)
    {
        $strOrder = '';

        foreach($arrOrder as $arrCriteria)
        {
            $strOrder .= $arrCriteria[0] . ' ' . $arrCriteria[1] . ', ';
        }

        return ' ORDER BY ' . rtrim($strOrder, ', ');
    }


    /**
     * Generate query limit boundaries.
     *
     * @param array $arrLimit [offset, limit]
     * @return string
     */
    private function generateLimit(array $arrLimit)
    {
        // set array keys as placeholders
        if(count($arrLimit) === 1)
        {
            return ' LIMIT :limit';
        }

        return ' LIMIT :offset, :limit';
    }


    /**
     * Bind values to the statement with data type.
     *
     * @param $pdoStatement
     * @param $arrValues [':name_1' => value_1, ':name_2' => value_2, ...]
     */
    private function bindValues($pdoStatement, array $arrValues)
    {
        foreach($arrValues as $strKey => $mixValue)
        {
            $pdoStatement->bindValue($strKey, $mixValue, $this->getPDODataType($mixValue));
        }
    }


    /**
     * Get PDO data type representation for the variable.
     *
     * @param $mixValue
     * @return int
     * @throws \Exception
     */
    private function getPDODataType($mixValue)
    {
        $dataType = gettype($mixValue);

        switch($dataType)
        {
            case self::DATA_TYPE_STRING:
                return PDO::PARAM_STR;

            case self::DATA_TYPE_INTEGER:
                return PDO::PARAM_INT;

            case self::DATA_TYPE_DOUBLE:
                return PDO::PARAM_STR;

            case self::DATA_TYPE_BOOLEAN:
                return PDO::PARAM_BOOL;

            case self::DATA_TYPE_NULL:
                return PDO::PARAM_NULL;

            default:
                throw new \Exception("Unsupported data type");
        }
    }


    /**
     * Generate the associative array containing placeholder to value mapping.
     *
     * @param $strQueryType
     * @param $arrValues ['where' => [], 'limit' => [], 'columns' => [], 'values' => [], 'set' => []]
     * @return array
     */
    private function generateBindArray($strQueryType, array $arrValues)
    {
        $arrReturn = [];

        if(is_null($arrValues))
        {
            return $arrReturn;
        }

        // generate array for SELECT
        if($strQueryType === self::QUERY_TYPE_SELECT)
        {
            if(isset($arrValues['where']))
            {
                $arrReturn = $this->generateWhereBindArray($arrValues['where']);
            }

            if(isset($arrValues['limit']))
            {
                $arrReturn = array_merge($arrReturn, $this->generateLimitBindArray($arrValues['limit']));
            }
        }

        // generate array for INSERT
        if($strQueryType === self::QUERY_TYPE_INSERT)
        {
            return $this->generateValuesBindArray($arrValues['columns'], $arrValues['values']);
        }

        // generate array for UPDATE
        if($strQueryType === self::QUERY_TYPE_UPDATE)
        {
            if(isset($arrValues['set']))
            {
                $arrReturn = $this->generateSetBindArray($arrValues['set']);
            }

            if(isset($arrValues['where']))
            {
                $arrReturn = array_merge($arrReturn, $this->generateWhereBindArray($arrValues['where']));
            }
        }

        // generate array for DELETE
        if($strQueryType === self::QUERY_TYPE_DELETE)
        {
            return $this->generateWhereBindArray($arrValues['where']);
        }

        return $arrReturn;
    }


    /**
     * Generate bind array for where clause.
     *
     * @param $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                   ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @return array
     */
    private function generateWhereBindArray(array $arrWhere)
    {
        $arrReturn = [];

        foreach($arrWhere as $arrCondition)
        {
            if($arrCondition[1] == 'IN')
            {
                foreach($arrCondition[2] as $arrInValue)
                {
                    $arrReturn[':' . $arrCondition[0] . $arrInValue] = $arrInValue;
                }
            }
            elseif($arrCondition[1] == 'BETWEEN')
            {
                $arrReturn[':from' . $arrCondition[0]] = $arrCondition[2][0];
                $arrReturn[':to' . $arrCondition[0]] = $arrCondition[2][1];
            }
            else
            {
                $arrReturn[':' . $arrCondition[0]] = $arrCondition[2];
            }
        }

        return $arrReturn;
    }


    /**
     * Generate bind array for limit clause.
     *
     * @param $arrLimit [offset, limit]
     * @return array
     */
    private function generateLimitBindArray($arrLimit)
    {
        if(count($arrLimit) === 1)
        {
            return [':limit' => $arrLimit[0]];
        }

        return [
            ':offset' => $arrLimit[0],
            ':limit' => $arrLimit[1]
        ];
    }


    /**
     * Generate bind array for insert values.
     *
     * @param $arrColumns ['column_1', 'column_2', ...]
     * @param $arrValues [value_1, value_2, ...]
     * @return array
     * @throws \Exception
     */
    private function generateValuesBindArray($arrColumns, $arrValues)
    {
        $intColumnCount = count($arrColumns);

        if($intColumnCount !== count($arrValues))
        {
            throw new \Exception("Column and Value array length mismatch");
        }

        $arrReturn = [];

        for($i = 0; $i < $intColumnCount; $i++)
        {
            $arrReturn[':' . $arrColumns[$i]] = $arrValues[$i];
        }

        return $arrReturn;
    }


    /**
     * Generate bind array for update values.
     *
     * @param $arrSet ['column_1' => value_1, 'column_2' => value_2, ...]
     * @return array
     */
    private function generateSetBindArray($arrSet)
    {
        $arrReturn = [];

        foreach($arrSet as $strKey => $mixValue)
        {
            $arrReturn[':' . $strKey] = $mixValue;
        }

        return $arrReturn;
    }

}
