<?php

namespace app\service\controller;
use think\swoole\Server;

class Demo extends Server
{
    // 监听所有地址
    protected $host = '0.0.0.0';
    // 监听 9501 端口
    protected $port = 9501;
    // 指定运行模式为多进程
    protected $mode = SWOOLE_PROCESS;
    // 指定 socket 的类型为 ipv4 的 tcp socket
    protected $sockType = SWOOLE_SOCK_TCP;
    // 配置项
    protected $option = [
        /**
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
     * @param \swoole_server $server swoole_server对象
     * @param $fd TCP客户端连接的文件描述符
     * @param $from_id TCP连接所在的Reactor线程ID
     * @param $data 收到的数据内容
     */
    public function onReceive(\swoole_server $server, $fd, $from_id, $data)
    {
        $server->send($fd, 'onReceive: ' . $data);
    }
}