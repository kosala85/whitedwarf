<?php

namespace Api\Core\Exceptions\Types;

class ValidationException extends \Exception
{
    public $arrValidationErrors = [];
}