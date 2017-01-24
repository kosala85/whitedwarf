<?php

namespace Api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Api\Logic\Hello\HelloLogic;

class HelloController extends ControllerAbstract
{
    private $hello;


    public function __construct()
    {
        parent::__construct();

        $this->hello = new HelloLogic();
    }


    public function index(Request $request, Response $response)
	{
        $data = $this->hello->getAllHello();

        return $response->withJson($data, 201);
	}

}
