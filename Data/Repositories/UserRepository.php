<?php

namespace Data\Repositories;

use Data\Core\Abstracts\RepositoryAbstract;
use Data\Core\Interfaces\RepositoryInterface;
use Data\Models\User;

class UserRepository extends RepositoryAbstract implements RepositoryInterface
{

    /**
     * Select that can be manipulated using a filters array.
     *
     * @param array $arrFilters [[ <string>'field', <integer>1 (operator), <mixed>'value' ]
     *                          [ <string>'field', <integer>1 (operator), <mixed>'value' ]]
     * @return array
     */
    public function select($arrFilters = [])
    {
        // TODO: Implement select() method.
    }


    /**
     * Select a single item using its primary key value.
     *
     * @param $intId
     * @return array
     */
    public function selectItem($intId)
    {
        // TODO: Implement selectItem() method.
    }


    /**
     * Select data from the base table by different criteria.
     *
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @param array $arrOrder [['column_1' => 'ASC'], ['column_2', 'DESC']]
     * @param array $arrLimit [offset, limit]
     * @param array $arrColumns ['column_1', 'column_2', ...]
     * @return array
     */
    public function selectBy($arrWhere = [], $arrOrder = [], $arrLimit = [], $arrColumns = [])
    {
        // TODO: Implement selectBy() method.
    }


    /**
     * Get a count from the base table by different criteria.
     *
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @return integer
     */
    public function count($arrWhere = [])
    {
        // TODO: Implement count() method.
    }


    /**
     * Insert a record in to the base table.
     *  (NOTE: The db adapter also supports bulk insets through
     *         insertBulk($strTable, $arrColumns, $arrValues) method)
     *
     * @param $arrRecord ['column_1' = > value_1, 'column_2' => value_2, ...]
     * @return array
     */
    public function insert($arrRecord)
    {
        // TODO: Implement insert() method.
    }


    /**
     * Update a record in the base table.
     *
     * @param $intId
     * @param $arrSet ['column_1' => value_1, 'column_2' => value_2, ...]
     * @return array
     */
    public function update($intId, $arrSet)
    {
        // TODO: Implement update() method.
    }


// ___________________________________________________________________________________________ additional repo functions

    /**
     * Select a user by login credentials.
     *
     * @param $arrCredentials
     * @return mixed
     */
    public function selectUserByCredentials($arrCredentials)
    {
        $strQuery = "SELECT id, name, type, status 
                     FROM " . User::TABLE . " 
                     WHERE email = :email 
                        AND password = PASSWORD(:password) 
                     LIMIT 1";

        $arrValues = [
            ':email' => $arrCredentials['email'],
            ':password' => $arrCredentials['password'],
        ];

        return $this->db->query($strQuery, $arrValues);
    }

}