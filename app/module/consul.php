<?php

namespace app\module;

use DCarbone\PHPConsulAPI\QueryOptions;

class consul
{
    /**
     * @var \DCarbone\PHPConsulAPI\Consul
     * @return \DCarbone\PHPConsulAPI\Consul
     */
    private static $consul = null;

    public static function getConfig()
    {
        $proxyClient = new \GuzzleHttp\Client();
        $config      = new \DCarbone\PHPConsulAPI\Config(['HttpClient'         => $proxyClient,
                                                          // [required] Client conforming to GuzzleHttp\ClientInterface
                                                          'Address'            => config('consul.address'),
                                                          // [required]
                                                          'Scheme'             => 'http',
                                                          // [optional] defaults to "http"
                                                          'Datacenter'         => config('consul.data_center'),
                                                          // [optional]
                                                          'HttpAuth'           => '',
                                                          // [optional] user:pass
                                                          'Token'              => '',
                                                          // [optional] default auth token to use
                                                          'TokenInHeader'      => false,
                                                          // [optional] specifies whether to send the token in the header or query string
                                                          'InsecureSkipVerify' => false,
                                                          // [optional] if set to true, ignores all SSL validation
                                                          'CAFile'             => '',
                                                          // [optional] path to ca cert file, see http://docs.guzzlephp.org/en/latest/request-options.html#verify
                                                          'CertFile'           => '',
                                                          // [optional] path to client public key.  if set, requires KeyFile also be set
                                                          'KeyFile'            => '',
                                                          // [optional] path to client private key.  if set, requires CertFile also be set
        ]);
        return $config;
    }

    /**
     * @return \DCarbone\PHPConsulAPI\Consul
     */
    public static function getConsul()
    {
        if (!isset(self::$consul)) {
            self::$consul = new \DCarbone\PHPConsulAPI\Consul(self::getConfig());
        }
        return self::$consul;
    }

    public static function getKeys()
    {
        return self::getConsul()->KV()->keys();
    }

    /**
     * @return array
     */
    public static function getServicesAll()
    {
        return self::getConsul()->Agent()->services();
    }

    /**获取服务
     * @return array
     */
    public static function getServices($service, $tag = '', $passingOnly = false, QueryOptions $options = null)
    {
        return self::getConsul()->Health()->service($service, $tag, $passingOnly, $options);
    }

    /**获取服务 单个服务
     * @param                   $service
     * @param string            $tag
     * @param bool              $passingOnly
     * @param QueryOptions|null $options
     * @return bool|object
     *  object->ID
     *  object->Address
     *  object->Port
     *  object->Service
     *
     */
    public static function getServicesOne($service, $tag = '', $passingOnly = false, QueryOptions $options = null)
    {
        $services = self::getConsul()->Health()->service($service, $tag, $passingOnly, $options);
        if (isset($services[0]) && isset($services[0][0])) {
            if (isset($services[0][0]->Service)) {
                return $services[0][0]->Service;
            }
        }
        return false;
    }

    /**注册服务
     * @param       $ID
     * @param       $Name
     * @param       $Address
     * @param       $Port
     * @param bool  $EnableTagOverride
     * @param null  $Check  \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck
     * @param array $Checks \DCarbone\PHPConsulAPI\Agent\AgentServiceCheck[]
     * @return \DCarbone\PHPConsulAPI\Error|null
     */
    public static function registerService($ID, $Name, $Address, $Port, $EnableTagOverride = false, $Check = null, $Checks = [])
    {
        $config                      = [];
        $config['ID']                = '';
        $config['Name']              = '';
        $config['Port']              = '';//int
        $config['Address']           = '';
        $config['EnableTagOverride'] = '';//bool
        $config['Check']             = '';
        $config['Checks']            = '';
        $agentServiceRegistration    = new \DCarbone\PHPConsulAPI\Agent\AgentServiceRegistration();
        $agentServiceRegistration->setID($ID);
        $agentServiceRegistration->setName($Name);
        $agentServiceRegistration->setAddress($Address);
        $agentServiceRegistration->setPort($Port);
        $agentServiceRegistration->setEnableTagOverride($EnableTagOverride);
        if ($Check) {
            $agentServiceRegistration->setCheck($Check);
        }
        if ($Checks) {
            $agentServiceRegistration->setChecks($Checks);
        }
        return self::getConsul()->Agent()->serviceRegister($agentServiceRegistration);
    }
}