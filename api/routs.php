<?php

use Api\Controllers\HelloController;

use Api\Middleware\TransformerMiddleware;

$app->group('/v1', function()
{
    $this->get('/hello/{name}', HelloController::class . ':index');

})->add(new TransformerMiddleware());
