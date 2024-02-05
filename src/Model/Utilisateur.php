<?php

include_once 'src/Service/gestionErreur.php';
include_once 'src/Service/validateData.php';
include_once 'src/Model/Bdd.php';

class Utilisateur
{
    private string $id = '';
    private string $nom;
    private string $prenom;
    private string $email;
    private string $telephone;
    private array $errors = [];
    private $redis;
    static string $namespaceUtilisateur = 'utilisateur';
    public function __construct(String $nom = '',String $prenom = '',String $email = '',String $telephone = '')
    {
        $this->nom = trim($nom);
        $this->prenom = trim($prenom);
        $this->email = trim($email);
        $this->telephone = trim($telephone);

        $bd = new Bdd();
        $this->redis = $bd->getRedis();
    }

    public function setId(string $id) {
        $this->id = $id;
    }

    public function setUtilisateur(array $data) {
        foreach ($data as $key => $value) {
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

    public function getAll() {
        try {
            $keyUtilisateurs = $this->redis->keys('user:*');
            $donneesUtilisateurs = [];

            foreach ($keyUtilisateurs as $key) {
                $donneesUtilisateur = $this->redis->hgetall($key);
                $donneesUtilisateurs[$key] = $donneesUtilisateur;
            }
            return $donneesUtilisateurs;
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
            foreach ($donneesUtilisateur as $key => $value) {
                if($key == 'nom') $this->nom = $value;
                if($key == 'prenom') $this->prenom = $value;
                if($key == 'email') $this->email = $value;
                if($key == 'telephone') $this->telephone = $value;
            }
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
            //TODO vérifier si l'utilisateur existe déjà
            $userId = $this->redis->incr('next_user_id');
            $this->id = $userId;
            $userKey = "user:$userId";

            $userData = [
                'id' => $userId,
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'email' => $this->email,
                'telephone' => $this->telephone,
            ];

            $this->redis->hMset($userKey, $userData);
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

            $userId = "user:$this->id";

            $userData = [
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'email' => $this->email,
                'telephone' => $this->telephone,
            ];

            $this->redis->hMset($userId, $userData);
            return ['OK'];
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