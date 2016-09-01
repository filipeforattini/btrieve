<?php

namespace FForattini\Btrieve\Persistence;

class DebugPool extends Pool
{
    public function cast($element)
    {
        return $element;
    }
}
