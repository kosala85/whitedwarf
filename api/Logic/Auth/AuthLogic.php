<?php

namespace Api\Logic\Auth;

use Api\Logic\LogicAbstract;

use Api\Data\Repositories\UserRepository;

class AuthLogic extends LogicAbstract
{
	public function authenticate($email, $password)
	{
		$userRepository = new UserRepository();

        // get user
        $arrColumns = ['id', 'type', 'status'];

        $arrWhere = [
            ['email', '=', $email],
            ['password', '=', $password],
        ];

        $arrOrder = [
             ['id' => 'DESC']
        ];

        $arrLimit = [1];

        $user = $userRepository->selectUser($arrWhere, $arrOrder, $arrLimit, $arrColumns);

        return $user;

	}
}
