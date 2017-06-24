<?php

namespace Api\Logic\Hello;

use Api\Core\Abstracts\LogicAbstract;
use Api\Data\Repositories\HelloRepository;

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
	    return $this->helloRepository->selectHello($arrFilter);
	}


    public function createHello($arrFilter)
    {
        $this->db->transBegin();

        try
        {
            $result = $this->helloRepository->selectHello($arrFilter);

            $this->db->transCommit();

            return $result;
        }
        catch(\Exception $e)
        {
            $this->db->transRollback();

            throw $e;
        }
    }
}
