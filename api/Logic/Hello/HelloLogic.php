<?php

namespace Api\Logic\Hello;

use Api\Core\Abstracts\LogicAbstract;
use Api\Data\Repositories\HelloRepository;

class HelloLogic extends LogicAbstract
{
	public function getAllHello()
	{
		$helloRepository = new HelloRepository();

        $this->db->transBegin();

        try
        {
            $result = $helloRepository->selectHello();

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
