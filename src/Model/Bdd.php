<?php

require_once 'vendor/autoload.php';
include_once 'config/configRedis.php';
include_once 'config/configMysql.php';
include_once 'src/Service/gestionErreur.php';

class Bdd
{
    private $redis;
    private $mysqli;
    static $namespaceErreur = 'redis';

    public function __construct()
    {
        try {
            $this->redis = new Predis\Client(REDIS_CONFIG);
            $this->mysqli = new mysqli(MYSQL_CONFIG['hostname'], MYSQL_CONFIG['username'],
                MYSQL_CONFIG['password'], MYSQL_CONFIG['bdd'],MYSQL_CONFIG['port']);

            if ($this->mysqli->connect_error) {
                die("Connexion échouée : " . $this->mysqli->connect_error);
            }

        }catch (Exception $e){
            gestionErreur($e,self::$namespaceErreur);
        }

    }

    public function getMysqli(): mysqli
    {
        return $this->mysqli;
    }

    public function closeConnection()
    {
        $this->mysqli->close();
    }

    public function getRedis(): \Predis\Client
    {
        return $this->redis;
    }

    public function miseEnCache($key,$data)
    {
        try {
            $this->redis->hMset($key, $data);
            $this->redis->expire($key, 3 * 3600);
            $this->redis->del("getAll_contacts");
        }catch (Error $e){

        }
    }

}