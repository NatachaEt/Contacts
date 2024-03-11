<?php


namespace App\Model;
use Config\Mysql;
use Config\Redis;
use mysqli;


class Bdd
{
    private $redis;
    private $mysqli;
    static $namespaceErreur = 'redis';

    public function __construct()
    {
        try {
            $this->redis = new \Predis\Client(Redis::getConfig());
            $this->mysqli = new mysqli(Mysql::getHost(), Mysql::getUsername(),
                Mysql::getPassword(), Mysql::getBdd(),Mysql::getPort());

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
        }catch (\Exception $e){
            //TODO
        }

    }

}
