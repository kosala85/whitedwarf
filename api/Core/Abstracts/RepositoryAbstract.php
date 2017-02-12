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
	];

    /**
     * RepositoryAbstract constructor.
     */
	public function __construct()
	{
	    // get a reference to DatabaseAdapter
		$this->db = $GLOBALS['db'];
	}


// _________________________________________________________________________ protected


	protected function rebuildFilter(array $arrFilters, array $arrMappings)
	{
		$arrReturn = [
			'static' => [],
			'dynamic' => [],
		];

		$arrMappingField = [];
        $arrMappingValue = [];
		
		foreach($arrMappings as $strMappingField => $strMappingColumn)
		{
			foreach($arrFilters as $arrFilter)
			{
				// check whether a fliter exists for the mapping
				if($arrFilter[0] == $strMappingField)
				{
					// add to static filters when mapping column is null
					if(is_null($strMappingColumn))
					{
						$arrReturn['static'][$strMappingField] = $arrFilter[1];
					}
					else
					{
						$arrReturn['dynamic'][] = $this->generateWhereCondition($strMappingColumn, $arrFilter);
					}
				}
				else
				{
					// add to static filters when mapping column is null
					if(is_null($strMappingColumn))
					{
						$arrReturn['static'][$strMappingField] = null;
					}	
				}
			}
		}

		return $arrReturn;
	}


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