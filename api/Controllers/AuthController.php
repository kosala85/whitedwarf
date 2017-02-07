<?php

namespace Api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Api\Core\Abstracts\ControllerAbstract;
use Api\Core\Enums\ResponseCodeEnum;
use Api\Validations\AuthRules;

class AuthController extends ControllerAbstract
{
    /**
     * AuthController constructor.
     *
     * @param $app
     */
    public function __construct($app)
    {
        parent::__construct($app);
    }


    /**
     * Login.
     * 
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function login(Request $request, Response $response)
	{
	    $authenticator = $GLOBALS['auth'];

        // validate input data
        $this->validator->validate($this->arrRequestBody, AuthRules::LOGIN);

        // check for authenticity and generate token
        $strToken = $authenticator->createToken($this->arrRequestBody['email'], $this->arrRequestBody['password']);

	    $arrData = ['token' => $strToken];

        return $response->withJson($arrData, ResponseCodeEnum::HTTP_OK);
	}
}
