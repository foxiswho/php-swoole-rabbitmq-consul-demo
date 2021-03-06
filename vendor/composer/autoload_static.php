<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7861e09299ced4b6e56094d945d85f0a
{
    public static $files = array (
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'think\\swoole\\' => 13,
            'think\\composer\\' => 15,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'PhpAmqpLib\\' => 11,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
        'D' => 
        array (
            'DCarbone\\PHPConsulAPI\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'think\\swoole\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-swoole/src',
        ),
        'think\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-installer/src',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'PhpAmqpLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-amqplib/php-amqplib/PhpAmqpLib',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
        'DCarbone\\PHPConsulAPI\\' => 
        array (
            0 => __DIR__ . '/..' . '/dcarbone/php-consul-api/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7861e09299ced4b6e56094d945d85f0a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7861e09299ced4b6e56094d945d85f0a::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
