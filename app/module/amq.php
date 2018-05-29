<?php

namespace app\module;

class amq
{
    /**
     * @var \AMQPConnection
     */
    private static $conn;
    /**
     * @var \AMQPChannel
     */
    private static $channel;
    /**
     * @var \AMQPExchange
     */
    private static $Exchange;
    /**
     * @var \AMQPQueue
     */
    private static $Queue;

    /**
     * @param array $config  [
     *                       'host'     => '127.0.0.1',
     *                       'port'     => '5672',
     *                       'login'    => 'guest',
     *                       'password' => 'guest',
     *                       'vhost'    => '/'
     *                       ]
     */
    public static function init($config = [])
    {
        return new self($config);
    }

    /**
     * @param array $config  [
     *                       'host'     => '127.0.0.1',
     *                       'port'     => '5672',
     *                       'login'    => 'guest',
     *                       'password' => 'guest',
     *                       'vhost'    => '/'
     *                       ]
     */
    public function __construct($config = [])
    {
        $this->getConn($config);
    }

    /**
     * @param array $config  [
     *                       'host'     => '127.0.0.1',
     *                       'port'     => '5672',
     *                       'login'    => 'guest',
     *                       'password' => 'guest',
     *                       'vhost'    => '/'
     *                       ]
     * @return \AMQPConnection
     * @throws \AMQPConnectionException
     */
    public function getConn($config = [])
    {
        if (!isset(self::$conn)) {
            //配置信息
            if (!$config) {
                $config = config('amq');
            } elseif (is_array($config)) {
                $config = array_merge(config('amq'), $config);
            }
            //创建连接和channel
            self::$conn = new \AMQPConnection($config);
            if (!self::$conn->connect()) {
                throw new \Exception("Cannot connect to the broker!\n");
            }
        }
        return self::$conn;
    }

    public function getChannel()
    {
        if (!isset(self::$channel)) {
            self::$channel = new \AMQPChannel($this->getConn());
        }
        return self::$channel;
    }

    public function getExchange()
    {
        if (!isset(self::$Exchange)) {
            self::$Exchange = new \AMQPExchange($this->getChannel());
        }
        return self::$Exchange;
    }

    public function getQueue()
    {
        if (!isset(self::$Queue)) {
            self::$Queue = new \AMQPQueue($this->getChannel());
        }
        return self::$Queue;
    }

    public function close()
    {
        if (isset(self::$conn)) {
            self::$Exchange = null;
            self::$channel  = null;
            self::$Queue    = null;
            self::$conn->disconnect();
            self::$conn = null;
        }
    }

    public function disconnect()
    {
        $this->close();
    }

    public function setExchangeName($exchange_name)
    {
        return $this->getExchange()->setName($exchange_name);
    }

    public function setExchangeType($exchange_type)
    {
        return $this->getExchange()->setType($exchange_type);
    }

    public function setExchangeFlags($flags)
    {
        return $this->getExchange()->setFlags($flags);
    }

    public function setExchangeDeclareExchange()
    {
        return $this->getExchange()->declareExchange();
    }

    public function ExchangeDeclareExchange()
    {
        return $this->getExchange()->declareExchange();
    }

    public function ExchangePublish($message, $routing_key)
    {
        return $this->getExchange()->publish($message, $routing_key);
    }

    public function setExchangePublish($message, $routing_key)
    {
        return $this->getExchange()->publish($message, $routing_key);
    }

    public function setChannelStartTransaction()
    {
        return $this->getChannel()->startTransaction();
    }

    public function setChannelCommitTransaction()
    {
        return $this->getChannel()->commitTransaction();
    }

    public function setQueueName($queue_name)
    {
        return $this->getQueue()->setName($queue_name);
    }

    public function setQueueFlags($flags)
    {
        return $this->getQueue()->setFlags($flags);
    }

    public function QueueDeclareQueue()
    {
        return $this->getQueue()->declareQueue();
    }

    public function setQueueDeclareQueue()
    {
        return $this->getQueue()->declareQueue();
    }

    public function QueueBind($exchange_name, $routing_key = null, array $arguments = [])
    {
        return $this->getQueue()->bind($exchange_name, $routing_key, $arguments);
    }

    public function setQueueBind($exchange_name, $routing_key = null, array $arguments = [])
    {
        return $this->getQueue()->bind($exchange_name, $routing_key, $arguments);
    }

    public function QueueConsume(callable $callback = null, $flags = AMQP_NOPARAM, $consumerTag = null)
    {
        return $this->getQueue()->consume($callback, $flags, $consumerTag);
    }

    public function setQueueConsume(callable $callback = null, $flags = AMQP_NOPARAM, $consumerTag = null)
    {
        return $this->getQueue()->consume($callback, $flags, $consumerTag);
    }
}