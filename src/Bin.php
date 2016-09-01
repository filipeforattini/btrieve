<?php

namespace FForattini\Btrieve;

class Bin
{
    /**
     * Converts Binary to Hexadecimal.
     *
     * @param string $bin
     *
     * @return string
     */
    public static function toHex($bin)
    {
        return bin2hex($bin);
    }

    /**
     * Converts Binary to ASCII.
     *
     * @param string $bin
     *
     * @return string
     */
    public static function toStr($bin)
    {
        return Hex::toStr(self::toHex($bin));
    }
}
