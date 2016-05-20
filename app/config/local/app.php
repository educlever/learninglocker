<?php

$env = getenv('EC_ENV');
if (empty($env)) {
    $env = 'dev';
}

switch ($env) {

    case 'dev':
        return array(
            'debug' => false,
            'url' => 'http://lrs.edu/',
            'key' => 'a4k23G2pNpi4ngc8xbyZox6UujZ0GyEz',
        );

    case 'demo':
        return array(
            'debug' => false,
            'url' => 'http://demo.lrs.educlever.io/',
            'key' => 'z5k23G2ppin4NGC8xbyzox6UujZ0GbC5',
        );

    default :
        throw new \Exception("No config for env \"{$env}\"");
}