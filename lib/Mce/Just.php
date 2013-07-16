<?php
namespace Mce;

class Just implements Maybe
{
    protected $x;

    public function __construct($x)
    {
        $this->x = $x;
    }

    public function __invoke($default, $g = null)
    {
        if(is_null($g)) {
            return $this->x;
        }
        return $g($this->x);
    }

    public function bind($g)
    {
        return $g($this->x);
    }
}

