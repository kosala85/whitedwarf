<?php

namespace Api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Api\Core\Abstracts\ControllerAbstract;
use Api\Core\Enums\ResponseCodeEnum;
use Api\Validations\HelloRules;

use Domain\Logic\Hello\HelloLogic;

class HelloController extends ControllerAbstract
{
    private $hello;


    public function __construct($app)
    {
        parent::__construct($app);

        $this->hello = new HelloLogic();
    }


    public function index(Request $request, Response $response)
	{
        // $this->validator->validate($arrFilter, HelloRules::SELECT_FILTER);

        $data = $this->hello->getHelloList($this->arrRequestParams['filters']);

        return $response->withJson($this->structureResponseData($data), ResponseCodeEnum::HTTP_OK);
	}


    public function getById(Request $request, Response $response)
    {
        $intId = $request->getAttribute('id');

        $data = $this->hello->getHello($intId);

        return $response->withJson($this->structureResponseData($data), ResponseCodeEnum::HTTP_OK);
    }


    public function create(Request $request, Response $response)
    {
//        $name = $request->getAttribute('name');

//        $this->validator->validate($this->arrRequestBody, HelloRules::SELECT);

        $data = $this->hello->createHello([]);

        return $response->withJson($data, ResponseCodeEnum::HTTP_CREATED);
    }


    public function update(Request $request, Response $response)
    {
        $name = $request->getAttribute('name');

        $this->validator->validate($this->arrRequestBody, HelloRules::SELECT);

        $data = $this->hello->updateHello($intId, $arrHello);

        return $response->withJson([], ResponseCodeEnum::HTTP_NO_CONTENT);
    }


    public function delete(Request $request, Response $response)
    {
        $name = $request->getAttribute('name');

        $this->validator->validate($this->arrRequestBody, HelloRules::DELETE);

        $data = $this->hello->deleteHello($intId, $strMessage);

        return $response->withJson([], ResponseCodeEnum::HTTP_NO_CONTENT);
    }

}
