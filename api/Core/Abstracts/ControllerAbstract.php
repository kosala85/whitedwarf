<?php

namespace Api\Core\Abstracts;

abstract class ControllerAbstract
{
    protected $arrRequestParams = [];
    protected $arrRequestBody = [];
    protected $validator;
    protected $session;


    /**
     * ControllerAbstract constructor.
     *
     * @param $app
     */
    public function __construct($app)
    {
        $request = $app->get('request');

        // get a reference to the session
        $this->session = $GLOBALS['session'];

        // get a reference to ValidationAdapter
        $this->validator = $GLOBALS['validator'];

        // assign request query parameters to a class variable as an associative array
        $this->arrRequestParams = $request->getQueryParams();

        // build up the filters array if it exists
        if(isset($this->arrRequestParams['filters']))
        {
            $this->arrRequestParams['filters'] = json_decode($this->arrRequestParams['filters']);
        }

        // assign request body in to a class variable as an associative array
        $this->arrRequestBody = $request->getParsedBody();
    }

}