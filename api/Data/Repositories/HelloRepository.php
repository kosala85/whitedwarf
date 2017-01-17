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
            0, //offset
            10, // limit
        ];

        $arrColumns = [
//            'id',
//            'col_1',
        ];

        return $this->adapter->select(Hello::TABLE, $arrWhere, $arrOrder, $arrLimit, $arrColumns);
	}


    public function createHello()
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

        return $this->adapter->insert(Hello::TABLE, $arrColumns, $arrValues);
    }


    public function updateHello()
    {
        $arrSet = [
            'col_1' => 'update_11',
            'col_2' => 'update_11',
            'col_3' => 'update_11',
        ];

        $arrWhere = [
        ['id', 'IN', [2, 4, 6]],
//	        ['col_1', 'LIKE', 'sri lanka%'],
//            ['date', 'BETWEEN', ['2017-01-01', '2017-01-02']],
//            ['status', 'IN', [1, 2, 3, 4, 5]],
        ];

        return $this->adapter->update(Hello::TABLE, $arrSet, $arrWhere);
    }


    public function deleteHello()
    {
        $arrWhere = [
        ['id', '=', 3],
//	        ['col_1', 'LIKE', 'sri lanka%'],
//            ['date', 'BETWEEN', ['2017-01-01', '2017-01-02']],
//            ['status', 'IN', [1, 2, 3, 4, 5]],
        ];

        return $this->adapter->delete(Hello::TABLE, $arrWhere);
    }

}