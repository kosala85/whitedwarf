<?php

namespace Api\Core\Adapters\Auth;

use Firebase\JWT\JWT;

use Api\Data\Repositories\UserRepository;

class JWTAdapter
{
    private $strSecretKey;
    

    public function __construct($arrConfig)
    {
        $this->strSecretKey = $arrConfig['secret'];
    }


    public function authenticate($strToken)
    {
        $arrToken = [];

        if(empty($strToken))
        {
            JWTAdapterException::noToken();
        }

        try
        {
           $arrToken = JWT::decode($strToken, $this->strSecretKey, ['HS256']); 
        }
        catch(\Exception $e)
        {
            JWTAdapterException::tokenInvalid();
        }

        // do all checks with token

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

        if(empty($arrUser))
        {
            JWTAdapterException::noUser();
        }

        // create token            
        $arrToken = [
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "data" => [
                "user" => [
                    "id" => 1,
                    "type" => 'A',
                ],
            ],
        ];

        return JWT::encode($arrToken, $this->strSecretKey);
	}
}
