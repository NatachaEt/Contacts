<?php

include_once 'src/Service/gestionErreur.php';
include_once 'src/Service/validateData.php';
include_once 'src/Model/Bdd.php';
include_once 'src/Model/Utilisateur.php';
include_once 'src/Repository/ContactRepository.php';
class Adresse
{
    private int $id = 0;
    private string $departement;
    private string $commune;
    private string $numero;
    private string $voie;
    private Utilisateur $contact;
    private ContactRepository $repoContact;

    static string $namespace = 'adresse';

    private array $errors = [];

    public function __construct(string $departement = '',string $commune = '',string $numero = '',string $voie = '',Utilisateur $contact = null) {
        $this->departement = $departement;
        $this->commune = $commune;
        $this->numero = $numero;
        $this->voie = $voie;
        $this->contact = $contact;
        $this->repoContact = new ContactRepository();
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

    public function getContact(): Utilisateur
    {
        return $this->contact;
    }

    public function toString(): string
    {
        return $this->departement . ' ' . $this->commune . ' ' .$this->numero. ' ' . $this->voie;
    }

    public function setAdresse(array $data) :void
    {
        foreach ($data as $key => $value) {
            if($key == 'id') $this->id = $value;
            if($key == 'departement') $this->departement = $value;
            if($key == 'commune') $this->commune = $value;
            if($key == 'numero') $this->numero = $value;
            if($key == 'voie') $this->voie = $value;
            if($key == 'contact_id') $this->contact = $this->repoContact->getById($value);
        }
    }

    public function setErrors(array $errors) :void
    {
        foreach ($errors as $key => $value) {
            $this->errors[$key] = $value;
        }
    }

    public function getErrors(): array
    {
        $result = $this->errors;
        $this->errors = [];

        return $result;
    }

    public function getDataCache(): array
    {
        return [
            'id' => $this->id,
            'departement' => $this->departement,
            'commune' => $this->commune,
            'voie' => $this->voie,
            'numero' => $this->numero,
            'contact_id' => $this->contact->getId(),
        ];
    }

}
