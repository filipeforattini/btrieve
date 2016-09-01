<?php

use FForattini\Btrieve\Persistence\ActiveRecord;

class BtrieveRecordTests extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function empty()
    {
        $ar = new ActiveRecord();
        $this->assertEquals(is_null($ar), false);
    }

    /**
     * @test
     */
    public function creatingFromColumnsAndData()
    {
        $ar = new ActiveRecord(['name', 'surname'], ['Filipe', 'Forattini']);
        $this->assertEquals($ar->attr('name'), 'Filipe');
    }

    /**
     * @test
     */
    public function creatingFromAttributes()
    {
        $ar = ActiveRecord::attributes([
            'name'    => 'Filipe',
            'surname' => 'Forattini',
        ]);
        $this->assertEquals($ar->attr('name'), 'Filipe');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function attributeDoesntExist()
    {
        $ar = new ActiveRecord();
        $ar->update('name', 'Filipe');
    }

    /**
     * @test
     */
    public function updatingAttribute()
    {
        $ar = new ActiveRecord(['count'], [0]);
        $ar->update('count', 1);
        $this->assertEquals($ar->attr('count'), 1);
        $this->assertEquals($ar->getData()[0], 1);

        $ar->set('count', 2);
        $this->assertEquals($ar->attr('count'), 2);
    }

    /**
     * @test
     */
    public function setingAttributes()
    {
        $ar = new ActiveRecord();
        $ar->set('count', 1);
        $this->assertEquals($ar->attr('count'), 1);
    }
}
