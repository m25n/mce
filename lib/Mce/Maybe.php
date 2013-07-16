<?php
namespace Mce;

interface Maybe
{
    public function __invoke($default, $g = null);
    public function bind($g);
}
