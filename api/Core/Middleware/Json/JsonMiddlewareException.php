<?php

namespace Api\Core\Middleware\Json;

use Api\Core\Exceptions\Types\MiddlewareException;

class JsonMiddlewareException extends MiddlewareException
{
    const BASE_ERROR_CODE = 1000;

    public static function unknown($exception)
    {
        throw new AuthMiddlewareException("Json Middleware Exception: " . $exception->getMessage(), self::BASE_ERROR_CODE);
    }

    public static function notJson()
    {
        throw new AuthMiddlewareException("Api only accepts JSON data", self::BASE_ERROR_CODE + 1);
    }
}
