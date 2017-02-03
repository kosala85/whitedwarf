<?php

namespace Api\Logic\Auth;

use Api\Core\Exceptions\Types\AuthException;

class JWTAuthException extends AuthException
{
    const BASE_ERROR_CODE = 1000;

    public static function unknown()
    {
        throw new JWTAuthException("Unknown Auth Exception", self::BASE_ERROR_CODE);
    }

    public static function noUser()
    {
        throw new JWTAuthException("Username Password mismatch", self::BASE_ERROR_CODE + 1);
    }

}
