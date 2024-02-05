<?php

require_once 'vendor/autoload.php';
include_once 'config/configRedis.php';
include_once 'src/Service/gestionErreur.php';

class Bdd
{
    private $redis;
    static $namespaceErreur = 'redis';

    public function __construct()
    {
        try {
            $this->redis = new Predis\Client(REDIS_CONFIG);
        }catch (Exception $e){
            gestionErreur($e,self::$namespaceErreur);
        }
    }

    public function getRedis()
    {
        return $this->redis;
    }

}