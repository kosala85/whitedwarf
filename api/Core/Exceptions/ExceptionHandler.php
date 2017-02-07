<?php

namespace Api\Core\Exceptions;

use Api\Core\Enums\ResponseCodeEnum;
use Api\Core\Exceptions\Types\DataException;
use Api\Core\Exceptions\Types\LogicalException;
use Api\Core\Exceptions\Types\MiddlewareException;
use Api\Core\Exceptions\Types\ValidationException;

class ExceptionHandler
{
    protected $exception;
    protected $intResponseCode;


    /**
     * ExceptionHandler constructor.
     *
     * @param $exception
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
        $this->setResponseCode();
    }


// ______________________________________________________________________________________________________________ public


    /**
     * Handle error according to error type.
     *
     * @return array
     */
    public function handle()
    {
        if($this->exception instanceof ValidationException)
        {
            return $this->handleValidationException();
        }

        return $this->handleException();
    }


    public function getResponseCode()
    {
        return $this->intResponseCode;
    }


// _____________________________________________________________________________________________________________ private


    /**
     * Set HTTP response code depending on the error type.
     */
    private function setResponseCode()
    {
        if($this->exception instanceof ValidationException)
        {
            $this->intResponseCode = ResponseCodeEnum::HTTP_UNPROCESSABLE;
            return;
        }

        $this->intResponseCode = ResponseCodeEnum::HTTP_SERVER_ERROR;
    }


    /**
     * Handle any exception.
     *
     * @return array
     */
    private function handleException()
    {
        $exceptionDetails = [];

        $exceptionDetails['code'] = $this->exception->getCode();
        $exceptionDetails['message'] = $this->exception->getMessage();
        $exceptionDetails['line'] = $this->exception->getLine();
        $exceptionDetails['file'] = $this->exception->getFile();

        return $exceptionDetails;
    }


    /**
     * Handle validation exceptions.
     *
     * @return array
     */
    private function handleValidationException()
    {
        $arrErrors = [];

        foreach($this->exception->arrValidationErrors as $arrError)
        {
            $arrErrors[] = [
                'field' => $arrError['property'],
                'message' => $arrError['message']
            ];
        }

        return $arrErrors;
    }
}