<?php

namespace App\Tests\Brouillon;

use App\brouillon\Calculateur;
use PHPUnit\Framework\TestCase;



class CalculateurTest extends TestCase {

    function testAdditionNombresPositifs() {
        $calcul = new Calculateur;
        $rs = $calcul->addition(5,10);
        $this->assertEquals(15, $rs);
    }

    function testSoustractionNombresPositifs() {
        $calcul = new Calculateur;
        $rs = $calcul->soustraction(10,2);
        $this->assertEquals(8, $rs);
    }
}