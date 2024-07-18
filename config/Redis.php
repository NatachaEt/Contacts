<?php

namespace Config;

class Redis {
    private static $host = '';
    private static $port = 6379;
    private static $password = '';

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

