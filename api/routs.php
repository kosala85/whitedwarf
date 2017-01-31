<?php

use Api\Controllers\HelloController;
use Api\Controllers\AuthController;


$app->group('/v1', function()
{
    // login to the api
    $this->post('/login', AuthController::class . ':login');

    // logout from the api
    $this->post('/logout', AuthController::class . ':logout');


    $this->get('/hello/{name}', HelloController::class . ':index');
    $this->post('/hello/{name}', HelloController::class . ':index');
});
