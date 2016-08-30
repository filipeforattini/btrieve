<?php
use FForattini\Btrieve\Btrieve;
use FForattini\Btrieve\Rule\SkipRule;
use FForattini\Btrieve\Column\ColumnRule;
use FForattini\Btrieve\Column\IdColumn;
use FForattini\Btrieve\Column\IntColumn;
use FForattini\Btrieve\Column\DateColumn;
use FForattini\Btrieve\Column\FloatColumn;
use FForattini\Btrieve\Column\StringColumn;

class BtrieveTests extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
     * @expectedException InvalidArgumentException
     */
	public function btrieveReturnsExceptionWhenItCantFindTheFile()
	{
		$btrieve = Btrieve::load(__DIR__.'/invalid-file');
	}

	/**
	 * @test
	 */
	public function addSkipRuleToBtrieve()
	{
		$btrieve = new Btrieve();
		$this->assertEquals($btrieve->countRules() == 0, true);
		$btrieve->addRule(new SkipRule(4));
		$this->assertEquals($btrieve->countRules() > 0, true);
	}

	/**
	 * @test
	 */
	public function addColumnRuleToBtrieve()
	{
		$btrieve = new Btrieve();
		$this->assertEquals($btrieve->countRules() == 0, true);
		$btrieve->addRule(new ColumnRule('data',ColumnRule::TYPE_INT, 2));
		$this->assertEquals(count($btrieve->getRules()) > 0, true);
	}

	/**
	 * @test
	 */
	public function addingColumnsToBtrieve()
	{
		$btrieve = new Btrieve();

		$this->assertEquals($btrieve->countRules(), 0);
		$btrieve->addRules([
			new IdColumn('data-id'),
			new IntColumn('data-int'),
			new DateColumn('data-date'),
			new FloatColumn('data-float'),
			new StringColumn('data-string')
		]);
		$this->assertEquals($btrieve->countRules(), 5);

		$this->assertEquals($btrieve->clearRules()->countRules(), 0);
		$btrieve->addColumn(new IdColumn('data-id'));
		$btrieve->addColumn(new IntColumn('data-int'));
		$btrieve->addColumn(new DateColumn('data-date'));
		$btrieve->addColumn(new FloatColumn('data-float'));
		$btrieve->addColumn(new StringColumn('data-string'));
		$this->assertEquals($btrieve->countRules(), 5);

		$this->assertEquals($btrieve->clearRules()->countRules(), 0);
		$btrieve->id('data-id')
			->int('data-int')
			->date('data-date')
			->float('data-float')
			->string('data-string')
			->skip(1);
		$this->assertEquals($btrieve->countRules(), 6);

		return $btrieve;
	}

	/**
	 * @test
	 * @depends addingColumnsToBtrieve
	 */
	public function getColumnsNames($btrieve)
	{
		$columns = $btrieve->getColumns();
		$this->assertEquals(count($columns), 5);

		foreach (['id','int','date','float','string'] as $column) {
			$this->assertEquals(in_array('data-'.$column, $columns), true);
		}
	}
}