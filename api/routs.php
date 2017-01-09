<?php

use Api\Controllers\HelloController;

$app->get('/hello/{name}', HelloController::class . ':hello');