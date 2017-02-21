<?php

namespace Api\Data\Repositories;

use Api\Core\Abstracts\RepositoryAbstract;
use Api\Data\Models\Hello;

class HelloRepository extends RepositoryAbstract
{
    public function selectHello($arrFilters)
	{
	    // define filter mappings to table columns ___
        $arrMappings = [
            'trip_status' => null,
            'passenger_phone' => 'phone',
            'type' => null, // null is mapped to fields that are static filters
        ];

        // rebuild the filters array to be compatible with the repository ___
        $arrFilters = $this->rebuildFilter($arrFilters, $arrMappings);


	    $arrWhere = [
	          ['id', '!=', 1],
              ['status', 'IN', [1, 2, 3, 4, 5], false],
              [[['col1', '!=', 2], ['col2', '!=', 3]], true],
              [[['col3', 'IN', [1,2,3]], ['col126548', '!=', 3]]]
           ];

	    // add static where conditions ___
        if($arrFilters['static']['trip_status'] == 1)
        {
            $arrWhere[] = ['status', '=', 10, true];
        }

	    // add dynamic where conditions ___
        $arrWhere = array_merge($arrWhere, $arrFilters['dynamic']);


        $arrJoins = [
//            ['LEFT JOIN', 'table_1 T1', 'T1.column', 'T.column'],
//            ['JOIN', 'table_2 T2', 'T2.column', 'T.column'],
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

        return $this->db->select(Hello::TABLE, $arrJoins, $arrWhere, $arrOrder, $arrLimit, $arrColumns);
	}


	public function countHello($arrFilter)
    {
        $arrWhere = [
//	          ['id', '!=', 1],
//	          ['col_1', 'LIKE', 'sri lanka%'],
//            ['date', 'BETWEEN', ['2017-01-01', '2017-01-02']],
//            ['status', 'IN', [1, 2, 3, 4, 5]],
        ];

        return $this->db->count(Hello::TABLE, $arrWhere);
    }


    public function insertHello()
    {
        $arrRecord = [
            'col_1' => 'value_1',
            'col_2' => 'value_2',
            'col_3' => 'value_3',
        ];

        return $this->db->insert(Hello::TABLE, $arrRecord);
    }


    public function insertMultipleHello()
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