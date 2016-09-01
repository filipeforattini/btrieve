<?php

namespace FForattini\Btrieve\Rule;

class SkipRule extends Rule implements RuleInterface
{
    const DEFAULT_LENGTH = 1;

    public function __construct($length = self::DEFAULT_LENGTH)
    {
        $this->setLength($length);
    }
}
