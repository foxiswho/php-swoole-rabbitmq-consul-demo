<?php

namespace app\service\controller;

use app\module\amqConsul;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use think\facade\Log;

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
            $str= ' [x] Received '.$msg->body."\n";
            trace('basic_consume'.$str);
            Log::write('实时写入basic_consume:'.var_export($str,true));
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };
        $channel->basic_consume('queue_php', '', false, true, false, false, $callback);
        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }
}