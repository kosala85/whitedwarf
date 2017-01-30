<?php

namespace Api\Adapters\Validation;

use JsonSchema\Validator;
use Api\Exceptions\Types\ValidationException;

class ValidationAdapter
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    public function validate($arrValues, $strRules)
    {
        $objRules = json_decode($strRules);

        $this->validator->check((object)$arrValues, $objRules);

        if ($this->validator->isValid())
        {
            return true;
        }
        else
        {
            $exception = new ValidationException('Validation Exception');

            $exception->arrValidationErrors = $this->validator->getErrors();

            throw $exception;
        }
    }
}