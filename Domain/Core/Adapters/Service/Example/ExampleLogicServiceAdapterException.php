<?php

namespace Domain\Core\Adapters\Service\Example;

use Domain\Core\Exceptions\Types\LogicServiceException;

class ExampleLogicServiceAdapterException extends LogicServiceException
{
    const BASE_ERROR_CODE = 1000;

    public static function unknown($exception)
    {
        throw new ExampleLogicServiceAdapterException("Example Service Exception: " . $exception->getMessage(), self::BASE_ERROR_CODE);
    }

}
