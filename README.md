# php-thinkphp-swoole-rabbitmq-consul-demo

还未完成

# 一步步从零开始
## 下载框架

https://www.kancloud.cn/manual/thinkphp5_1/353948
```SHELL
composer create-project topthink/think php-thinkphp5-swoole
```

## 安装swoole 扩展
https://github.com/top-think/think-swoole
```SHELL
composer require topthink/think-swoole
```

## 安装 consul API 扩展
https://www.consul.io/api/libraries-and-sdks.html
https://github.com/dcarbone/php-consul-api
```SHELL
composer require dcarbone/php-consul-api
```

# 服务
```SHELL
#PHP服务
php public/index.php service/demo/start

#生产者 发出信息
php public/index.php mq/publisher.demo/index

#订阅者 处理消息
php public/index.php mq/subscriber.demo/index
```
浏览器
```angular2html
http://localhost/demo/service_demo_client/index
或
http://localhost/demo/service_demo_mq_client/index
```

# 测试案例
当前根目录下执行
```SHELL
php public/index.php service/demo/start
```
在新的终端中执行：
```SEHLL
telnet 127.0.0.1 9501
```
然后在终端中输入 `hello`

终端打印出
```SHELL
onReceive: hello
```

更多的请看 https://www.kancloud.cn/chunice/think-swoole