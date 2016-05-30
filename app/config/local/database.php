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

    case 'preprod':
        return [
            'connections' => [
                'mongodb' => [
                    'driver' => 'mongodb',
                    'host' => ['192.168.8.1', '192.168.8.2', '192.168.8.3', '192.168.8.4', '192.168.8.5',],
                    'port' => 27017,
                    'username' => '',
                    'password' => '',
                    'database' => 'lrs_preprod',
                    'options' => [
                        'replicaSet' => 'rs0',
                    ],
                ],
            ],
        ];

    default :
        throw new \Exception("No config for env \"{$env}\"");
}
