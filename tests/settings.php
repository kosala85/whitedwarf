<?php

$settings = [
    'db' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'schema' => 'slimtest',
    ],

    'auth' => [
        'secret' => 'secretkey',
        'issuer' => '',
        'subject' => '',
        'lifetime' => 86400, // in seconds (24 hours)
    ]
];
