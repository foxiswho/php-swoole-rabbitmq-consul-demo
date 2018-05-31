<?php

namespace app\demo\controller;

use app\module\consul;

//本demo处理 app\service\controller\Demo
class ServiceDemoClient
{
    /**
     * 获取指定服务,发送数据，服务端处理数据，并返回数据
     */
    public function index()
    {
        //获取指定服务
        $services = consul::getServicesOne('php-demo');
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
            //$data = $client->recv(65535, \swoole_client::MSG_PEEK | \swoole_client::MSG_WAITALL);

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

    /**
     * 获取指定服务
     */
    public function getService()
    {
        echo "获取所有keys\n";
        list($kv_list, $qm, $err) = consul::getKeys();
        if (null !== $err) {
            die($err);
        }
        var_dump($kv_list);
        echo "获取所有服务\n";
        //获取所有服务
        $services = consul::getServicesAll();
        print_r($services);
        //获取指定服务
        echo "获取指定服务\n";
        $services = consul::getServices('php-demo');
        print_r($services);
        if (isset($services[0]) && isset($services[0][0]) && $services[0][0]->Service) {
            echo "输出一个服务\n";
            print_r($services[0][0]->Service);
        }
    }
}