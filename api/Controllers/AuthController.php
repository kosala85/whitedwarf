<?php

namespace Api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Api\Core\Enums\ResponseCodeEnum;

class AuthController extends ControllerAbstract
{
    public function __construct($app)
    {
        parent::__construct($app);
    }


    public function login(Request $request, Response $response)
	{
        //  check for validity and generate token

	    $data = ['token' => ''];

        return $response->withJson($data, ResponseCodeEnum::HTTP_OK);
	}


    public function logout(Request $request, Response $response)
	{
	    // unset token

        return $response->withStatus(ResponseCodeEnum::HTTP_OK);
	}

}
