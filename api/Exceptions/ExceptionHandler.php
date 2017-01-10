<?php

namespace Api\Exceptions;

class ExceptionHandler
{
    protected $exception;


    public function __construct($exception)
    {
        $this->exception = $exception;
    }


    public function handle()
    {
        $exceptionDetails = [];

        $exceptionDetails['code'] = $this->exception->getCode();
        $exceptionDetails['message'] = $this->exception->getMessage();
        $exceptionDetails['line'] = $this->exception->getLine();
        $exceptionDetails['file'] = $this->exception->getFile();

        return $exceptionDetails;
    }
}