<?php

namespace Api\Logic\Hello;

use Api\Core\Exceptions\Types\LogicalException;

class BookingException extends LogicalException
{
    const BASE_ERROR_CODE = 1000;

    public static function unknown()
    {
        throw new BookingException("Unknown Booking Exception", self::BASE_ERROR_CODE);
    }

    public static function exception()
    {
        throw new BookingException("Booking Exception", self::BASE_ERROR_CODE + 1);
    }

}
