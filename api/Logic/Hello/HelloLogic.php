<?php

namespace Api\Logic\Hello;

use Api\Data\Repositories\HelloRepository;

class HelloLogic
{
	public function getAllHello()
	{
		$helloRepository = new HelloRepository();

		return $helloRepository->selectAll();
	}
}
