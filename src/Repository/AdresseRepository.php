<?php
include_once 'config/config.php';

if(CONFIG['env'] == 'dev')
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

class AdresseRepository
{
    private $db;
    private $mysqli;
    private $redis;

    static string $namespace = 'adresse';

    public function __construct()
    {
        $this->db = new Bdd();
        $this->mysqli = $this->db->getMysqli();
        $this->redis = $this->db->getRedis();
    }

    public function getAll() :array
    {
        try {
            $allAdressesKey = "getAll_adresses";
            $donneesAdresses = $this->redis->get($allAdressesKey);

            if (empty($donneesAdresses)) {
                $query = "SELECT * FROM adresses";
                $result = $this->mysqli->query($query);
                $donneesAdresses = [];

                while ($row = $result->fetch_assoc()) {
                    $adresse = new Adresse();
                    $adresse->setAdresse($row);
                    $donneesAdresses[] = $donneesAdresses;
                }
                $this->redis->set($allAdressesKey, json_encode($donneesAdresses));
            } else {
                $donneesAdresses = json_decode($donneesAdresses);
            }

            return $donneesAdresses;
        }catch (Exception $e) {
            return gestionErreur($e,self::$namespace.'_getAll');
        }
    }

    public function getById(int $id) :Adresse|array
    {
        $adresse = new Adresse();

        if(empty($id)){
            return $adresse;
        }

        $adresseKey = "adresse:$id";

        try {
            $donneesAdresse = $this->redis->hgetall($adresseKey);
        }catch (Exception $e){
            return gestionErreur($e,self::$namespace, 'redis');
        }

        if (empty($donneesAdresse)) {
            try {
                $query = "SELECT * FROM adresses WHERE id = ?";
                $stmt = $this->mysqli->prepare($query);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $donneesAdresse = $result->fetch_assoc();
            }catch (Exception $e){
                return gestionErreur($e,self::$namespace,'mySql');
            }

            if($donneesAdresse == null) return $adresse;
        }

        $adresse->setAdresse($donneesAdresse);
        $this->db->miseEnCache($adresseKey,$adresse->getDataCache());

        return $adresse;
    }

    public function add(Adresse $adresse) {

    }

}
