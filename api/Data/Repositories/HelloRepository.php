<?php

namespace Api\Data\Repositories;

use Api\Data\Models\Hello;

class HelloRepository extends RepositoryAbstract
{
	public function selectAll()
	{
	    $arrWhere = [
	        ['id', '=', 1],
            ['date', 'BETWEEN', ['2017-01-01', '2017-01-02']],
            ['status', 'IN', [1, 2, 3, 4, 5]],
        ];

        $arrOrder = [
            ['id', 'ASC'],
            ['status', 'DESC'],
        ];

        $arrLimit = [
            0, //offset
            10, // limit
        ];

        $arrColumns = [
            'id',
            'status',
        ];

        return $this->adapter->select(Hello::TABLE, $arrWhere, $arrOrder, $arrLimit, $arrColumns);
	}

}