<?php

namespace app\service\controller;

use app\module\amqConsul;
use think\facade\Log;
use think\swoole\Server;

//默认action start
class DemoMqSub extends Server
{
    // 监听所有地址
    protected $host = '0.0.0.0';
    // 监听 9501 端口
    protected $port = 9503;
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
                         'worker_num' => 4,
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
            Log::write('实时写入:'.$msg,'notice');
            $queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
        }

        while (true) {
            $sub->setQueueConsume('processMessage');
            //$q->consume('processMessage', AMQP_AUTOACK); //自动ACK应答
        }
        $sub->close();
        $server->send($fd, 'onReceive: ' . $data);
    }
}