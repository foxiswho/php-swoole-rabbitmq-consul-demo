<?php

namespace app\mq\controller\subscriber;

use app\module\amq;
use app\module\amqConsul;

class demo
{
    public static function index()
    {
        //使用 注册中心获取
        $sub = amqConsul::getServicesOne('RabbitMQ')->getAmq();
//        $sub = amq::init();
        $sub->getChannel();
        $sub->getExchange();
        $sub->setExchangeName('exchange_php');//交换机名
        $sub->setExchangeType(AMQP_EX_TYPE_DIRECT);//direct类型
        $sub->setExchangeFlags(AMQP_DURABLE); //持久化
        $sub->setExchangeDeclareExchange();
        $sub->getQueue();
        $sub->setQueueName('queue_php');
        $sub->setQueueFlags(AMQP_DURABLE); //持久化
        $sub->setQueueDeclareQueue();
        $sub->setQueueBind('queue_php', 'route_php');
        /**
         * 消费回调函数
         * 处理消息
         */
        function processMessage($envelope, $queue)
        {
            $msg = $envelope->getBody();
            echo $msg . "\n"; //处理消息
            $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
        }

        while (true) {
            $sub->setQueueConsume('processMessage');
            //$q->consume('processMessage', AMQP_AUTOACK); //自动ACK应答
        }
        $sub->close();
    }

}