<?php

namespace Api\Core\Adapters\Auth;

use Api\Core\Exceptions\Types\AuthException;

class JWTAdapterException extends AuthException
{
    const BASE_ERROR_CODE = 1000;

    public static function unknown($exception)
    {
        throw new JWTAdapterException("JWT Exception: " . $exception->getMessage(), self::BASE_ERROR_CODE);
    }

    public static function noUser()
    {
        throw new JWTAdapterException("Username Password mismatch", self::BASE_ERROR_CODE + 1);
    }

    public static function noToken()
    {
        throw new JWTAdapterException("Need a token", self::BASE_ERROR_CODE + 2);
    }

	public static function tokenInvalid()
    {
        throw new JWTAdapterException("The token is invalid", self::BASE_ERROR_CODE + 3);
    }

    public static function tokenExpired()
    {
        throw new JWTAdapterException("The token has expired", self::BASE_ERROR_CODE + 4);
    }
}
