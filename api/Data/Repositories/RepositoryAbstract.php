<?php

namespace Api\Data\Repositories;

abstract class RepositoryAbstract
{
	protected $db;

	public function __construct()
	{
		$this->db = $GLOBALS['db'];
	}


}