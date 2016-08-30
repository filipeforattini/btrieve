<?php
namespace FForattini\Btrieve\Persistence;

class BtrieveRecord extends ActiveRecord
{
	protected $length;

	/**
	 * Sets the element's length in bytes
	 * @param int $n
	 * @return BtrieveRecord
	 */
	public function setLength($n)
	{
		$this->length = $n;
		return $this;
	}

	/**
	 * Returns the element's length in bytes
	 * @return int
	 */
	public function getLength()
	{
		return $this->length;
	}
}