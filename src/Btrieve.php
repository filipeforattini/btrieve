<?php
namespace FForattini\Btrieve;

use FForattini\Btrieve\Bin;
use FForattini\Btrieve\Hex;
use FForattini\Btrieve\Str;
use InvalidArgumentException;
use FForattini\Btrieve\Rule\SkipRule;
use FForattini\Btrieve\Column\IdColumn;
use FForattini\Btrieve\Column\IntColumn;
use FForattini\Btrieve\Column\ColumnRule;
use FForattini\Btrieve\Column\DateColumn;
use FForattini\Btrieve\Column\FloatColumn;
use FForattini\Btrieve\Rule\RuleInterface;
use FForattini\Btrieve\Column\StringColumn;
use FForattini\Btrieve\Persistence\RowsPool;
use FForattini\Btrieve\Persistence\DebugPool;
use FForattini\Btrieve\Column\ColumnInterface;
use FForattini\Btrieve\Persistence\RecordsPool;
use FForattini\Btrieve\Persistence\BtrieveRecord;
use FForattini\Btrieve\Persistence\DatasetInterface;

class Btrieve
{
	protected $input;
	protected $input_stats;
	protected $rules;
	protected $pointer;
	protected $columns;
	protected $dirty;
	protected $variable_column_name;

	public $pools;
	const POOL_MAIN 	= 0;
	const POOL_DEBUG 	= 1;
	const POOL_ROWS 	= 2;

	/**
	 * Creates a Btrieve parser from a file.
	 * @param  string $file Full path
	 * @return Btrieve
	 */
	public static function load($file)
	{
		return (new self)->setinput(realpath($file));
	}

	/**
	 * Constructs a Btrieve parser
	 */
	public function __construct()
	{
		$this->setRules([]);

		$this->registerPool(new RecordsPool, self::POOL_MAIN);
		$this->registerPool(new DebugPool, self::POOL_DEBUG);
		$this->registerPool(new RowsPool, self::POOL_ROWS);
		$this->initPools();

		$this->dirty = false;

		$this->setVariableColumnName('attach');
	}

	/**
	 * Set input Btrieve file.
	 * @param string $file Real path
	 * @return Btrieve
	 */
	public function setInput($file)
	{
		if(!file_exists($file)) {
			throw new InvalidArgumentException('File not found.');
		}
		$this->input = $file;
		return $this->begin();
	}

	/**
	 * Get input Btrieve file.
	 * @return string Real path
	 */
	public function getInput()
	{
		return $this->input;
	}

	/**
	 * Set rules for the Btrieve parser
	 * @param array $rules
	 * @return Btrieve
	 */
	public function setRules($rules)
	{
		if(!is_array($rules)) {
			$rules = [$rules];
		}

		$this->rules = $rules;
		$this->dirty = true;
		return $this;
	}

	/**
	 * Get rules from the Btrieve parser
	 * @return array
	 */
	public function getRules()
	{
		return $this->rules;
	}

	/**
	 * Count the number of rules already added.
	 * @return int Number of rules.
	 */
	public function countRules()
	{
		return count($this->getRules());
	}

	/**
	 * Add a new Rule
	 * @param RuleInterface $rule
	 * @return Btrieve
	 */
	public function addRule(RuleInterface $rule)
	{
		$this->rules[] = $rule;
		$this->dirty = true;
		return $this;
	}

	/**
	 * Add an array of Rules.
	 * @param array $new_rules
	 * @return Btrieve
	 */
	public function addRules($new_rules)
	{
		if(!is_array($new_rules)) {
			throw new InvalidArgumentException('Parameter must be an array of Rules');
		}

		foreach ($new_rules as $new_rule) {
			$this->addRule($new_rule);
		}

		return $this;
	}

	/**
	 * Romove all rules.
	 * @return Btrieve
	 */
	public function clearRules()
	{
		foreach($this->getRules() as $rule) {
			unset($rule);
		}
		unset($this->rules);
		$this->setRules([]);
		return $this;
	}

	/**
	 * Adds a Skip to the rules.
	 * @param  string $title
	 * @param  int $size
	 * @return Btrieve
	 */
	public function skip($size = null)
	{
		if(is_null($size)) {
			$this->addRule(new SkipRule());
		} else {
			$this->addRule(new SkipRule($size));
		}
		return $this;
	}

	/**
	 * Adds a column to the rules.
	 * @param ColumnInterface $column
	 * @return Btrieve
	 */
	public function addColumn(ColumnInterface $column)
	{
		$this->addrule($column);
		return $this;
	}

	/**
	 * Adds a IdColumn to the rules.
	 * @param  string $title
	 * @param  int $size
	 * @return Btrieve
	 */
	public function id($title, $size = null)
	{
		if(is_null($size)) {
			$this->addColumn(new IdColumn($title));
		} else {
			$this->addColumn(new IdColumn($title, $size));
		}
		return $this;
	}

	/**
	 * Adds a IntColumn to the rules.
	 * @param  string $title
	 * @param  int $size
	 * @return Btrieve
	 */
	public function int($title, $size = null)
	{
		if(is_null($size)) {
			$this->addColumn(new IntColumn($title));
		} else {
			$this->addColumn(new IntColumn($title, $size));
		}
		return $this;
	}

	/**
	 * Adds a DateColumn to the rules.
	 * @param  string $title
	 * @param  int $size
	 * @return Btrieve
	 */
	public function date($title, $size = null)
	{
		if(is_null($size)) {
			$this->addColumn(new DateColumn($title));
		} else {
			$this->addColumn(new DateColumn($title, $size));
		}
		return $this;
	}

	/**
	 * Adds a FloatColumn to the rules.
	 * @param  string $title
	 * @param  int $size
	 * @return Btrieve
	 */
	public function float($title, $size = null)
	{
		if(is_null($size)) {
			$this->addColumn(new FloatColumn($title));
		} else {
			$this->addColumn(new FloatColumn($title, $size));
		}
		return $this;
	}

	/**
	 * Adds a StringColumn to the rules.
	 * @param  string $title
	 * @param  int $size
	 * @return Btrieve
	 */
	public function string($title, $size = null)
	{
		if(is_null($size)) {
			$this->addColumn(new StringColumn($title));
		} else {
			$this->addColumn(new StringColumn($title, $size));
		}
		return $this;
	}

	/**
	 * Sets a name for the variable column.
	 * @param string $name
	 */
	public function setVariableColumnName($name)
	{
		$this->variable_column_name = $name;
		return $this;
	}

	/**
	 * Sets a name for the variable column.
	 * @param string $name
	 */
	public function getVariableColumnName()
	{
		return $this->variable_column_name;
	}

	/**
	 * Read all the rules and returns
	 * @return void
	 */
	public function setColumns()
	{
		$columns = array_filter($this->getRules(), function($rule) {
			if($rule instanceof ColumnInterface) {
				return $rule;
			}
		});

		$columns = array_map(function(ColumnInterface $rule) {
			return $rule->getTitle();
		}, $columns);

		$this->columns = $columns;
	}

	/**
	 * Returns an array of columns names.
	 * @return array
	 */
	public function getColumns()
	{
		if($this->dirty) {
			$this->setColumns();
			$this->dirty = false;
		}

		return $this->columns;
	}

	/**
	 * Get current pointer over Betrieve's input file.
	 * @return resource
	 */
	public function getPointer()
	{
		return $this->pointer;
	}

	/**
	 * Creates a pointer at the begin of input Btrieve file.
	 * @return Btrieve
	 */
	public function begin()
	{
		$this->pointer = fopen($this->input,'rb');
		$this->input_stats = fstat($this->getPointer());

		if(is_null($this->getPointer())) {
			throw new Exception('Couldnt create the pointer at input file.');
		}

		return $this;
	}

	/**
	 * Returns Btrieve input file length
	 * @return int
	 */
	public function inputLength()
	{
		return $this->input_stats['size'];
	}

	/**
	 * Register a new pool of elements;
	 * @param  DatasetInterface $pool
	 * @param  int $key
	 * @return Btrieve
	 */
	public function registerPool(DatasetInterface $pool, $key)
	{
		$this->pools[$key] = $pool;
		return $this;
	}

	/**
	 * Verify if Pool was registered.
	 * @param  int  $index
	 * @return boolean
	 */
	public function hasPool($index)
	{
		if(isset($this->pools[$index])) {
			return true;
		}
		return false;
	}

	/**
	 * Get targered pool by index.
	 * @param  int $index
	 * @return DatasetInterface
	 */
	public function getPool($key)
	{
		if($this->hasPool($key)) {
			return $this->pools[$key];
		}
		throw new InvalidArgumentException('Pool with key \''.$key.'\' was not registered.');
	}

	/**
	 * Get array of registered pools.
	 * @return array
	 */
	public function getPools()
	{
		return $this->pools;
	}

	/**
	 * Initializes the pool of elements.
	 * @param  int $target_pool
	 * @return Btrieve
	 */
	public function initPool($target_pool = self::POOL_MAIN)
	{
		$this->getPool($target_pool)->init();
		return $this;
	}

	/**
	 * Initializes all pools registered.
	 * @return Btrieve
	 */
	public function initPools()
	{
		foreach (array_keys($this->getPools()) as $key) {
			$this->initPool($key);
		}
		return $this;
	}

	/**
	 * Remove all elements from a pool of elements.
	 * @param  int $target_pool
	 * @return Btrieve
	 */
	public function clearPool($target_pool = self::POOL_MAIN)
	{
		$this->getPool($target_pool)->reset();
		return $this;
	}

	/**
	 * Clear all registered pools.
	 * @return Btrieve
	 */
	public function clearPools()
	{
		foreach (array_keys($this->getPools()) as $key) {
			$this->clearPool($key);
		}
		return $this;
	}

	/**
	 * Adds a record to one of the pools.
	 * @param mixed $record
	 * @return Btrieve
	 */
	protected function addElem($element)
	{
		foreach($this->getPools() as $pool) {
			$pool->add($element);
		}
		return $this;
	}

	/**
	 * Returns if the reader can se if it has more elements or not.
	 * @return boolean
	 */
	public function hasNext()
	{
		if($this->position() == $this->inputLength() and feof($this->getPointer())) {
			return false;
		}
		return true;
	}

	/**
	 * Returns Btrieve's pointer position at the input file
	 * @return int
	 */
	public function position()
	{
		return ftell($this->getPointer());
	}

	/**
	 * Reads next element using the rules set starting from last pointers position.
	 * @return object
	 */
	public function next()
	{
		if(!$this->hasNext()) {
			return null;
		}
		$size = $this->nextRecordLength();
		if($size < 1) {
			return null;
		}
		$raw_content = $this->readBits($size);
		if(is_null($raw_content)) {
			return null;
		}
		$index = $this->applyRules($raw_content, $size);
	}

	/**
	 * Read number of bits from the input file.
	 * @param  int $n
	 * @return string
	 */
	protected function readBits($n = 1)
	{
		if($this->position() + $n >= $this->inputLength() and feof($this->getPointer())) {
			return null;
		}
		return fread($this->getPointer(), $n);
	}

	/**
	 * Gets the next record length in bits
	 * @return int
	 */
	protected function nextRecordLength()
	{
		list($size, $temp) = ['', ''];
		
		while ($temp != "," and $this->hasNext()) {
			$size .= $temp;
			$temp = Bin::toStr($this->readBits());
		}

		return intval($size);
	}

	/**
	 * Gets a raw line and apply all declared rules to the object.
	 * @param  string $raw  String result of the specified length declared at beginning.
	 * @param  int $size Length of the string
	 * @return Btrieve
	 */
	protected function applyRules($raw, $size)
	{
		$position = 0;
		$attributes = [];

		foreach ($this->getRules() as $rule) {
			$column_content = substr($raw, $position, $rule->getLength());
			if($rule instanceof ColumnInterface) {
				$attributes[$rule->getTitle()] = $rule::cast($column_content);
			}
			$position += $rule->getLength();
		}
	
		if($position < $size) {
			$column_content = Bin::toHex(substr($raw, $position, ($size-$position)));
			$column_content = preg_split('/(?<=2020)2020(?!2020])/', $column_content);
			$column_content = Hex::toStr(end($column_content));
			$attributes[$this->getVariableColumnName()] = $column_content;
		}

		return $this->addElem($attributes);
	}

	/**
	 * Verify if the element exists
	 * @param  int $index
	 * @param  int $target_pool [description]
	 * @return bool
	 */
	public function exists($index, $target_pool = self::POOL_MAIN)
	{
		if($this->haspool($index)) {
			return $this->getPool($key)->exists($index);
		}
		throw new InvalidArgumentException('Invalid pool.');
	}

	/**
	 * Returns an element from specific position.
	 * @param  int $index
	 * @param  int $target_pool
	 * @return mixed
	 */
	public function elem($index, $target_pool = self::POOL_MAIN)
	{
		if($this->haspool($index)) {
			return $this->getPool($target_pool)->elem($index);
		} else {
			throw new InvalidArgumentException('Invalid pool.');
		}
		return null;
	}

	/**
	 * Get the first element.
	 * @param  int $target_pool
	 * @return mixed
	 */
	public function first($target_pool = self::POOL_MAIN)
	{
		return $this->elem(0, $target_pool);
	}

	/**
	 * Take a [n] ammounts of elements from the file and add to the pool.
	 * @param  integer $n Number of elements to read.
	 * @return Btrieve;
	 */
	public function take($number_of_elements = 1)
	{
		$elements = [];
		while(($number_of_elements > 0) and $this->hasNext())
		{
			$temp = $this->next();
			$objects->push($temp);
			$n--;
		}

		return $objects->all();
	}

	/**
	 * Read the whole input file and converts.
	 * @return Btrieve
	 */
	public function all() {
		$objects = []
		while($this->hasNext()) {
			$next = $this->next();
			if($next == FALSE) break;
			$objects->push($next);
		}

		return $objects;
	}

}