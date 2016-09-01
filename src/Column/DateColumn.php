<?php

namespace FForattini\Btrieve\Column;

use Datetime;
use Exception;
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
        if (self::validate($content)) {
            return;
        }

        $content = Hex::toStr($content);

        try {
            $content = DateTime::createFromFormat('Ymd', $content);
            $content->setTime(0, 0, 0);
        } catch (Exception $e) {
            $content = null;
        }

        return $content;
    }

    public static function validate($content)
    {
        if (intval($content) == 0 or strlen($content) < 8 or $content == '2020202020202020') {
            return false;
        }

        return true;
    }
}
