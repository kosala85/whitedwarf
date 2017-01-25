<?php

namespace Api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

abstract class ControllerAbstract
{
    protected $arrRequestBody = [];
    protected $arrRequestParam = [];
    protected $validator;


    public function __construct(Request $request, Response $response)
    {
        // get a reference to ValidatorAdapter
        $this->validator = $GLOBALS['validator'];

        // assign request body in to a class variable as an associative array
        $this->arrRequestBody = $request->getParsedBody();
    }

}