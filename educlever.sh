#!/bin/sh

dir=$(dirname $0)

composer require php-amqplib/php-amqplib

# TODO ne pas faire si la ligne est déjà dans le fichier
cat << EOF >> ${dir}/app/listeners.php
require(__DIR__ . '/../educlever-listeners.php');
EOF

chmod a+rw -R ${dir}app/storage/
