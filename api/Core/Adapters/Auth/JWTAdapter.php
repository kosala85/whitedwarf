<?php

namespace Api\Core\Adapters\Auth;

use Firebase\JWT\JWT;

use Api\Data\Repositories\UserRepository;
use Api\Data\Models\User;

class JWTAdapter
{
    private $strSecretKey;
    private $strIssuer;
    private $strSubject;
    private $intLifetime;
    

    public function __construct($arrConfig)
    {
        $this->strSecretKey = $arrConfig['secret'];
        $this->strIssuer = $arrConfig['issuer'];
        $this->strSubject = $arrConfig['subject'];
        $this->intLifetime = (int)$arrConfig['lifetime'];

        if($this->intLifetime <= 0)
        {
            JWTAdapterException::invalidLifetime();
        }
    }


    public function authenticate($strToken)
    {
        $token = null;

        if(empty($strToken))
        {
            JWTAdapterException::noToken();
        }

        try
        {
           $token = JWT::decode($strToken, $this->strSecretKey, ['HS256']); 
        }
        catch(\Exception $e)
        {
            JWTAdapterException::tokenInvalid();
        }

        // check whether token expired
        if(time() > $token->exp)
        {
            JWTAdapterException::tokenExpired();
        }


        // add user to the session
        $GLOBALS['session']->user = $token->data->user;

        return true;
    }


	public function createToken($strEmail, $strPassword)
	{
		$userRepository = new UserRepository();

        // get user
        $arrColumns = ['id', 'type', 'status'];

        $arrWhere = [
            ['email', '=', $strEmail],
            ['password', '=', $strPassword],
        ];

        $arrOrder = [
             ['id' => 'DESC']
        ];

        $arrLimit = [1];

        $arrUser = $userRepository->selectUser($arrWhere, $arrOrder, $arrLimit, $arrColumns);

        // check whether there is a user
        if(empty($arrUser))
        {
            JWTAdapterException::noUser();
        }

        // get first row
        $arrUser = $arrUser[0];

        // check whether user is active
        if($arrUser['status'] !== User::STATUS_ACTIVE)
        {
            JWTAdapterException::inactiveUser();
        }

        $intTime = time();

        // create token            
        $arrToken = [
            "iss" => $this->strIssuer,              // issuer
            "sub" => $this->strSubject,             // subject
            "iat" => $intTime,                      // issued at
            "exp" => $intTime + $this->intLifetime, // expire on
            "data" => [
                "user" => [
                    "id" => $arrUser['id'],
                    "type" => $arrUser['type'],
                ],
            ],
        ];

        return JWT::encode($arrToken, $this->strSecretKey);
	}
}
