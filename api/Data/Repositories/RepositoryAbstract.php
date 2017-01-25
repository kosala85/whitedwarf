<?php

namespace Api\Data\Repositories;

abstract class RepositoryAbstract
{
	protected $db;

	public function __construct()
	{
	    // get a reference to DatabaseAdapter
		$this->db = $GLOBALS['db'];
	}


}