<?php

namespace Api\Core\Adapters\Auth;

use Firebase\JWT\JWT;

use Data\Repositories\UserRepository;
use Data\Models\User;

class JWTAdapter
{
    private $strSecretKey;
    private $strIssuer;
    private $strSubject;
    private $intLifetime;


    /**
     * JWTAdapter constructor.
     *
     * @param $arrConfig
     */
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


    /**
     * Authenticate a token and assign user details to the session.
     *
     * @param $strToken
     * @return bool
     */
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


    /**
     * Check the database for a user matching provided credentials and create an authentication token.
     *
     * @param $strEmail
     * @param $strPassword
     * @return string
     */
	public function createToken($strEmail, $strPassword)
	{
		$userRepository = new UserRepository();

        $arrCredentials = [
            'email' => $strEmail,
            'password' => $strPassword,
        ];

        $arrUser = $userRepository->selectUserByCredentials($arrCredentials);

        // check whether there is a user
        if(empty($arrUser))
        {
            JWTAdapterException::noUser();
        }

        // get first row since the return array always is a collection of rows
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
                    'name' => $arrUser['name'],
                    "type" => $arrUser['type'],
                ],
            ],
        ];

        return JWT::encode($arrToken, $this->strSecretKey);
	}
}
