<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$env = getenv('EC_ENV');
if (empty($env)) {
    $env = 'dev';
}

switch ($env) {

    case 'dev':
        $qConfig = [
            'host' => 'lrs.mxc',
            'port' => 5672,
            'user' => 'guest',
            'password' => 'guest',
            //
            'queue_name' => 'lrs',
        ];
        break;

    case 'demo':
        $qConfig = [
            'host' => 'localhost',
            'port' => 5672,
            'user' => 'guest',
            'password' => 'guest',
            //
            'queue_name' => 'lrs',
        ];
        break;

    default:
        throw new \Exception("No config for env \"{$env}\"");
}

function educlever_config(array &$qConfig, $key, $defaultValue = null)
{
    if (isset($qConfig[$key])) {
        return $qConfig[$key];
    }
    return $defaultValue;
}

\Event::listen(
    'Statements.store',
    function ($statements) use ($qConfig) {
        $encodedStatements = @json_encode($statements);
        if (JSON_ERROR_NONE !== json_last_error()) {
            error_log("Statements.store listener, json_encode error : " . json_last_error_msg());
        } else {

            // connexion au serveur de Q

            $host = educlever_config($qConfig, 'host');
            $port = educlever_config($qConfig, 'port');
            $user = educlever_config($qConfig, 'user');
            $password = educlever_config($qConfig, 'password');
            $vhost = educlever_config($qConfig, 'vhost', '/');
            $insist = educlever_config($qConfig, 'insist', false);
            $login_method = educlever_config($qConfig, 'login_method', 'AMQPLAIN');
            $login_response = educlever_config($qConfig, 'login_response');
            $locale = educlever_config($qConfig, 'locale', 'en_US');
            $connection_timeout = educlever_config($qConfig, 'connection_timeout', 3.0);
            $read_write_timeout = educlever_config($qConfig, 'read_write_timeout', 3.0);
            $context = null; // cf. stream_socket_client context parameter
            $keepalive = educlever_config($qConfig, 'keepalive', false);
            $heartbeat = educlever_config($qConfig, 'heartbeat', 0);

            try {
                $connection = new AMQPStreamConnection(
                    $host,
                    $port,
                    $user,
                    $password,
                    $vhost,
                    $insist,
                    $login_method,
                    $login_response,
                    $locale,
                    $connection_timeout,
                    $read_write_timeout,
                    $context,
                    $keepalive,
                    $heartbeat
                );
            } catch (\Exception $e) {
                error_log($e);
                return;
            }

            $channel = $connection->channel();

            // mise en place de la Q

            // FSI 2016-05-18 15:32:15 : pas sûr que ce soit le bon endroit
            // pour créer la queue lorsqu'elle n'existe pas encore...
            // TODO étudier le paramêtre "passive"

            // cf. https://www.rabbitmq.com/amqp-0-9-1-reference.html
            $queue = educlever_config($qConfig, 'queue_name');
            $passive = educlever_config($qConfig, 'queue_passive', false);
            $durable = educlever_config($qConfig, 'queue_durable', true);
            $exclusive = educlever_config($qConfig, 'queue_exclusive', false);
            $auto_delete = educlever_config($qConfig, 'queue_auto_delete', true);
            $nowait = educlever_config($qConfig, 'queue_nowait', false);
            $arguments = null;
            $ticket = null;

            try {
                $channel->queue_declare(
                    $queue,
                    $passive,
                    $durable,
                    $exclusive,
                    $auto_delete,
                    $nowait,
                    $arguments,
                    $ticket
                );
            } catch (\Exception $e) {
                error_log($e);
                return;
            }

            // émission du message

            // cf. https://www.rabbitmq.com/amqp-0-9-1-reference.html
            $exchange = '';
            $routing_key = educlever_config($qConfig, 'queue_name');
            $mandatory = true;
            $immediate = false;
            $ticket = null;

            error_log(__METHOD__.' basic_publish');
            $channel->basic_publish(
                new AMQPMessage($encodedStatements),
                $exchange,
                $routing_key,
                $mandatory,
                $immediate,
                $ticket
            );

            // FSI 2016-05-18 17:59:04 : J'ai peur que ça déconnecte les consumers
            // TODO : à vérifier
            $channel->close();

            $connection->close();
        }
    }
);
