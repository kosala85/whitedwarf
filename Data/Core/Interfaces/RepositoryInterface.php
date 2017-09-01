<?php

namespace Data\Core\Interfaces;

interface RepositoryInterface
{
    /**
     * Select that can be manipulated using a filters array.
     *
     * @param array $arrFilters [[ <string>'field', <integer>1 (operator), <mixed>'value' ]
     *                          [ <string>'field', <integer>1 (operator), <mixed>'value' ]]
     * @return array
     * @throws \Exception
     */
    public function select($arrFilters = []);


    /**
     * Select a single item using its primary key value.
     *
     * @param $intId
     * @return array
     * @throws \Exception
     */
    public function selectItem($intId);


    /**
     * Select data from the base table by different criteria.
     *
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @param array $arrOrder [['column_1' => 'ASC'], ['column_2', 'DESC']]
     * @param array $arrLimit [offset, limit]
     * @param array $arrColumns ['column_1', 'column_2', ...]
     * @return array
     * @throws \Exception
     */
    public function selectBy($arrWhere = [], $arrOrder = [], $arrLimit = [], $arrColumns = []);


    /**
     * Get a count from the base table by different criteria.
     *
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @return integer
     * @throws \Exception
     */
    public function count($arrWhere = []);


    /**
     * Insert a record in to the base table.
     *  (NOTE: The db adapter also supports bulk insets through
     *         insertBulk($strTable, $arrColumns, $arrValues) method)
     *
     * @param $arrRecord ['column_1' = > value_1, 'column_2' => value_2, ...]
     * @return array
     * @throws \Exception
     */
    public function insert($arrRecord);


    /**
     * Update a record in the base table.
     *
     * @param $intId
     * @param $arrSet ['column_1' => value_1, 'column_2' => value_2, ...]
     * @return array
     * @throws \Exception
     */
    public function update($intId, $arrSet);
}