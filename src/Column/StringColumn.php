<?php

namespace FForattini\Btrieve\Column;

use FForattini\Btrieve\Bin;
use FForattini\Btrieve\Str;

class StringColumn extends ColumnRule implements ColumnInterface
{
    const DEFAULT_LENGTH = 30;

    public function __construct($title, $length = self::DEFAULT_LENGTH)
    {
        $this->setTitle($title);
        $this->setType(self::TYPE_STRING);
        $this->setLength($length);
    }

    public static function cast($content)
    {
        return ''.trim(Str::toUtf8(Bin::toStr($content)));
    }
}
