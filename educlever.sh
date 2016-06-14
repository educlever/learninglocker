#!/bin/sh

dir=$(dirname $0)
action=${1}

case ${action} in

    install)
        exists=$(grep php-amqplib ${dir}/composer.json)
        if [ -z "$exists" ]
        then
            composer require php-amqplib/php-amqplib
        fi

        composer install

        exists=$(grep educlever-listeners.php ${dir}/app/listeners.php)
        if [ -z "$exists" ]
        then
            cat << EOF >> ${dir}/app/listeners.php
require_once(__DIR__ . '/../educlever-listeners.php');
EOF
        fi

        mkdir ${dir}/app/storage/
        mkdir ${dir}/app/storage/cache/
        mkdir ${dir}/app/storage/clockwork/
        mkdir ${dir}/app/storage/debugbar/
        mkdir ${dir}/app/storage/logs/
        mkdir ${dir}/app/storage/meta/
        mkdir ${dir}/app/storage/sessions/
        mkdir ${dir}/app/storage/views/

        chmod a+rw ${dir}/app/storage/*

        view=app/views/layouts/loggedout.blade.php
        exists=$(grep "Logo Educlever" ${view})
        if [ -z "$exists" ]
        then
            cp $dir/educlever-logo.png $dir/public/
            sed -i -E 's#(<div class="logo">)#\1<img src="/educlever-logo.png" alt="Logo Educlever">#' ${view}
        fi

;;
esac
