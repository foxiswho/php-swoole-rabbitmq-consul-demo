<?php

namespace app\mq\controller\publisher;

use app\module\amq;
use app\module\amqConsul;

class demo
{
    //生产者
    public static function index()
    {
        //使用 注册中心获取
        $pub = amqConsul::getServicesOne('RabbitMQ')->getAmq();
        //配置信息
//        $pub = amq::init();
        $pub->getChannel();
        $pub->getExchange();
        $pub->setExchangeName('exchange_php');//交换机名
        for ($i = 0; $i < 5; ++$i) {
            sleep(1);//休眠1秒
            //消息内容
            $message = "TEST MESSAGE!" . date("h:i:sa");
            echo $pub->ExchangePublish($message, 'route_php') . "\n";
        }
        $pub->disconnect();
    }
}