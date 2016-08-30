<?php
namespace FForattini\Btrieve\Persistence;

use InvalidArgumentException;

class ActiveRecord
{
	private $columns;
	private $data;
	private $attributes;

	/**
	 * Shorter way to create an ActiveRecord using an array of attributes.
	 * @param  array  $attributes
	 * @return ActiveRecord
	 */
	public static function attributes($attributes = [])
	{
		return (new static)->setAttributes($attributes);
	}

	/**
	 * Constructor
	 * @param array $columns
	 * @param array $data
	 */
	public function __construct($columns = [], $data = [])
	{
		if(count($columns) !== count($data)) {
			throw new InvalidArgumentException('Columns are required to have the same # of elements from data.');
		}
		$this->setColumns($columns);
		$this->setData($data);
		if(!empty($columns)) {
			$this->setAttributes(array_combine($this->getColumns(), $this->getData()));
		}
	}

	/**
	 * Set columns
	 * @param array $columns
	 * @return ActiveRecord
	 */
	public function setColumns($columns)
	{
		$this->columns = $columns;
		return $this;
	}

	/**
	 * Get columns
	 * @return array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Set correspondent data from columns order.
	 * @param array $data
	 * @return ActiveRecord
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * Get correspondent data from columns order.
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Returns combination array of attributes
	 * @return array
	 */
	public function toArray()
	{
		return $this->getAttributes();
	}

	/**
	 * Defines columns and data from an array of attributes
	 * @param array $attributes
	 * @return ActiveRecord
	 */
	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;
		$this->setColumns(array_keys($attributes));
		$this->setData(array_values($attributes));
		return $this;
	}

	/**
	 * Get an array of attributes.
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Verify if columns exists.
	 * @param  string  $key
	 * @return boolean
	 */
	public function has($key)
	{
		if(isset($this->attributes[$key])) {
			return true;
		}
		return false;
	}

	/**
	 * Returns attribute of the element
	 * @param  string $key
	 * @return mixed
	 */
	public function attribute($key)
	{
		if(! $this->has($key)) {
			throw new InvalidArgumentException('Attribute \''.$key.'\' doesnt exists.');
		}
		return $this->attributes[$key];
	}

	/**
	 * @aliasof attribute
	 * @param  string $key
	 * @return mixed
	 */
	public function attr($key)
	{
		return $this->attribute($key);
	}

	/**
	 * Update attribute value.
	 * @param string $key
	 * @param mixed $value
	 */
	public function update($key, $value)
	{
		if(! $this->has($key)) {
			throw new InvalidArgumentException('Attribute \''.$key.'\' doesnt exists.');
		}
		$this->attributes[$key] = $value;
		$this->data[array_search($key, $this->getColumns())] = $value;
		return $this;
	}

	/**
	 * Set new attribute value.
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key, $value)
	{
		if($this->has($key)) {
			return $this->update($key, $value);
		}
		$this->attributes[$key] = $value;
		$this->columns[] = $key;
		$this->data[] = $value;
		return $this;
	}
}