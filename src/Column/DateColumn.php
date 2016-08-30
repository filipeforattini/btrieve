<?php
namespace FForattini\Btrieve\Column;

use Exception;
use Datetime;
use FForattini\Btrieve\Hex;

class DateColumn extends ColumnRule implements ColumnInterface
{
	const DEFAULT_LENGTH = 8;

	public function __construct($title, $length = self::DEFAULT_LENGTH)
	{
		$this->setTitle($title);
		$this->setType(self::TYPE_DATE);
		$this->setLength($length);
	}

	public static function cast($content)
	{
		if(self::validate($content)) {
			return null;
		}
		
		$content = Hex::toStr($content);

		try {
			$content = DateTime::createFromFormat('Ymd', $content);
			$content->setTime(0, 0, 0);
		} catch (Exception $e) {
			$content = NULL;
		}

		return $content;
	}

	public static function validate($content)
	{
		if(intval($content) == 0 OR strlen($content)<8 OR $content == "2020202020202020") {
			return false;
		}
		return true;
	}
}