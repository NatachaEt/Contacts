<?php
namespace Tests\Model;

use App\Model\Utilisateur;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    /**
     * @covers \App\Model\Utilisateur::__construct
     * @covers \App\Model\Utilisateur::getNom
     */
    public function testVoidConstructNom()
    {
        $utilisateur = new Utilisateur('Alex');
        $this->assertSame('Alex',$utilisateur->getNom());
    }

    /**
     * @covers \App\Model\Utilisateur::__construct
     * @covers \App\Model\Utilisateur::getPrenom
     */
    public function testVoidConstructPrenom()
    {
        $utilisateur = new Utilisateur('','Durand');
        $this->assertSame('Durand',$utilisateur->getPrenom());
    }

    /**
     * @covers \App\Model\Utilisateur::__construct
     * @covers \App\Model\Utilisateur::getEmail
     */
    public function testVoidConstructEmail()
    {
        $utilisateur = new Utilisateur('','','email');
        $this->assertSame('email',$utilisateur->getEmail());
    }


    /**
     * @covers \App\Model\Utilisateur::__construct
     */
    public function testVoidConstruct()
    {
        $utilisateur = new Utilisateur();
        $this->assertEmpty($utilisateur->getId(),'l\'id n\'est pas vide');
        $this->assertEmpty($utilisateur->getNom(),'le nom n\'est pas vide');
        $this->assertEmpty($utilisateur->getPrenom(),'le prenom n\'est pas vide');
        $this->assertEmpty($utilisateur->getEmail(), 'l\'email prenom n\'est pas vide');
        $this->assertEmpty($utilisateur->getTelephone(),'le telephone n\'est pas vide');
    }

    /**
     * @covers \App\Model\Utilisateur::__construct
     * @covers \App\Model\Utilisateur::validate
     * @covers \App\Model\Utilisateur::getErrors
     */
    public function testValidateUtilisateurEmpty()
    {
        $error = [
            "NomInvalide" => "Le nom doit être compris entre 3 et 100 caractères, les caractères spéciaux ne sont pas acceptés.",
            "PrenomInvalide" => "Le nom doit être compris entre 3 et 100 caractères, les caractères spéciaux ne sont pas acceptés.",
            ];
        $utilisateur = new Utilisateur();
        $utilisateur->validate();
        $this->assertSame($utilisateur->getErrors(),$error);
    }

    /**
     * @covers \App\Model\Utilisateur::__construct
     * @covers \App\Model\Utilisateur::validate
     * @covers \App\Model\Utilisateur::getErrors
     */
    public function testValidateUtilisateurWithInvalidEmail()
    {
        $error = [
            "EmailInvalide" => "Veuillez donner un email valide",
        ];
        $utilisateur = new Utilisateur('test','test','jesuisinvalide@oui');
        $utilisateur->validate();
        $this->assertSame($utilisateur->getErrors(),$error);
    }

    /**
     * @covers \App\Model\Utilisateur::__construct
     * @covers \App\Model\Utilisateur::validate
     * @covers \App\Model\Utilisateur::getErrors
     */
    public function testValidateUtilisateurWithInvalidTelephone()
    {
        $error = [
            "PhoneInvalide" => "Veuillez donner un téléphone valide",
        ];
        $utilisateur = new Utilisateur('test','test','bonjour@mail.com','079065');
        $utilisateur->validate();
        $this->assertSame($utilisateur->getErrors(),$error);
    }

    /**
     * @covers \App\Model\Utilisateur::__construct
     * @covers \App\Model\Utilisateur::validate
     * @covers \App\Model\Utilisateur::getErrors
     */
    public function testValidateUtilisateurWithValidTelephoneFR()
    {
        $error = [];
        $utilisateur = new Utilisateur('test','test','bonjour@mail.com','+33726550774');
        $utilisateur->validate();
        $this->assertSame($utilisateur->getErrors(),$error);
    }

}
