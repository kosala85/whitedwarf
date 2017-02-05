<?php

/**
* This is to handle errors thrown at the time of Slim bootup.
*/

// get autoloader
require(__DIR__ . '/../vendor/autoload.php');

$handler = new \Api\Core\Exceptions\ExceptionHandler($exception);

// format of exception to return
$data = $handler->handle();

// response code for the exception
$code = $handler->getResponseCode();

// since this happens outside the Slim/App we have to use the good old php methods to create the response
header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, $code);
header('Content-Type: application/json');
echo json_encode($data);
