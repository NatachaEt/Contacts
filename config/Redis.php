<?php

namespace Config;

class Redis {
    private static $host = 'redis-10087.c323.us-east-1-2.ec2.cloud.redislabs.com:10087';
    private static $port = 6379;
    private static $password = 'MRBPq776KvNXRCmV2O4iSwx7KCRuJNV8';

    public static function getHost() {
        return self::$host;
    }

    public static function getPort() {
        return self::$port;
    }

    public static function getConfig() {
        return [
            'scheme' => 'tcp',
            'host' => self::$host,
            'port' => self::$port,
            'password' => self::$password,
        ];
    }
}

