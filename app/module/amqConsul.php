<?php

namespace app\module;

use DCarbone\PHPConsulAPI\QueryOptions;

class amqConsul
{
    private static $service;

    /**获取服务 单个服务
     * @param                   $service
     * @param string            $tag
     * @param bool              $passingOnly
     * @param QueryOptions|null $options
     * @return amqConsul
     *
     */
    public static function getServicesOne($service, $tag = '', $passingOnly = false, QueryOptions $options = null)
    {
        self::$service = consul::getServicesOne($service, $tag, $passingOnly, $options);
        return new self();
    }

    /**
     * @return object
     *  object->ID
     *  object->Address
     *  object->Port
     *  object->Service
     *
     */
    public function getService()
    {
        return self::$service;
    }

    /**
     * @return amq
     * @throws \Exception
     */
    public function getAmq()
    {
        if (isset(self::$service) && self::$service) {
            $config         = [];
            $config['host'] = self::$service->Address;
            $config['port'] = self::$service->Port;
            return amq::init($config);
        }
        throw new \Exception("consul getServicesOne failed.");
    }
}