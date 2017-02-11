<?php

namespace Api\Core\Adapters\Service;

use Api\Core\Exceptions\Types\ServiceException;

class ExampleServiceAdapterException extends ServiceException
{
    const BASE_ERROR_CODE = 1000;

    public static function unknown($exception)
    {
        throw new ExampleServiceAdapterException("Example Service Exception: " . $exception->getMessage(), self::BASE_ERROR_CODE);
    }

}
