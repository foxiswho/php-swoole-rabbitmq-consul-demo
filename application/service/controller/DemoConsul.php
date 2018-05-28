<?php
/**
 * Created by PhpStorm.
 * User: fox
 * Date: 2018/5/28
 * Time: 上午9:55
 */

namespace app\service\controller;

class DemoConsul
{
    public function index()
    {
        $proxyClient = new \GuzzleHttp\Client(['proxy' => 'whatever proxy you want']);
        $config      = new \DCarbone\PHPConsulAPI\Config(['HttpClient'         => $proxyClient,
                                                          // [required] Client conforming to GuzzleHttp\ClientInterface
                                                          'Address'            => 'address of server',
                                                          // [required]
                                                          'Scheme'             => 'http or https',
                                                          // [optional] defaults to "http"
                                                          'Datacenter'         => 'name of datacenter',
                                                          // [optional]
                                                          'HttpAuth'           => 'user:pass',
                                                          // [optional]
                                                          'Token'              => 'auth token',
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
        $consul = new \DCarbone\PHPConsulAPI\Consul($config);
        list($kv_list, $qm, $err) = $consul->KV->keys();
        if (null !== $err) {
            die($err);
        }
        var_dump($kv_list);
    }
}