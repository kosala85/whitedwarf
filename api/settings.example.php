<?php

/**
 * Settings example file.
 *      (NOTE: Create a copy of this file and name it settings.php to store application settings)
 */

$settings = [
    'settings' => [
        'displayErrorDetails' => false,

        'db' => [
            'host' => 'db-host',
            'user' => 'db-user',
            'password' => 'db-password',
            'schema' => 'db-schema',
        ],

        'auth' => [
            'secret' => 'token-secret-key',
            'issuer' => 'token-issuer',
            'subject' => 'token-subject',
            'lifetime' => 86400, // in seconds (24 hours)
        ]
    ]
];
