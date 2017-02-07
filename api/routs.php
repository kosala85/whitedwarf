<?php

use Api\Controllers\HelloController;
use Api\Controllers\AuthController;
use Api\Controllers\BookingController;


$app->group('/v1', function()
{
    // login to the api
    $this->post('/auth/login', AuthController::class . ':login');

    // authenticated routs
    $this->group('', function()
    {

    	$this->get('/hello/{name}', HelloController::class . ':index');
    	$this->post('/hello/{name}', HelloController::class . ':index');

        $this->get('/booking', BookingController::class . ':index');
    	
    })->add(new \Api\Core\Middleware\AuthMiddleware());
     
});
