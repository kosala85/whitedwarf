<?php

namespace Api\Core\Adapters\DB;

use Api\Core\Exceptions\Types\DataException;

class MySQLAdapterException extends DataException
{
    const BASE_ERROR_CODE = 1000;

    public static function unknown()
    {
        throw new MySQLAdapterException("Unknown Hello Exception", self::BASE_ERROR_CODE);
    }

    public static function noInsertValues()
    {
        throw new MySQLAdapterException("The insert record cannot be empty", self::BASE_ERROR_CODE + 1);
    }

    public static function noUpdateValues()
    {
        throw new MySQLAdapterException("Update values cannot be empty", self::BASE_ERROR_CODE + 1);
    }

    public static function noWhereConditions()
    {
        throw new MySQLAdapterException("Where conditions cannot be empty", self::BASE_ERROR_CODE + 1);
    }

}
