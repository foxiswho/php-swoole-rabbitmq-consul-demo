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

        Log::write('实时写入:'.var_export($consul->getService(),true));
//        $sub=$consul->getAmq();
//        //        $sub = amq::init();
//        $sub->getChannel();
//        $sub->getExchange();
//        $sub->setExchangeName('exchange_php');//交换机名
//        $sub->setExchangeType(AMQP_EX_TYPE_DIRECT);//direct类型
//        $sub->setExchangeFlags(AMQP_DURABLE); //持久化
//        //$sub->setExchangeDeclareExchange();
//        $queue=$sub->getQueue();
//        $sub->setQueueName('queue_php');
//        $sub->setQueueFlags(AMQP_DURABLE); //持久化
//        $sub->setQueueBind('exchange_php', 'route_php');
//        //$sub->setQueueDeclareQueue();//如果该队列已经存在不用再调用这个方法了
//        $envelope= $sub->QueueGet();
//        if($envelope){
//            $msg = $envelope->getBody();
//            Log::write('实时写入:'.var_export($msg,true));
//            trace('实时写入:'.var_export($msg,true));
//            $res = $queue->ack($envelope->getDeliveryTag());
//        }else{
//            trace('实时写入:发生错误');
//        }
        $config         = [];
        $config['host'] = $consul->getService()->Address;
        $config['port'] = $consul->getService()->Port;
        //配置信息
        if (is_array($config)) {
            $config = array_merge(config('amq.'), $config);
        }
        $exchange_name = 'exchange_php'; //交换机名
        $queue_name = 'queue_php'; //队列名
        $routing_key = 'route_php'; //路由key

        //创建连接和channel
        $conn = new \AMQPConnection($config);
        if (!$conn->connect()) {
            die("Cannot connect to the broker!\n");
        }
        $channel = new \AMQPChannel($conn);

        //创建交换机
        $ex = new \AMQPExchange($channel);
        $ex->setName($exchange_name);
        $ex->setType(AMQP_EX_TYPE_DIRECT); //direct类型
        $ex->setFlags(AMQP_DURABLE); //持久化
        echo "Exchange Status:".$ex->declareExchange()."\n";

        //创建队列
        $q = new \AMQPQueue($channel);
        $q->setName($queue_name);
        $q->setFlags(AMQP_DURABLE); //持久化
        echo "Message Total:".$q->declareQueue()."\n";

        //绑定交换机与队列，并指定路由键
        echo 'Queue Bind: '.$q->bind($exchange_name, $routing_key)."\n";

        //阻塞模式接收消息
        echo "Message:\n";
        /**
         * 消费回调函数
         * 处理消息
         */
        $processMessage=function ($envelope, $queue){
            $msg = $envelope->getBody();
            echo $msg."\n"; //处理消息
            Log::write('实时写入:' . var_export($msg, true));
            trace('实时写入:' . var_export($msg, true));
            $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
        };
        while(true){
            $q->consume($processMessage);
            //$q->consume('processMessage', AMQP_AUTOACK); //自动ACK应答
        }
        $conn->disconnect();
    }
}