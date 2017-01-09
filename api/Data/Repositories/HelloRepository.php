<?php

namespace Api\Data\Repositories;

use Api\Data\Models\Hello;

class HelloRepository extends RepositoryAbstract
{
	public function selectAll()
	{
		return $this->adapter->select(Hello::TABLE);
	}


	public function selectById($id)
	{
		return $this->adapter->select(Hello::TABLE, ['id' => $id]);
	}
}