<?php

namespace Api\Data\Repositories;

use Api\Data\Models\Hello;

class HelloRepository extends RepositoryAbstract
{
    // @TODO: need to find out about naming conventions used in each layer.

	public function selectAll()
	{
	    $arrWhere = [
//	        ['id', '!=', 1],
//	        ['col_1', 'LIKE', 'sri lanka%'],
//            ['date', 'BETWEEN', ['2017-01-01', '2017-01-02']],
//            ['status', 'IN', [1, 2, 3, 4, 5]],
        ];

        $arrOrder = [
//            ['id', 'ASC'],
//            ['status', 'DESC'],
        ];

        $arrLimit = [
//            0, //offset
//            10, // limit
        ];

        $arrColumns = [
//            'id AS identifier',
//            'col_1 AS column_1',
        ];

        return $this->db->select(Hello::TABLE, $arrWhere, $arrOrder, $arrLimit, $arrColumns);
	}


    public function createHello()
    {
        $arrRecord = [
            'col_1' => 'value_1',
            'col_2' => 'value_2',
            'col_3' => 'value_3',
        ];

        return $this->db->insert(Hello::TABLE, $arrRecord);
    }


    public function createMultipleHello()
    {
        $arrColumns = [
            'col_1',
            'col_2',
            'col_3',
        ];

        $arrValues = [
            ['val_11', 'val_12', null],
            ['val_21', 'val_22', null],
            ['val_21', 'val_22', null],
        ];

        $this->db->transBegin();

        try
        {
            $result = $this->db->insertBulk(Hello::TABLE, $arrColumns, $arrValues);

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }
    }


    public function updateHello()
    {
        $arrSet = [
            'col_1' => 'updated_again2',
            'col_2' => 'updated_again2',
            'col_3' => 'updated_again2',
        ];

        $arrWhere = [
        ['id', 'IN', [1, 2]],
//	        ['col_1', 'LIKE', 'sri lanka%'],
//            ['date', 'BETWEEN', ['2017-01-01', '2017-01-02']],
//            ['status', 'IN', [1, 2, 3, 4, 5]],
        ];

        $this->db->transBegin();

        try
        {
            $result = $this->db->update(Hello::TABLE, $arrSet, $arrWhere);

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }
    }


    public function deleteHello()
    {
        $arrWhere = [
        ['id', '=', 5],
//	        ['col_1', 'LIKE', 'sri lanka%'],
//            ['date', 'BETWEEN', ['2017-01-01', '2017-01-02']],
//            ['status', 'IN', [1, 2, 3, 4, 5]],
        ];

        $this->db->transBegin();

        try
        {
            $result = $this->db->delete(Hello::TABLE, $arrWhere);

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }
    }

}