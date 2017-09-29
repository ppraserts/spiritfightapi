<?php
return [
    'settings' => [
        'JWT_TokenLifeTime' => '+10 minutes',
        'JWT_Secret' => 'd3d3LnNreWhhd2thZHZlbnR1cmUuY29t',
        'REST_Url' => 'http://tkdapi.spiritfight.com',
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        //'renderer' => [
        //    'template_path' => '../templates/',
        //],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => '../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        //Database settings
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'tkdsystem_new',
            'username' => 'tkd',
            'password' => 'Passw0rd!',
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix'    => '',
        ],
    ],
];
