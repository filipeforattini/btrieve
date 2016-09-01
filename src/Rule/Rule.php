<?php

namespace FForattini\Btrieve\Rule;

abstract class Rule
{
    protected $length;

    public function setLength($n)
    {
        $this->length = $n;
    }

    public function getLength()
    {
        return $this->length;
    }
}
