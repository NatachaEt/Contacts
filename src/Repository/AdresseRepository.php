<?php

namespace App\Repository;

use App\Model\Adresse;
use App\Model\Bdd;
use Exception;

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

    public function getById(int $id) :Adresse|null
    {
        $adresse = new Adresse();

        if(empty($id)){
            return $adresse;
        }

        $adresseKey = "adresse:$id";
        try {
            $donneesAdresse = $this->redis->hgetall($adresseKey);
        }catch (Exception $e){
            //TODO
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
                gestionErreur($e,self::$namespace,'mySql');
                return null;
            }

            if($donneesAdresse == null) return $adresse;
        }
        $adresse->setAdresse($donneesAdresse);
        $this->db->miseEnCache($adresseKey,$adresse->getDataCache());

        return $adresse;
    }

    public function add(Adresse $adresse) :void
    {
        $adresse->validate();
        if(!empty($adresse->getErrors())){
            return;
        }

        $departement = $adresse->getDepartement();
        $commune = $adresse->getCommune();
        $contact_id = $adresse->getContact();

        try{
            $query = "INSERT INTO adresses (contact_id, departement, commune) VALUES (?, ?, ?)";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param("iss",$contact_id, $departement, $commune);
            $retour = $stmt->execute();

            $adresseId = $this->mysqli->insert_id;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            gestionErreur($e,self::$namespace,'mySql');
            die();
        }

        $adresse->setId($adresseId);
        $adresseKey = "adresse:$adresseId";

        $this->db->miseEnCache($adresseKey,$adresse->getDataCache());
    }

    public function put(Adresse $adresse) :void
    {
        $adresse->validate();
        if(!empty($adresse->getErrors())){
            return;
        }

        $id = $adresse->getId();
        if(empty($id)) {
            $this->add($adresse);
            return;
        }

        $departement = $adresse->getDepartement();
        $commune = $adresse->getCommune();
        $contact_id = $adresse->getContact();

        try{
            $query = "UPDATE adresses SET contact_id = ?, departement = ?, commune = ? WHERE id = ?";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param("issi", $contact_id,$departement, $commune, $id);
            $retour = $stmt->execute();

        } catch (Exception $e) {
            var_dump($e->getMessage());
            gestionErreur($e,self::$namespace,'mySql');
        }

        $adresseKey = "adresse:$id";

        $this->db->miseEnCache($adresseKey,$adresse->getDataCache());
    }

}
