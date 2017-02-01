<?php

namespace Api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Api\Core\Enums\ResponseCodeEnum;

use Api\Logic\Auth\AuthLogic;

use Api\Validations\AuthRules;

class AuthController extends ControllerAbstract
{
    public function __construct($app)
    {
        parent::__construct($app);
    }


    public function login(Request $request, Response $response)
	{
	    $authLogic = new AuthLogic();

        $this->validator->validate($this->arrRequestBody, AuthRules::LOGIN);

        //  check for validity and generate token
        $strToken = $authLogic->authenticate($this->arrRequestBody['email'], $this->arrRequestBody['password']);

	    $arrData = ['token' => $strToken];

        return $response->withJson($arrData, ResponseCodeEnum::HTTP_OK);
	}


    public function logout(Request $request, Response $response)
	{
	    // unset token

        return $response->withStatus(ResponseCodeEnum::HTTP_OK);
	}

}
