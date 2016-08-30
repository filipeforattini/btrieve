<?php
namespace FForattini\Btrieve\Persistence;

class RowsPool extends Pool
{
	public function cast($element)
	{
		return array_values($element);
	}
}
