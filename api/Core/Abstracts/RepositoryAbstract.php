<?php

namespace Api\Core\Abstracts;

abstract class RepositoryAbstract
{
	protected $db;

	protected $arrOperators = [
		1 => '=',
		2 => '!=',
		3 => 'LIKE',
		4 => 'IN',
		5 => 'BETWEEN',
		6 => '<',
		7 => '>',
		8 => '<=',
		9 => '>=',
	];

    /**
     * RepositoryAbstract constructor.
     */
	public function __construct()
	{
	    // get a reference to DatabaseAdapter
		$this->db = $GLOBALS['db'];
	}


// ___________________________________________________________________________________________________________ protected


    /**
     * Rebuild the structure filter sent from the client that it can be used in the api.
     *
     * @param array $arrFilters
     * @param array $arrMappings
     * @return array
     */
	protected function rebuildFilter(array $arrFilters, array $arrMappings)
	{
		$arrReturn = [
			'static' => [],
			'dynamic' => [],
		];

		foreach($arrMappings as $strMappingField => $strMappingColumn)
		{
			foreach($arrFilters as $arrFilter)
			{
				// check whether a filter exists for the mapping
				if($arrFilter[0] == $strMappingField)
				{
					// add to static filters when mapping column is null otherwise add to dynamic filters
					if(is_null($strMappingColumn))
					{
						$arrReturn['static'][$strMappingField] = $arrFilter[1];
					}
					else
					{
						$arrReturn['dynamic'][] = $this->generateWhereCondition($strMappingColumn, $arrFilter);
					}
				}
			}
		}

		return $arrReturn;
	}


    /**
     * Generate a single where condition array.
     *
     * @param $strColumn
     * @param $arrFilter
     * @return array
     */
	protected function generateWhereCondition($strColumn, $arrFilter)
	{
		// set operator
		$strOperator = $this->arrOperators[1];

		if(isset($arrFilter[2]))
		{
			$strOperator = $this->arrOperators[$arrFilter[2]];
		}

		// set whether condition is of type 'OR'
		$blnOr = false;

		if(isset($arrFilter[3]) && $arrFilter[3])
		{
			$blnOr = true;
		}

		// set value
		$arrValue = $arrFilter[1];

		return [
			$strColumn,
			$strOperator,
			$arrValue,
			$blnOr
		];
	}

}