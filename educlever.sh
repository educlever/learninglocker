#!/bin/sh

dir=$(dirname $0)

exists=$(grep php-amqplib ${dir}/composer.json)
if [ -z "$exists" ]
then
    composer require php-amqplib/php-amqplib
fi

composer install

exists=$(grep educlever-listeners.php ${dir}/app/listeners.php)
if [ $z "$exists" ]
then
    cat << EOF >> ${dir}/app/listeners.php
require_once(__DIR__ . '/../educlever-listeners.php');
EOF
fi

chmod a+rw -R ${dir}/app/storage/

view=app/views/layouts/loggedout.blade.php
exists=$(grep "Logo Educlever" ${view})
if [ -z "$exists" ]
then
    sed -i -E 's#(<div class="logo">)#\1<img src="http://demo.educlever.io/img.png" alt="Logo Educlever">#' ${view}
fi

