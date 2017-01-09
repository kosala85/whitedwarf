<?php

namespace Api\Data\Repositories;

use Api\Adapters\DB\MySQLAdapter;

abstract class RepositoryAbstract
{
	protected $adapter;

	public function __construct()
	{
		$this->adapter = new MySQLAdapter();
	}


}