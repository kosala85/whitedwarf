<?php

namespace Api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Api\Core\Enums\ResponseCodeEnum;

use Api\Logic\Auth\AuthLogic;

class AuthController extends ControllerAbstract
{
    public function __construct($app)
    {
        parent::__construct($app);
    }


    public function login(Request $request, Response $response)
	{
	    $auth = new AuthLogic();

        //  check for validity and generate token
        $strToken = $auth->authenticate('admin@pickme.lk', '21232f297a57a5a743894a0e4a801fc3');

	    $arrData = ['token' => $strToken];

        return $response->withJson($arrData, ResponseCodeEnum::HTTP_OK);
	}


    public function logout(Request $request, Response $response)
	{
	    // unset token

        return $response->withStatus(ResponseCodeEnum::HTTP_OK);
	}

}
