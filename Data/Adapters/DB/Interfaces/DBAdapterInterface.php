<?php

namespace Data\Adapters\DB\Interfaces;


interface DBAdapterInterface
{
    public function __construct($arrConfig);


    public function __destruct();


    /**
     * SELECT from a table.
     *      (NOTE: Use this for simple to moderate queries)
     *
     * @param $strTable
     * @param $arrJoins [['LEFT JOIN', 'table_1', 'table_1.column', 'other_table.column'],
     *                   ['JOIN', 'table_2', 'table_2.column', 'other_table.column']]
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @param array $arrOrder [['column_1' => 'ASC'], ['column_2', 'DESC']]
     * @param array $arrLimit [offset, limit]
     * @param array $arrColumns ['column_1', 'column_2', ...]
     * @return array
     */
    public function select($strTable, array $arrJoins = [], array $arrWhere = [], array $arrOrder = [], array $arrLimit = [], array $arrColumns = []);


    /**
     * COUNT from a table.
     *
     * @param $strTable
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @param array $arrJoins [['LEFT JOIN', 'table_1', 'table_1.column', 'other_table.column'],
     *                         ['JOIN', 'table_2', 'table_2.column', 'other_table.column']]
     * @return int
     */
    public function count($strTable, array $arrWhere = [], array $arrJoins = []);


    /**
     * INSERT a single record in to a table.
     *
     * @param $strTable
     * @param array $arrRecord ['column_1' = > value_1, 'column_2' => value_2, ...]
     * @return array
     */
    public function insert($strTable, array $arrRecord);


    /**
     * INSERT multiple records in to a table.
     *
     * @param $strTable
     * @param array $arrColumns ['column_1', 'column_2', ...]
     * @param array $arrValues [[value_1, value_2, ...], [value_1, value_2, ...]]
     * @return array
     */
    public function insertBulk($strTable, array $arrColumns, array $arrValues);


    /**
     * UPDATE records in a table.
     *
     * @param $strTable
     * @param array $arrSet ['column_1' => value_1, 'column_2' => value_2, ...]
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @return array
     */
    public function update($strTable, array $arrSet, array $arrWhere);


    /**
     * DELETE records from a table.
     *
     * @param $strTable
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @return array
     * @throws DataException
     */
    public function delete($strTable, array $arrWhere);


    /**
     * Execute a raw query.
     *      (NOTE: use this to execute complex queries, and make sure to use placeholders for all variables)
     *
     * @param $strQuery
     * @param $arrValues [':name_1' => value_1, ':name_2' => value_2, ...]
     * @return array
     */
    public function query($strQuery, $arrValues = []);


    /**
     * Begin a transaction.
     */
    public function transBegin();


    /**
     * Commit the transaction.
     */
    public function transCommit();


    /**
     * Rollback the transaction.
     */
    public function transRollback();
}