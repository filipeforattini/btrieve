<?php

use FForattini\Btrieve\Column\ColumnRule;
use FForattini\Btrieve\Rule\SkipRule;

class RulesTests extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createRuleToSkipBytes()
    {
        $rule = new SkipRule(4);
        $this->assertEquals(is_null($rule), false);
        $this->assertEquals($rule->getLength(), 4);
    }

    /**
     * @test
     */
    public function createRuleWithContent()
    {
        $rule = new ColumnRule('content_name', 'string', 100);
        $this->assertEquals(is_null($rule), false);
        $this->assertEquals($rule->getTitle(), 'content_name');
        $this->assertEquals($rule->getType(), 'string');
        $this->assertEquals($rule->getLength(), 100);
    }
}
