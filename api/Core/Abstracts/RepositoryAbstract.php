<?php

namespace Api\Core\Abstracts;

abstract class RepositoryAbstract
{
	protected $db;

    /**
     * RepositoryAbstract constructor.
     */
	public function __construct()
	{
	    // get a reference to DatabaseAdapter
		$this->db = $GLOBALS['db'];
	}


}