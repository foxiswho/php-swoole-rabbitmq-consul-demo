<?php
/**
 * Created by PhpStorm.
 * User: fox
 * Date: 2018/5/28
 * Time: 上午9:55
 */

namespace app\service\controller;

use app\util\consul;

class DemoConsul
{
    /**
     * 获取指定服务
     */
    public function index()
    {
        echo "获取所有keys\n";
        list($kv_list, $qm, $err) = consul::getKeys();
        if (null !== $err) {
            die($err);
        }
        var_dump($kv_list);
        echo "获取所有服务\n";
        //获取所有服务
        $services=consul::getServicesAll();
        print_r($services);
        //获取指定服务
        echo "获取指定服务\n";
        $services=consul::getServices('php-demo');
        print_r($services);
        if(isset($services[0])&&isset($services[0][0])&& $services[0][0]->Service){
            echo "输出一个服务\n";
            print_r($services[0][0]->Service);
        }

    }
}