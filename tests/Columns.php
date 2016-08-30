<?php
use FForattini\Btrieve\Column\IdColumn;
use FForattini\Btrieve\Column\IntColumn;
use FForattini\Btrieve\Column\DateColumn;
use FForattini\Btrieve\Column\FloatColumn;
use FForattini\Btrieve\Column\StringColumn;

class ColumnsTests extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function evalIdColumn()
	{
		$column = new IdColumn('content_name');
		$this->assertEquals(is_null($column), false);
		$this->assertEquals($column->getTitle(), 'content_name');
		$this->assertEquals($column->getLength(), IdColumn::DEFAULT_LENGTH);

		unset($column);
		$column = new IdColumn('new_content_name', 10);
		$this->assertEquals($column->getTitle(), 'new_content_name');
		$this->assertEquals($column->getLength(), 10);

		foreach ([null, 1, 0.123, 'abc'] as $value) {
			$this->assertEquals(is_int(IdColumn::cast($value)), true);
		}
	}

	/**
	 * @test
	 */
	public function evalIntColumn()
	{
		$column = new IntColumn('content_name');
		$this->assertEquals(is_null($column), false);
		$this->assertEquals($column->getTitle(), 'content_name');
		$this->assertEquals($column->getLength(), IntColumn::DEFAULT_LENGTH);

		unset($column);
		$column = new IntColumn('new_content_name', 10);
		$this->assertEquals($column->getTitle(), 'new_content_name');
		$this->assertEquals($column->getLength(), 10);

		foreach ([null, 1, 0.123, 'abc'] as $value) {
			$this->assertEquals(is_int(IntColumn::cast($value)), true);
		}
	}

	/**
	 * @test
	 */
	public function evalDateColumn()
	{
		$column = new DateColumn('content_name');
		$this->assertEquals(is_null($column), false);
		$this->assertEquals($column->getTitle(), 'content_name');
		$this->assertEquals($column->getLength(), DateColumn::DEFAULT_LENGTH);

		unset($column);
		$column = new IntColumn('new_content_name', 10);
		$this->assertEquals($column->getTitle(), 'new_content_name');
		$this->assertEquals($column->getLength(), 10);

		foreach ([null, 1, 0.123, 'abc'] as $value) {
			$this->assertEquals(is_int(IntColumn::cast($value)), true);
		}
	}

	/**
	 * @test
	 */
	public function evalFloatColumn()
	{
		$column = new FloatColumn('content_name');
		$this->assertEquals(is_null($column), false);
		$this->assertEquals($column->getTitle(), 'content_name');
		$this->assertEquals($column->getLength(), FloatColumn::DEFAULT_LENGTH);

		unset($column);
		$column = new FloatColumn('new_content_name', 10);
		$this->assertEquals($column->getTitle(), 'new_content_name');
		$this->assertEquals($column->getLength(), 10);

		foreach ([null, 1, 0.123, 'abc'] as $value) {
			$this->assertEquals(is_float(FloatColumn::cast($value)), true);
		}
	}

	/**
	 * @test
	 */
	public function evalStringColumn()
	{
		$column = new StringColumn('content_name');
		$this->assertEquals(is_null($column), false);
		$this->assertEquals($column->getTitle(), 'content_name');
		$this->assertEquals($column->getLength(), StringColumn::DEFAULT_LENGTH);

		unset($column);
		$column = new StringColumn('new_content_name', 10);
		$this->assertEquals($column->getTitle(), 'new_content_name');
		$this->assertEquals($column->getLength(), 10);

		foreach ([null, 1, 0.123, 'abc'] as $value) {
			$this->assertEquals(is_string(StringColumn::cast($value)), true);
		}
	}
}