<?php

namespace Api\Core\Adapters\Validation\Exceptions\Types;

class ValidationException extends \Exception
{
    public $arrValidationErrors = [];
}