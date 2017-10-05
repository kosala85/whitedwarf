<?php

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/settings.php');

// assign objects that are needed across the test suit to $GLOBALS (NOTE: use with responsibility)
$GLOBALS['db'] = new \Api\Core\Adapters\DB\MySQL\MySQLAdapter($settings['db']);
$GLOBALS['validator'] = new \Api\Core\Adapters\Validation\ValidationAdapter();
$GLOBALS['auth'] = new \Api\Core\Adapters\Auth\JWT\JWTAdapter($settings['auth']);

// setup a globally accessible session object
$GLOBALS['session'] = (object)[
    'user' => null,
];

// drop and create all tables in test db

// add test data data to test db
