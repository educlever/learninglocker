#!/bin/sh

dir=$(dirname $0)

composer require php-amqplib/php-amqplib

cat << EOF >> ${dir}/app/listeners.php
require(__DIR__ . '/../../educlever-listeners.php');
EOF
