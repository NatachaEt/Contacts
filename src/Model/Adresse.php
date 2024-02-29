<?php

include_once 'src/Service/gestionErreur.php';
include_once 'src/Service/validateData.php';
include_once 'src/Model/Bdd.php';
include_once 'src/Model/Utilisateur.php';
class Adresse
{
    private int $id = 0;
    private string $departement;
    private string $commune;
    private ?int $contact_id;

    static string $namespace = 'adresse';

    private array $errors = [];

    public function __construct(string $departement = '',string $commune = '',string $numero = '',string $voie = '',int $contact = null) {
        $this->departement = $departement;
        $this->commune = $commune;
        $this->numero = $numero;
        $this->voie = $voie;
        $this->contact_id = $contact;
    }

    public function set(array $data) :void
    {
        foreach ($data as $key => $value) {
            if($key == 'id') $this->id = $value;
            if($key == 'departement') $this->departement = $value;
        }
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setContact(?int $id): void
    {
        $this->contact_id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDepartement(): string
    {
        return $this->departement;
    }

    public function getCommune(): string
    {
        return $this->commune;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function getVoie(): string
    {
        return $this->voie;
    }

    public function getContact(): int
    {
        return $this->contact_id;
    }

    public function validate() :void
    {
        $this->errors = [];
        if(!validateDepartement($this->departement) && !empty($this->departement))
            $this->addErreurDepartement();
        if(!validateCommune($this->commune) && !empty($this->commune))
            $this->addErreurCommune();
        if($this->contact_id == null)
            $this->addErreur('Contact id null', 'L\'utilisateur n\'existe pas' );
    }

    private function addErreur($typeErreur, $messageErreur) :void
    {
        if (!isset($this->errors[$typeErreur])) {
            $this->errors[$typeErreur] = $messageErreur;
        }
    }

    private function addErreurDepartement() :void
    {
        $this->addErreur('DepartementInvalide', 'Le département sélectionnée n\'existe pas');
    }

    private function addErreurCommune() :void
    {
        $this->addErreur('CommuneInvalide', 'La commune sélectionnée n\'existe pas');
    }

    public function toString(): string
    {
        return $this->departement . ' ' . $this->commune;
    }

    public function setAdresse(array $data) :void
    {
        foreach ($data as $key => $value) {
            if($key == 'id') $this->id = $value;
            if($key == 'departement') $this->departement = $value;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getDataCache(): array
    {
        return [
            'id' => $this->id,
            'departement' => $this->departement,
            'commune' => $this->commune,
            'voie' => $this->voie,
            'numero' => $this->numero,
            'contact_id' => $this->contact_id,
        ];
    }

}
