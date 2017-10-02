<?php

namespace Data\Repositories;

use Data\Repositories\Abstracts\RepositoryAbstract;
use Data\Repositories\Interfaces\RepositoryInterface;
use Data\Exceptions\Types\NotImplementedException;
use Data\Models\User;

class UserRepository extends RepositoryAbstract implements RepositoryInterface
{

    /**
     * Select that can be manipulated using a filters array.
     *
     * @param array $arrFilters [[ <string>'field', <integer>1 (operator), <mixed>'value' ]
     *                          [ <string>'field', <integer>1 (operator), <mixed>'value' ]]
     * @return array
     * @throws \Exception
     */
    public function select($arrFilters = [])
    {
        throw new NotImplementedException();
    }


    /**
     * Select a single item using its primary key value.
     *
     * @param $intId
     * @return array
     * @throws \Exception
     */
    public function selectItem($intId)
    {
        throw new NotImplementedException();
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
     * @throws \Exception
     */
    public function selectBy($arrWhere = [], $arrOrder = [], $arrLimit = [], $arrColumns = [])
    {
        throw new NotImplementedException();
    }


    /**
     * Get a count from the base table by different criteria.
     *
     * @param array $arrWhere [['column_1', '=', 'value'],['column_2', '=', 'value', 'OR'],['column_2', 'LIKE', '%value%'],
     *                         ['column_2', 'IN', [1, 2, 3]],['column_2', 'BETWEEN', [value_1, value_2]]]
     * @return int
     * @throws \Exception
     */
    public function count($arrWhere = [])
    {
        throw new NotImplementedException();
    }


    /**
     * Insert a record in to the base table.
     *  (NOTE: The db adapter also supports bulk insets through
     *         insertBulk($strTable, $arrColumns, $arrValues) method)
     *
     * @param $arrRecord ['column_1' = > value_1, 'column_2' => value_2, ...]
     * @return array
     * @throws \Exception
     */
    public function insert($arrRecord)
    {
        throw new NotImplementedException();
    }


    /**
     * Update a record in the base table.
     *
     * @param $intId
     * @param $arrSet ['column_1' => value_1, 'column_2' => value_2, ...]
     * @return array
     * @throws \Exception
     */
    public function update($intId, $arrSet)
    {
        throw new NotImplementedException();
    }


// ___________________________________________________________________________________________ additional repo functions

    /**
     * Select a user by login credentials.
     *
     * @param $arrCredentials
     * @return mixed
     * @throws \Exception
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