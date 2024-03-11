<?php

namespace App\Model;

use JsonSerializable;
use App\Repository\AdresseRepository;
use stdClass;

class Utilisateur extends Model implements JsonSerializable
{
    private int $id = 0;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $telephone;
    private Adresse|null $adresse;
    private AdresseRepository $repoAdresse;
    private array $errors = [];

    static string $namespace = 'utilisateur';

    public function __construct(String $nom = '',String $prenom = '',String $email = '',String $telephone = '')
    {
        $this->nom = trim($nom);
        $this->prenom = trim($prenom);
        $this->email = trim($email);
        $this->telephone = trim($telephone);
        $this->adresse = new Adresse();
        $this->repoAdresse = new AdresseRepository();
    }

    public function setId(?int $id) :void
    {
        if($id == null) $id = 0;
        $this->id = $id;
    }

    public function setNom(?string $nom)
    {
        if($nom == null) $nom = '';
        $this->nom = $nom;
    }

    public function setPrenom(?string $prenom)
    {
        if($prenom == null) $prenom = '';
        $this->prenom = $prenom;
    }

    public function setEmail(?string $email)
    {
        if($email == null) $email = '';
        $this->email = $email;
    }

    public function setTelephone(?string $telephone)
    {
        if($telephone == null) $telephone = '';
        $this->telephone = $telephone;
    }

    public function setAdresse(?Adresse $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function setAdresseById(?int $adresse_id): void
    {
        $this->adresse = $this->repoAdresse->getById($adresse_id);
    }

    public function setUtilisateur(array $data) :void
    {
        foreach ($data as $key => $value) {
            if($key == 'id') $this->id = $value;
            if($key == 'nom') $this->nom = $value;
            if($key == 'prenom') $this->prenom = $value;
            if($key == 'email') $this->email = $value;
            if($key == 'telephone') $this->telephone = $value;
            if($key == 'adresse_id') {
                if($value != null){
                    $this->adresse = $this->repoAdresse->getById($value);
                } else {
                    $this->adresse = new Adresse();
                }
            }
        }
    }

    public function getId() :int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function  getEmail(): string
    {
        return $this->email;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getAdresse(): Adresse|null
    {
        return $this->adresse;
    }

    public function getDataCache(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'telephone' => $this->telephone,
            'adresse_id' => $this->adresse->getId(),
        ];
    }

    public function validate() :void
    {
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

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->getDataCache();
    }

    public static function fromStdClass(stdClass $stdClass): Utilisateur {
        $utilisateur = new Utilisateur();
        $utilisateur->setId($stdClass->id);
        $utilisateur->setNom($stdClass->nom);
        $utilisateur->setPrenom($stdClass->prenom);
        $utilisateur->setEmail($stdClass->email);
        $utilisateur->setTelephone($stdClass->telephone);
        $utilisateur->setAdresseById($stdClass->adresse_id);

        return $utilisateur;
    }

    public  function getErrors(): array
    {
        return $this->errors;
    }

    private function addErreur($typeErreur, $messageErreur) :void
    {
        if (!isset($this->errors[$typeErreur])) {
            $this->errors[$typeErreur] = $messageErreur;
        }
    }

    private function addErreurNom() :void
    {
        $this->addErreur('NomInvalide', 'Le nom doit être compris entre 3 et 100 caractères, les caractères spéciaux ne sont pas acceptés.');
    }

    private function addErreurPrenom() :void
    {
        $this->addErreur('PrenomInvalide', 'Le nom doit être compris entre 3 et 100 caractères, les caractères spéciaux ne sont pas acceptés.');
    }

    private function addErreurEmail() :void
    {
        $this->addErreur('EmailInvalide', 'Veuillez donner un email valide');
    }

    private function addErreurPhone() :void
    {
        $this->addErreur('PhoneInvalide', 'Veuillez donner un téléphone valide');
    }

}
