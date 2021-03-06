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
        // get permissions
        $this->get('/auth/permission', AuthController::class . ':getPermission');

        // hello routs
    	$this->get('/hello', HelloController::class . ':index');
    	$this->get('/hello/{id}', HelloController::class . ':getById');
    	$this->post('/hello', HelloController::class . ':create');
    	$this->put('/hello/{id}', HelloController::class . ':update');
    	$this->delete('/hello/{id}', HelloController::class . ':delete');

    })->add(new \Api\Core\Middleware\Auth\AuthMiddleware());
     
});
