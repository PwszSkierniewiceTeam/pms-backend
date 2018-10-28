<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // JWT
        'jwt' => [
            'secret' => 'super_secret_should_not_commit',
            'expires' => '+20 minutes',
            'algorithm' => ['HS256']
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // DB Settings
        'db' => [
            'host' => '127.0.0.1',
            'port' => 3306,
            'dbname' => 'pms',
            'user' => 'pms_user',
            'password' => 'pms_user_123'
        ]
    ]
];
