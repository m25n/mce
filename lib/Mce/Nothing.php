<?php
namespace Mce;

class Nothing implements Maybe
{
    public function __invoke($default, $g = null)
    {
        return $default;
    }

    public function bind($g)
    {
        return $this;
    }
}
