<?php

namespace Api\Logic\Hello;

class HelloException extends \Exception
{
    const BASE_ERROR_CODE = 1000;

    public static function unknown()
    {
        throw new HelloException("Unknown Hello Exception", self::BASE_ERROR_CODE);
    }

    public static function exception()
    {
        throw new HelloException("Hello Exception", self::BASE_ERROR_CODE + 1);
    }

}
