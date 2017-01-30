<?php

namespace Api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Api\Core\Enums\ResponseCodeEnum;

use Api\Logic\Hello\HelloLogic;

use Api\Validations\HelloRules;

class HelloController extends ControllerAbstract
{
    private $hello;


    public function __construct($app)
    {
        parent::__construct($app);

        $this->hello = new HelloLogic();
    }


    public function index(Request $request, Response $response)
	{
        $name = $request->getAttribute('name');

        $this->validator->validate($this->arrRequestBody, HelloRules::SELECT);

        $data = $this->hello->getAllHello();

        return $response->withJson($data, ResponseCodeEnum::HTTP_OK);
	}

}
