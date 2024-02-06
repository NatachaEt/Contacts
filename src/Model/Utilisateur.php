<?php

include_once 'src/Service/gestionErreur.php';
include_once 'src/Service/validateData.php';
include_once 'src/Model/Bdd.php';

class Utilisateur
{
    private int $id = 0;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $telephone;
    private array $errors = [];
    private $db;
    private $mysqli;
    private $redis;
    static string $namespaceUtilisateur = 'utilisateur';

    public function __construct(String $nom = '',String $prenom = '',String $email = '',String $telephone = '')
    {
        $this->nom = trim($nom);
        $this->prenom = trim($prenom);
        $this->email = trim($email);
        $this->telephone = trim($telephone);

        $this->db = new Bdd();
        $this->mysqli = $this->db->getMysqli();
        $this->redis = $this->db->getRedis();
    }

    public function setId(string $id) {
        $this->id = $id;
    }

    public function setUtilisateur(array $data) {
        foreach ($data as $key => $value) {
            if($key == 'id') $this->id = $value;
            if($key == 'nom') $this->nom = $value;
            if($key == 'prenom') $this->prenom = $value;
            if($key == 'email') $this->email = $value;
            if($key == 'telephone') $this->telephone = $value;
        }
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function  getEmail() {
        return $this->email;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getDataCache()
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'telephone' => $this->telephone,
        ];
    }

    public function getAll() {
        try {
            $allUsersKey = "getAll_contacts";
            $donneesContacts = $this->redis->get($allUsersKey);
            if (empty($donneesContacts)) {
                $query = "SELECT * FROM contacts";
                $result = $this->mysqli->query($query);
                $donneesContacts = [];

                while ($row = $result->fetch_assoc()) {
                    $donneesContacts[] = $row;
                }

                $this->redis->set($allUsersKey, json_encode($donneesContacts));
            } else {
                $donneesContacts = json_decode($donneesContacts, true);
            }
            return $donneesContacts;
        }catch (Exception $e) {
            return gestionErreur($e,self::$namespaceUtilisateur.'_getAll');
        }
    }

    public function get(): array
    {
        try {
            if(empty($this->id)){
                return ['error' => 'L\'utilisateur n\'exsite pas'];
            }
            $userKey = "user:$this->id";
            $donneesUtilisateur = $this->redis->hgetall($userKey);

            if (!$donneesUtilisateur) {
                $query = "SELECT * FROM contacts WHERE id = ?";
                $stmt = $this->mysqli->prepare($query);
                $stmt->bind_param("i", $this->id);
                $stmt->execute();
                $result = $stmt->get_result();
                $donneesUtilisateur = $result->fetch_assoc();
                $this->db->miseEnCache($userKey,$donneesUtilisateur);
            }
            $this->setUtilisateur($donneesUtilisateur);
            return ['OK'];
        }catch (Exception $e){
            return gestionErreur($e,self::$namespaceUtilisateur.'_getAll');
        }
    }

    public function add(): array
    {
        try{
            $this->validate();
            if(count($this->errors)){
                return ['error' => $this->errors];
            }

            $query = "INSERT INTO contacts (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param("ssss", $this->nom, $this->prenom, $this->email, $this->telephone);
            $stmt->execute();

            $userId = $this->mysqli->insert_id;
            $this->id = $userId;
            $userKey = "user:$userId";

            $this->db->miseEnCache($userKey,$this->getDataCache());
            return ['OK'];
        }catch (Exception $e) {
            return gestionErreur($e,self::$namespaceUtilisateur.'_add');
        }
    }

    public function delete(): array
    {
        try {
            if(empty($this->id)){
                return ['error' => 'L\'utilisateur n\'exsite pas'];
            }

            $userKey = "user:$this->id";
            $query = "DELETE FROM contacts WHERE id = ?";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param("i", $this->id);
            $stmt->execute();

            $this->redis->del($userKey);
            return ['OK'];
        }catch (Exception $e){
            return gestionErreur($e,self::$namespaceUtilisateur.'_delete');
        }
    }

    public function put(): array
    {
        try {
            $this->validate();
            if(count($this->errors)){
                return ['error' => $this->errors];
            }

            $query = "SELECT id FROM contacts WHERE id = ?";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $query = "UPDATE contacts SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id = ?";
                $stmt = $this->mysqli->prepare($query);
                $stmt->bind_param("ssssi", $this->nom, $this->prenom, $this->email, $this->telephone, $this->id);
                $stmt->execute();

                $userId = "user:$this->id";
                $this->db->miseEnCache($userId,$this->getDataCache());
                return ['OK'];
            } else {
                return ['error' => 'L\'utilisateur n\'existe pas dans la base de données.'];
            }

        }catch (Exception $e){
            return gestionErreur($e,self::$namespaceUtilisateur.'_put');
        }
    }

    private function validate() {
        $this->errors = [];
        if(!validateNomEtPrenom($this->nom))
            $this->addErreurNom();

        if(!validateNomEtPrenom($this->prenom))
            $this->addErreurPrenom();

        if(!validateEmail($this->email) && !empty($this->email))
            $this->addErreurEmail();

        if(!validatePhone($this->telephone) && !empty($this->telephone))
            $this->addErreurPhone();
    }

    private  function getError(){
        return $this->errors;
    }

    private function addErreur($typeErreur, $messageErreur) {
        if (!isset($this->errors[$typeErreur])) {
            $this->errors[$typeErreur] = $messageErreur;
        }
    }

    private function addErreurNom() {
        $this->addErreur('NomInvalide', 'Le nom doit être compris entre 3 et 100 caractères, les caractères spéciaux ne sont pas acceptés.');
    }

    private function addErreurPrenom() {
        $this->addErreur('PrenomInvalide', 'Le nom doit être compris entre 3 et 100 caractères, les caractères spéciaux ne sont pas acceptés.');
    }

    private function addErreurEmail() {
        $this->addErreur('EmailInvalide', 'Veuillez donner un email valide');
    }

    private function addErreurPhone() {
        $this->addErreur('PhoneInvalide', 'Veuillez donner un téléphone valide');
    }

}