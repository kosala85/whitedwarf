<?php

namespace Api\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HelloController
{
  public function hello(Request $request, Response $response)
  {
      $name = $request->getAttribute('name');
      $response->getBody()->write("Hello, $name");

      return  $response;
  }

}
