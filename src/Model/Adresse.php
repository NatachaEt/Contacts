<?php

namespace App\Model;


use App\Repository\ContactRepository;

include_once 'src/Service/validateData.php';

class Adresse
{
    private int $id = 0;
    private string $departement;
    private string $commune;
    private ?int $contact_id;

    static string $namespace = 'adresse';

    private array $errors = [];

    public function __construct(string $departement = '',string $commune = '',int $contact = null) {
        $this->setDepartement($departement);
        $this->setCommune($commune);
        $this->contact_id = $contact;
    }

    public function set(array $data) :void
    {
        foreach ($data as $key => $value) {
            if($key == 'id') $this->id = $value;
            if($key == 'departement') $this->setDepartement($value);
        }
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setDepartement(?string $departement):void
    {
        $this->departement = htmlspecialchars(trim($departement));
    }

    public function setCommune(?string $commune):void
    {
        $this->commune = htmlspecialchars(trim($commune));
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

    public function getContact(): int
    {
        return $this->contact_id;
    }

    public function validate() :void
    {
        $repoContact = new ContactRepository();
        $this->errors = [];
        if(!empty($this->departement) && !validateDepartement($this->departement))
            $this->addErreurDepartement();
        if(!empty($this->commune) && !validateCommune($this->commune))
            $this->addErreurCommune();
        if(!empty($this->commune) && !empty($this->departement) && !validateCommuneInDepartement($this->commune,$this->departement))
            $this->addErreurCommunNotInDepartement();
        if($this->contact_id == null || $repoContact->getById($this->contact_id) == null)
            $this->addErreur('ContactNotExist', 'L\'utilisateur n\'existe pas' );
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

    private function  addErreurCommunNotInDepartement():void
    {
        $this->addErreur('CommuneNotInDepartement', 'La commune sélectionnée n\'appartient au departement '.$this->departement);
    }

    public function toString(): string
    {
        return $this->departement . ' ' . $this->commune;
    }

    public function setAdresse(array $data) :void
    {
        foreach ($data as $key => $value) {
            if($key == 'id') $this->id = trim($value);
            if($key == 'departement') $this->departement = trim($value);
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
            'contact_id' => $this->contact_id,
        ];
    }

}
