<?php
namespace Tests\Model;

use App\Model\Adresse;
use PHPUnit\Framework\TestCase;

class AdresseTest extends TestCase
{
    /**
     * @covers \App\Model\Adresse::__construct
     * @covers \App\Model\Adresse::validate
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
     * @covers \App\Model\Adresse::__construct
     * @covers \App\Model\Adresse::validate
     */
    public function testValidateInvalideDepartement()
    {
        $error = ['DepartementInvalide' => 'Le département sélectionnée n\'existe pas'];
        $adresse = new Adresse('	aisnne');
        $adresse->setContact(1);
        $adresse->validate();
        $this->assertSame($adresse->getErrors(),$error);
    }

    /**
     * @covers \App\Model\Adresse::__construct
     * @covers \App\Model\Adresse::validate
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
     * @covers \App\Model\Adresse::__construct
     * @covers \App\Model\Adresse::validate
     */
    public function testValidateValideCommune()
    {
        $error = [];
        $adresse = new Adresse('', 'Montséret');
        $adresse->setContact(1);
        $adresse->validate();
        $this->assertSame($adresse->getErrors(),$error);
    }

    /**
     * @covers \App\Model\Adresse::__construct
     * @covers \App\Model\Adresse::validate
     */
    public function testValidateInvalideCommune()
    {
        $error = ['CommuneInvalide' => 'La commune sélectionnée n\'existe pas'];
        $adresse = new Adresse('','	Montsérét');
        $adresse->setContact(1);
        $adresse->validate();
        $this->assertSame($adresse->getErrors(),$error);
    }

    /**
     * @covers \App\Model\Adresse::__construct
     * @covers \App\Model\Adresse::validate
     */
    public function testValidateValideCommuneInDepartement()
    {
        $error = [];
        $adresse = new Adresse('Jura', 'Arbois');
        $adresse->setContact(1);
        $adresse->validate();
        $this->assertSame($adresse->getErrors(),$error);
    }

    /**
     * @covers \App\Model\Adresse::__construct
     * @covers \App\Model\Adresse::validate
     */
    public function testValidateInvalideCommuneInDepartement()
    {
        $error = ['CommuneNotInDepartement' => 'La commune sélectionnée n\'appartient au departement Doubs'];
        $adresse = new Adresse('Doubs', 'Arbois');
        $adresse->setContact(1);
        $adresse->validate();
        $this->assertSame($adresse->getErrors(),$error);
    }



}
