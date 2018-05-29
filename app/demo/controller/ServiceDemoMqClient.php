<?php

namespace app\demo\controller;

use app\module\consul;

//本demo处理 app\service\controller\DemoMq
class ServiceDemoMqClient
{
    /**
     * 获取指定服务,发送数据，服务端处理数据，并返回数据
     */
    public function index()
    {
        //获取指定服务
        $services = consul::getServicesOne('php-mq-demo');
        if (isset($services) && $services) {
            trace($services);
            //                $ser->ID;
            //                $ser->Address;
            //                $ser->Service;
            //                $ser->Port;
            $client = new \swoole_client(SWOOLE_SOCK_TCP);
            //连接到服务器
            if (!$client->connect($services->Address, $services->Port, 0.5)) {
                throw new \Exception("swoole_client connect failed.");
            }
            //向服务器发送数据
            if (!$client->send("hello world")) {
                throw new \Exception("swoole_client send failed.");
            }
            //从服务器接收数据
            $data = $client->recv();
            if (!$data) {
                throw new \Exception("swoole_client recv failed.");
            }
            echo $data;
            //关闭连接
            $client->close();
        } else {
            throw new \Exception("Consul service not find.");
        }
    }
}