<?php

use FForattini\Btrieve\Btrieve;

class SampleTests extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function btrieveLinkingToTheSample()
    {
        $btrieve = Btrieve::load(__DIR__.'/sample.txt');
        $this->assertEquals(is_null($btrieve), false);

        return $btrieve;
    }

    /**
     * @test
     * @depends btrieveLinkingToTheSample
     *
     * @param Btrieve $btrieve
     */
    public function addColumns($btrieve)
    {
        $btrieve->id('CodConvenio', 2)
            ->string('NomeConvenio', 29)
            ->string('StatusConvenio', 1)
            ->int('ValorPagar', 2)
            ->setVariableColumnName('Extra');

        $this->assertEquals($btrieve->countRules(), 4);

        return $btrieve;
    }

    /**
     * @test
     * @depends addColumns
     *
     * @param Btrieve $btrieve
     */
    public function getFirstElement($btrieve)
    {
        $btrieve->next();
        $btrieve->next();
        var_dump($btrieve->elem(1));
    }
}
