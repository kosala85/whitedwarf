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
  return new \Api\Adapters\DB\MySQLAdapter($container->get('settings')['db']);
};

// add exception handler to the container
$container['errorHandler'] = function ($container)
{
    return function ($request, $response, $exception) use ($container)
    {
        //Format of exception to return
        $data = (new \Api\Exceptions\ExceptionHandler($exception))->handle();

        return $response->withJson($data, 500)
                         ->withHeader('Content-Type', 'application/json');
    };
};

// assign objects that are needed across the app to $GLOBALS (NOTE: use with responsibility)
$GLOBALS['databaseAdapter'] = $container['databaseAdapter'];

// add middleware (NOTE: Last-In-First-Out order)
$app->add(new \Api\Middleware\TransformerMiddleware());

// call on routs (NOTE: a nifty way is used in the routs to call the controller class)
require(__DIR__ . '/routs.php');
