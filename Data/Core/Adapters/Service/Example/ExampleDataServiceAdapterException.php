<?php

namespace Data\Core\Adapters\Service\Example;

use Data\Core\Exceptions\Types\DataServiceException;

class ExampleDataServiceAdapterException extends DataServiceException
{
    const BASE_ERROR_CODE = 1000;

    public static function unknown($exception)
    {
        throw new ExampleDataServiceAdapterException("Example Service Exception: " . $exception->getMessage(), self::BASE_ERROR_CODE);
    }

}
