<?php

namespace FForattini\Btrieve\Column;

class FloatColumn extends ColumnRule implements ColumnInterface
{
    const DEFAULT_LENGTH = 2;

    public function __construct($title, $length = self::DEFAULT_LENGTH)
    {
        $this->setTitle($title);
        $this->setType(self::TYPE_FLOAT);
        $this->setLength($length);
    }

    public static function cast($content)
    {
        return floatval($content);
    }
}
