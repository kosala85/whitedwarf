<?php

// get autoloader
require(__DIR__ . '/../vendor/autoload.php');

// get settings (NOTE: the array name is $settings)
require(__DIR__ . '/settings.php');

// create Slim\App instance with settings
$app = new \Slim\App($settings);

// get the container of the app
$container = $app->getContainer();

$container['db'] = function($container)
{
  return new \Api\Adapters\DB\MySQLAdapter($container->get('settings')['db']);
};

//$container['errorHandler'] = function ($container)
//{
//    return function ($request, $response, $exception) use ($container)
//    {
//        return $container['response']->withStatus(500)
//                                     ->withHeader('Content-Type', 'text/html')
//                                     ->write('Something went wrong!');
//    };
//};

//// add a class instance to the container
//$container['HelloController'] = function()
//{
//  return new \Api\Controllers\HelloController;
//};


// call on routs (NOTE: a nifty way is used in the routs to call the controller class)
require(__DIR__ . '/routs.php');
