<?php

namespace Api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Api\Logic\Hello\HelloLogic;

class HelloController
{
	public function index(Request $request, Response $response)
	{
		$hello = new HelloLogic();

        $response->getBody()->write(json_encode($hello->getAllHello()));
	}

}
