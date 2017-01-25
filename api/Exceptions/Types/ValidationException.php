<?php

namespace Api\Exceptions\Types;

class ValidationException extends \Exception
{
    public $arrValidationErrors = [];
}