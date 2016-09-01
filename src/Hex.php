<?php

namespace FForattini\Btrieve;

class Hex
{
    public static function toStr($hex)
    {
        $str = '';
        for ($i = 0; $i < strlen($hex); $i += 2) {
            $str .= chr(hexdec(substr($hex, $i, 2)));
        }

        return $str;
    }
}
