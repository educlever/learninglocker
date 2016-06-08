<?php

$env = getenv('EC_ENV');
if (empty($env)) {
    $env = 'dev';
}

switch ($env) {

    case 'dev':
        return array(
            'debug' => true,
            'url' => 'http://lrs.edu/',
            'key' => 'a4k23G2pNpi4ngc8xbyZox6UujZ0GyEz',
        );

    case 'demo':
        return array(
            'debug' => true,
            'url' => 'http://demo.lrs.educlever.io/',
            'key' => 'z5k23G2ppin4NGC8xbyzox6UujZ0GbC5',
        );

    case 'preprod':
        return array(
            'debug' => false,
            'url' => 'http://lrs.preprod.educlever.io/',
            'key' => '9fcYdJtHF0r09GGKrH2xqiTW61u9Pe8Z',
        );

    default :
        throw new \Exception("No config for env \"{$env}\"");
}
