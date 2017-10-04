<?php

namespace Domain\Logic\Auth;

use Domain\Exceptions\Types\LogicalException;

class UserAuthenticatorException extends LogicalException
{
    const BASE_ERROR_CODE = 10000;

    public static function unknown()
    {
        throw new UserAuthenticatorException("Unknown User Authenticator Exception", self::BASE_ERROR_CODE);
    }

    public static function exception()
    {
        throw new UserAuthenticatorException("Hello Exception", self::BASE_ERROR_CODE + 1);
    }

    public static function noUser()
    {
        throw new UserAuthenticatorException("Email Password mismatch", self::BASE_ERROR_CODE + 2);
    }

    public static function inactiveUser()
    {
        throw new UserAuthenticatorException("User is inactive", self::BASE_ERROR_CODE + 3);
    }

}
