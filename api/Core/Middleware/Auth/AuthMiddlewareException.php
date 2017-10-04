<?php

namespace Api\Core\Middleware\Auth;

use Api\Core\Middleware\Exceptions\Types\MiddlewareException;

class AuthMiddlewareException extends MiddlewareException
{
    const BASE_ERROR_CODE = 1000;

    public static function unknown($exception)
    {
        throw new AuthMiddlewareException("Auth Middleware Exception: " . $exception->getMessage(), self::BASE_ERROR_CODE);
    }

    public static function noToken()
    {
        throw new AuthMiddlewareException("Need a token", self::BASE_ERROR_CODE + 3);
    }

    public static function noBearerToken()
    {
        throw new AuthMiddlewareException("Token is not of the type 'Bearer'", self::BASE_ERROR_CODE + 5);
    }
}
