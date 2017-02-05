<?php

$settings = [
    'settings' => [
        'displayErrorDetails' => false,

        'db' => [
            'host' => 'localhost',
            'user' => 'root',
            'password' => 'admin',
            'schema' => 'slimtest',
        ],

        'auth' => [
            'secret' => 'secretkey',
            'issuer' => '',
            'subject' => '',
            'lifetime' => 86400, // in seconds (24 hours)
        ]
    ]
];
