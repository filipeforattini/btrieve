<?php
namespace FForattini\Btrieve\Persistence;

use Exception;

abstract class Pool implements DatasetInterface
{
	protected $dataset;

	/**
	 * Contructor
	 */
	public function __construct()
	{
		$this->init();
	}

	/**
	 * Initializes a pool of elements.
	 * @return Pool
	 */
	public function init()
	{
		$this->dataset = [];
		return $this;
	}

	/**
	 * Erase all information from dataset.
	 * @return Pool
	 */
	public function reset()
	{
		foreach ($this->dataset as $index => $element) {
			unset($this->dataset[$index]);
		}
		unset($this->dataset);
		$this->initDataset();
		return $this;
	}

	/**
	 * Checks if this element exists in this dataset.
	 * @param  int  $index
	 * @return boolean
	 */
	public function has($index)
	{
		if(isset($this->dataset[$index])) {
			return true;
		}
		return false;
	}

	/**
	 * @aliasof has
	 * @param  int  $index
	 * @return boolean
	 */
	public function exists($index)
	{
		return $this->has($index);
	}

	/**
	 * Returns the element in this index from the dataset.
	 * @param  int $index
	 * @return mixed
	 */
	public function elem($index)
	{
		if($this->has($index)) {
			return $this->dataset[$index];
		}
		throw new Exception('Element doesnt exist in this dataset.');
	}

	/**
	 * Function will show how to deal with the element.
	 * @param  array $element
	 * @return mixed
	 */
	abstract public function cast($element);

	/**
	 * Adds an element to the dataset.
	 * @param array $element
	 */
	public function add($element)
	{
		$this->dataset[] = $this->cast($element);
	}

	/**
	 * Return number of elements.
	 * @return int
	 */
	public function count()
	{
		return count($this->dataset);
	}
}