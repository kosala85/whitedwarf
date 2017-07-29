<?php

namespace Data\Repositories;

use Data\Core\Abstracts\RepositoryAbstract;
use Data\Models\User;

class UserRepository extends RepositoryAbstract
{

// _______________________________________________________________________________________________ common repo functions


    /**
     * Select User(s) based on different search criteria.
     *
     * @param $arrWhere
     * @param $arrOrder
     * @param $arrLimit
     * @param $arrColumns
     * @return mixed
     */
	public function selectUser($arrWhere = [], $arrOrder = [], $arrLimit = [], $arrColumns = [])
	{
        return $this->db->select(User::TABLE, $arrWhere, $arrOrder, $arrLimit, $arrColumns);
	}

    /**
     * Get count of Users based on different criteria.
     *
     * @param $arrWhere
     * @return mixed
     */
	public function countUser($arrWhere = [])
    {
        return $this->db->count(User::TABLE, $arrWhere);
    }


    /**
     * Create a new User.
     *
     * @param $arrRecord
     * @return mixed
     * @throws \Exception
     */
    public function insertUser($arrRecord)
    {
        $this->db->transBegin();

        try
        {
            $result = $this->db->insert(User::TABLE, $arrRecord);

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }

    }


    /**
     * Update User(s) based on different criteria.
     *
     * @param $arrSet
     * @param $arrWhere
     * @return mixed
     * @throws \Exception
     */
    public function updateUser($arrSet, $arrWhere)
    {
        $this->db->transBegin();

        try
        {
            $result = $this->db->update(User::TABLE, $arrSet, $arrWhere);

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }
    }


    /**
     * Delete User(s) based on different criteria.
     *
     * @param $arrWhere
     * @return mixed
     * @throws \Exception
     */
    public function deleteUser($arrWhere)
    {
        $this->db->transBegin();

        try
        {
            $result = $this->db->delete(User::TABLE, $arrWhere);

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }
    }


// _____________________________________________________________________________________________ specific repo functions


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