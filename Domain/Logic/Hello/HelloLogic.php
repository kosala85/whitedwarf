<?php

namespace Domain\Logic\Hello;

use Domain\Core\Abstracts\LogicAbstract;
use Data\Repositories\HelloRepository;

class HelloLogic extends LogicAbstract
{
    private $helloRepository;


    public function __construct()
    {
        parent::__construct();
        
        $this->helloRepository = new HelloRepository();
    }


	public function getHelloList($arrFilter)
	{
	    return $this->helloRepository->select($arrFilter);
	}


    public function createHello($arrFilter)
    {
        return $this->wrapInTransaction(function() use($arrFilter)
        {
            return $this->helloRepository->select($arrFilter);
        });
    }
}
