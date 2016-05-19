<?php

$env = getenv('EC_ENV');
if (empty($env)) {
    $env = 'dev';
}

switch ($env) {

    case 'dev':
        return [
            'connections' => [
                'mongodb' => [
                    'driver' => 'mongodb',
                    'host' => 'localhost',
                    'port' => 27017,
                    'username' => '',
                    'password' => '',
                    'database' => 'lrs',
                ],
            ],
        ];

    case 'demo':
        return [
            'connections' => [
                'mongodb' => [
                    'driver' => 'mongodb',
                    'host' => 'localhost',
                    'port' => 27017,
                    'username' => '',
                    'password' => '',
                    'database' => 'lrs_demo',
                ],
            ],
        ];

    default :
        throw new \Exception("No config for env \"{$env}\"");
}
