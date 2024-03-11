<?php

namespace Config;

class Mysql {
    private static $hostname = 'localhost';
    private static $username = 'root';
    private static $password = '';
    private static $bdd = 'devcloud_contact';
    private static $port = '3307';

    public static function getHost() {
        return self::$hostname;
    }

    public static function getUsername() {
        return self::$username;
    }

    public static function getPassword() {
        return self::$password;
    }

    public static function getBdd() {
        return self::$bdd;
    }

    public static function getPort() {
        return self::$port;
    }
}

