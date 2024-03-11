<?php
namespace Tests\Model;

use App\Model\Adresse;
use PHPUnit\Framework\TestCase;

class AdresseTest extends TestCase
{
    /**
     * @covers \App\Model\Utilisateur::__construct
     * @covers \App\Model\Utilisateur::getNom
     */
    public function testValidateValideDepartement()
    {
        $error = [];
        $adresse = new Adresse('	aisne');
        $adresse->setContact(1);
        $adresse->validate();
        $this->assertSame($adresse->getErrors(),$error);
    }

    /**
     * @covers \App\Model\Utilisateur::__construct
     * @covers \App\Model\Utilisateur::getNom
     */
    public function testValidateValideDepartementWithMajuscule()
    {
        $error = [];
        $adresse = new Adresse('Aisne');
        $adresse->setContact(1);
        $adresse->validate();
        $this->assertSame($adresse->getErrors(),$error);
    }

    /**
     * @covers \App\Model\Utilisateur::__construct
     * @covers \App\Model\Utilisateur::getNom
     */
    public function testValidateValideCommune()
    {
        $error = [];
        $adresse = new Adresse('', 'Montséret');
        $adresse->setContact(1);
        $adresse->validate();
        $this->assertSame($adresse->getErrors(),$error);
    }



}
