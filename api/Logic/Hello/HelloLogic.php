<?php

namespace Api\Logic\Hello;

use Api\Data\Repositories\HelloRepository;
use Api\Logic\Hello\HelloException;

class HelloLogic
{
	public function getAllHello()
	{
		$helloRepository = new HelloRepository();

		return $helloRepository->selectAll();
	}
}
