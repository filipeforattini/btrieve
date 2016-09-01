<?php

namespace FForattini\Btrieve\Persistence;

class RecordsPool extends Pool
{
    public function cast($element)
    {
        return BtrieveRecord::attributes($element);
    }
}
