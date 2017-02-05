<?php

namespace Api\Core\Abstracts;

abstract class ControllerAbstract
{
    protected $arrRequestParams = [];
    protected $arrRequestBody = [];
    protected $validator;
    protected $session;


    public function __construct($app)
    {
        $request = $app->get('request');

        // get a reference to the session
        $this->session = $GLOBALS['session'];

        // get a reference to ValidationAdapter
        $this->validator = $GLOBALS['validator'];

        // assign request query parameters to a class variable as an associative array
        $this->arrRequestParams = $request->getQueryParams();

        // assign request body in to a class variable as an associative array
        if(!empty($request->getParsedBody()))
        {
            $this->arrRequestBody = $request->getParsedBody();
        }
    }

}