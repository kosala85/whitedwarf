<?php

// get autoloader
require(__DIR__ . '/../vendor/autoload.php');

// get settings (NOTE: the array name is $settings)
require(__DIR__ . '/settings.php');

// create Slim\App instance with settings
$app = new \Slim\App($settings);

// get the container of the app
$container = $app->getContainer();

// add the database adapter to the container
$container['databaseAdapter'] = function($container)
{
  return new \Api\Core\Adapters\DB\MySQL\MySQLAdapter($container->get('settings')['db']);
};

// add the validation adapter to the container
$container['validationAdapter'] = function()
{
  return new \Api\Core\Adapters\Validation\ValidationAdapter();
};

// add the authentication adapter to the container
$container['authAdapter'] = function($container)
{
  return new \Api\Core\Adapters\Auth\JWT\JWTAdapter($container->get('settings')['auth']);
};

// add exception handler to the container
$container['errorHandler'] = function ($container)
{
    return function ($request, $response, $exception) use ($container)
    {
        $handler = new \Api\Core\Exceptions\ExceptionHandler($exception);

        // format of exception to return
        $data = $handler->handle();

        // response code for the exception
        $code = $handler->getResponseCode();

        return $response->withJson($data, $code);
    };
};

// assign objects that are needed across the app to $GLOBALS (NOTE: use with responsibility)
$GLOBALS['db'] = $container['databaseAdapter'];
$GLOBALS['validator'] = $container['validationAdapter'];
$GLOBALS['auth'] = $container['authAdapter'];

// setup a globally accessible session object
$GLOBALS['session'] = (object)[
  'user' => null,
];

// add middleware (NOTE: Last-In-First-Out order)
//  Check for and set application/json header
$app->add(new \Api\Middleware\Json\JsonMiddleware());

// call on routs
require(__DIR__ . '/routs.php');
