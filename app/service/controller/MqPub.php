<?php

namespace app\service\controller;

use app\module\amqConsul;
use think\swoole\Server;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//默认action start
class MqPub extends Server
{
    // 监听所有地址
    protected $host = '0.0.0.0';
    // 监听 9501 端口
    protected $port = 9505;
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
        $config=$consul->getService();
        trace($consul->getService());
        trace('swoole_server onReceive');

        $connection = new AMQPStreamConnection($config->Address, $config->Port, config('amq.login'), config('amq.password'));
        $channel = $connection->channel();
        $channel->exchange_declare('exchange_php','direct',false,true);
        $channel->queue_bind('queue_php','exchange_php','route_php');
        $channel->queue_declare('queue_php', false, true, false, false);
        $message = "这是消息 TEST MESSAGE! " . date('Y-m-d H:i:s');
        $AMQPMessage=new AMQPMessage($message,['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
        $ret= $channel->basic_publish($AMQPMessage, 'exchange_php', 'route_php');
        trace('ExchangePublish'.var_export($ret,true));
        $channel->close();
        $connection->close();
        $message = "这是消息 TEST MESSAGE! " . date('Y-m-d H:i:s');
        $server->send($fd, 'onReceive: ' . $data.":".$message);
    }
}