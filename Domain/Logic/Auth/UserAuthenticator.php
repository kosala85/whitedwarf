<?php

namespace Domain\Logic\Auth;

use Domain\Abstracts\LogicAbstract;

use Data\Models\User;
use Data\Repositories\MySQL\UserRepository;

class UserAuthenticator extends LogicAbstract
{
    private $userRepository;


    public function __construct()
    {
        parent::__construct();
        
        $this->userRepository = new UserRepository();
    }


	public function getValidUser($strEmail, $strPassword)
	{
        $arrCredentials = [
            'email' => $strEmail,
            'password' => $strPassword,
        ];

        $arrUser = $this->userRepository->selectUserByCredentials($arrCredentials);

        // check whether there is a user
        if(empty($arrUser))
        {
            UserAuthenticatorException::noUser();
        }

        // get first row since the return array always is a collection of rows
        $arrUser = $arrUser[0];

        // check whether user is active
        if($arrUser['status'] !== User::STATUS_ACTIVE)
        {
            UserAuthenticatorException::inactiveUser();
        }

        return $arrUser;
	}
}
