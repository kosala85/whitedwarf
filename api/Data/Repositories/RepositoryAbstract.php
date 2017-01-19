<?php

namespace Api\Data\Repositories;

abstract class RepositoryAbstract
{
	protected $adapter;

	public function __construct()
	{
		$this->db = $GLOBALS['databaseAdapter'];
	}


}