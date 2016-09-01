<?php

namespace FForattini\Btrieve\Column;

use FForattini\Btrieve\Rule\Rule;
use FForattini\Btrieve\Rule\RuleInterface;

class ColumnRule extends Rule implements RuleInterface
{
    const TYPE_DATE = 0;
    const TYPE_FLOAT = 1;
    const TYPE_ID = 2;
    const TYPE_INT = 3;
    const TYPE_STRING = 4;

    protected $title;
    protected $type;

    public function __construct($title, $type, $length)
    {
        $this->setTitle($title);
        $this->setType($type);
        $this->setLength($length);
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}
