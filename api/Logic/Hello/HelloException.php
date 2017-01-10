<?php

namespace Api\Logic\Hello;

class HelloException extends \Exception
{
    public static function unknown()
    {
        throw new HelloException("Unknown Exception", 0);
    }

}
