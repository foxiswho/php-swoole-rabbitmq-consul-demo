<?php

namespace app\service\controller;

use app\module\amqConsul;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class MqSub
{
    public function start()
    {
        //使用 注册中心获取
        $consul = amqConsul::getServicesOne('RabbitMQ');
        $config = $consul->getService();
        trace($consul->getService());
        trace('swoole_server onReceive');
        $connection = new AMQPStreamConnection($config->Address, $config->Port, config('amq.login'), config('amq.password'));
        $channel    = $connection->channel();
        $channel->queue_declare('queue_php', false, true, false, false);
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };
        $channel->basic_consume('queue_php', '', false, true, false, false, $callback);
        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }
}