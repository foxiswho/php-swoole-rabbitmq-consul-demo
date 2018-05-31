<?php

namespace app\service\controller;

use app\module\amqConsul;
use think\swoole\Server;

//默认action start
class DemoMq extends Server
{
    // 监听所有地址
    protected $host = '0.0.0.0';
    // 监听 9501 端口
    protected $port = 9561;
    // 指定运行模式为多进程
    protected $mode = SWOOLE_PROCESS;
    // 指定 socket 的类型为 ipv4 的 tcp socket
    protected $sockType = SWOOLE_SOCK_TCP;
    // 配置项
    protected $option = [/**
                          *  设置启动的worker进程数
                          *  业务代码是全异步非阻塞的，这里设置为CPU的1-4倍最合理
                          *  业务代码为同步阻塞，需要根据请求响应时间和系统负载来调整
                          */
                         'worker_num' => 1,
                         // 守护进程化
                         'daemonize'  => false,
                         // 监听队列的长度
                         'backlog'    => 128
    ];

    /**
     * 收到信息时回调函数
     * @param \swoole_server $server  swoole_server对象
     * @param                $fd      TCP客户端连接的文件描述符
     * @param                $from_id TCP连接所在的Reactor线程ID
     * @param                $data    收到的数据内容
     */
    public function onReceive(\swoole_server $server, $fd, $from_id, $data)
    {
        //使用 注册中心获取
        $consul=amqConsul::getServicesOne('RabbitMQ');
        trace($consul->getService());
        trace('swoole_server onReceive');
//        $pub = $consul->getAmq();
//        //配置信息
//        //        $pub = amq::init();
//        $pub->getChannel();
//        $pub->getExchange();
//        $pub->setExchangeName('exchange_php');//交换机名
//        $pub->setExchangeType(AMQP_EX_TYPE_DIRECT);//direct类型
//        $pub->setExchangeFlags(AMQP_DURABLE); //持久化
//        $pub->setExchangeDeclareExchange();//声明一个新交换机，如果这个交换机已经存在了，就不需要再调用
//        $pub->getQueue();
//        $pub->setQueueName('queue_php');
//        $pub->setQueueFlags(AMQP_DURABLE); //持久化
//        $pub->setQueueBind('exchange_php','route_php');
//        $pub->setQueueDeclareQueue();//如果该队列已经存在不用再调用这个方法了
        //消息内容
//        $message = "这是消息 TEST MESSAGE! " . date('Y-m-d H:i:s');
//        $ret= $pub->ExchangePublish($message, 'route_php',AMQP_DURABLE) . "\n";
//        trace('ExchangePublish'.var_export($ret,true));
        //$pub->disconnect();
        $config         = [];
        $config['host'] = $consul->getService()->Address;
        $config['port'] = $consul->getService()->Port;
        //配置信息
        if (is_array($config)) {
            $config = array_merge(config('amq.'), $config);
        }
        $exchange_name = 'exchange_php'; //交换机名
        $q_name = 'q_linvo'; //无需队列名
        $routing_key = 'route_php'; //路由key

        //创建连接和channel
        $conn = new \AMQPConnection($config);
        if (!$conn->connect()) {
            throw new \Exception("Cannot connect to the broker!");
        }
        $channel = new \AMQPChannel($conn);
        //消息内容
        $message = "TEST MESSAGE! 测试消息！";
        //创建交换机对象
        $ex = new \AMQPExchange($channel);
        $ex->setName($exchange_name);
        $ex->setType(AMQP_EX_TYPE_DIRECT);
        $ex->setFlags(AMQP_DURABLE);
        //发送消息
        //$channel->startTransaction(); //开始事务
        $ex->publish($message, $routing_key);
        //$channel->commitTransaction(); //提交事务
        $conn->disconnect();



        $message = "这是消息 TEST MESSAGE! " . date('Y-m-d H:i:s');
        $server->send($fd, 'onReceive: ' . $data.":".$message);
    }
}