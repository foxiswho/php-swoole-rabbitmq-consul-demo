<?php

namespace app\service\controller;

use app\module\amqConsul;
use think\facade\Log;

//默认action start
class DemoMqSub
{

    public function start()
    {
        trace("onReceive:DemoMqSub");
        //使用 注册中心获取
        $consul = amqConsul::getServicesOne('RabbitMQ');
        trace($consul->getService());
        $sub=$consul->getAmq();
        Log::write('实时写入:'.var_export($consul->getService(),true));
        //        $sub = amq::init();
        $sub->getChannel();
        $queue=$sub->getQueue();
        $sub->setQueueName('queue_php');
        $sub->setQueueBind('exchange_php', 'route_php');
        $sub->getExchange();
        $sub->setExchangeName('exchange_php');//交换机名
        $sub->setExchangeType(AMQP_EX_TYPE_DIRECT);//direct类型
        $sub->setExchangeFlags(AMQP_DURABLE); //持久化
        $envelope= $sub->QueueGet();
        if($envelope){
            $msg = $envelope->getBody();
            Log::write('实时写入:'.var_export($msg,true));
            trace('实时写入:'.var_export($msg,true));
            $res = $queue->ack($envelope->getDeliveryTag());
        }else{
            trace('实时写入:发生错误');
        }
    }
}