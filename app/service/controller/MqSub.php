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
        trace('MqSub::::');
        print_r($config);
        $connection = new AMQPStreamConnection($config->Address, $config->Port, config('amq.login'), config('amq.password'));
        $channel    = $connection->channel();
        $channel->queue_declare('queue_php', false, true, false, false);
        $channel->exchange_declare('exchange_php','direct',false,true,false,false);
        $channel->queue_bind('queue_php', 'exchange_php','route_php');

        //
        $callback = function ($msg) {
            $str= ' [x] Received '.$msg->body."\n";
            trace('basic_consume'.$str);
            Log::write('实时写入basic_consume:'.var_export($str,true));
            if(isset($msg->delivery_info->delivery_tag)){
                $msg->delivery_info->channel->basic_ack($msg->delivery_info->delivery_tag);
            }
        };
        //设置参数prefetch_count = 1。
        //这告诉RabbitMQ不要在一个时间给一个消费者多个消息。或者，换句话说，在处理和确认以前的消息之前，不要向消费者发送新消息
        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('queue_php', '', false, true, false, false, $callback);
        while (count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }
}