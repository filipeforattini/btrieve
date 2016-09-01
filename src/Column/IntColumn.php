<?php

namespace FForattini\Btrieve\Column;

class IntColumn extends ColumnRule implements ColumnInterface
{
    const DEFAULT_LENGTH = 2;

    public function __construct($title, $length = self::DEFAULT_LENGTH)
    {
        $this->setTitle($title);
        $this->setType(self::TYPE_INT);
        $this->setLength($length);
    }

    public static function cast($content)
    {
        return intval($content);
    }
}
