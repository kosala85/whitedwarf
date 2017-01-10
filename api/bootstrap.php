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

//// add error handler to the container
//$container['errorHandler'] = function($container)
//{
//    return function ($request, $response, $exception) use ($container)
//    {
//        return $container['response']->withStatus(500)
//                                     ->withHeader('Content-Type', 'text/html')
//                                     ->write('Something went wrong!');
//    };
//};

// assign objects that are needed across the app to $GLOBALS (NOTE: use with responsibility)
$GLOBALS['databaseAdapter'] = $container['databaseAdapter'];

// call on routs (NOTE: a nifty way is used in the routs to call the controller class)
require(__DIR__ . '/routs.php');
