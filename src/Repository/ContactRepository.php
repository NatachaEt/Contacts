<?php

class ContactRepository {

    private $db;
    private $mysqli;
    private $redis;

    public function __construct()
    {
        $this->db = new Bdd();
        $this->mysqli = $this->db->getMysqli();
        $this->redis = $this->db->getRedis();
    }

    public function getAll() :array
    {
        try {
            $allUsersKey = "getAll_contacts";
            $donneesContacts = $this->redis->get($allUsersKey);

            if (empty($donneesContacts)) {
                $query = "SELECT * FROM contacts";
                $result = $this->mysqli->query($query);
                $donneesContacts = [];

                while ($row = $result->fetch_assoc()) {
                    $contact = new Utilisateur();
                    $contact->setUtilisateur($row);
                    $donneesContacts[] = $contact;
                }
                $this->redis->set($allUsersKey, json_encode($donneesContacts));
            } else {
                $donneesContacts = json_decode($donneesContacts);
            }

            return $donneesContacts;
        }catch (Exception $e) {
            return gestionErreur($e,Utilisateur::$namespaceUtilisateur.'_getAll');
        }
    }

    public function getById (int $id)
    {
        try {
            $contact = new Utilisateur();

            if(empty($id)){
                return $contact;
            }

            $userKey = "user:$id";
            $donneesUtilisateur = $this->redis->hgetall($userKey);
            if (empty($donneesUtilisateur)) {
                $query = "SELECT * FROM contacts WHERE id = ?";
                $stmt = $this->mysqli->prepare($query);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $donneesUtilisateur = $result->fetch_assoc();
                if($donneesUtilisateur == null) return $contact;

                $contact->setUtilisateur($donneesUtilisateur);

                $this->db->miseEnCache($userKey,$contact->getDataCache());
            }else{
                $contact->setUtilisateur($donneesUtilisateur);
            }

            return $contact;
        }catch (Exception $e){
            return gestionErreur($e,Utilisateur::$namespaceUtilisateur.'_getAll');
        }
    }

    public function add(Utilisateur $utilisateur) :void
    {
        try{
            $utilisateur->validate();
            if(count($utilisateur->getErrors())){
                return;
            }

            $query = "INSERT INTO contacts (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param("ssss", $this->nom, $this->prenom, $this->email, $this->telephone);
            $stmt->execute();

            $userId = $this->mysqli->insert_id;
            $utilisateur->setId($userId);
            $userKey = "user:$userId";

            $this->db->miseEnCache($userKey,$utilisateur->getDataCache());
        }catch (Exception $e) {
            gestionErreur($e,Utilisateur::$namespaceUtilisateur.'_add');
        }
    }

    public function delete(int $id): array
    {
        try {
            if(empty($id)){
                return ['error' => 'L\'utilisateur n\'exsite pas'];
            }

            $userKey = "user:$id";
            $query = "DELETE FROM contacts WHERE id = ?";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $this->redis->del($userKey);
            return ['OK'];
        }catch (Exception $e){
            return gestionErreur($e,Utilisateur::$namespaceUtilisateur.'_delete');
        }
    }

    public function put(Utilisateur $contact) :void
    {
        try {
            $contact->validate();
            if(count($contact->getErrors())){
                return;
            }

            $id = $contact->getId();
            if(empty($id)) {
                $this->add($contact);
                return;
            }
            $nom = $contact->getNom();
            $prenom = $contact->getPrenom();
            $email = $contact->getEmail();
            $telephone = $contact->getTelephone();

            $query = "UPDATE contacts SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id = ?";
            $stmt = $this->mysqli->prepare($query);

            $stmt->bind_param("ssssi", $nom, $prenom, $email, $telephone, $id);
            $stmt->execute();

            $userId = "user:$id";
            $this->db->miseEnCache($userId,$contact->getDataCache());


        }catch (Exception $e){
            gestionErreur($e,Utilisateur::$namespaceUtilisateur.'_put');
        }
    }
}