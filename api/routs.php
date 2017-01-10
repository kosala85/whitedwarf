<?php

use Api\Controllers\HelloController;

$app->group('/v1', function()
{
    $this->get('/hello/{name}', HelloController::class . ':index');

});
