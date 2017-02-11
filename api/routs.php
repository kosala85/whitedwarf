<?php

use Api\Controllers\HelloController;
use Api\Controllers\AuthController;


$app->group('/v1', function()
{
    // login to the api
    $this->post('/auth/login', AuthController::class . ':login');

    // authenticated routs
    $this->group('', function()
    {

    	$this->get('/hello/{name}', HelloController::class . ':index');
    	$this->post('/hello/{name}', HelloController::class . ':index');
    	
    })->add(new \Api\Core\Middleware\Auth\AuthMiddleware());
     
});
