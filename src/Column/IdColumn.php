<?php

namespace FForattini\Btrieve\Column;

use FForattini\Btrieve\Bin;

class IdColumn extends ColumnRule implements ColumnInterface
{
    const DEFAULT_LENGTH = 1;

    public function __construct($title, $length = self::DEFAULT_LENGTH)
    {
        $this->setTitle($title);
        $this->setType(self::TYPE_ID);
        $this->setLength($length);
    }

    public static function cast($content)
    {
        $content = str_split(Bin::toHex($content), 2);

        $id = 0;
        foreach ($content as $power => $value) {
            $id += hexdec($value) * pow(256, $power);
        }

        return intval($id);
    }
}
