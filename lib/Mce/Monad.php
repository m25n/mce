<?php
namespace Mce;

class Monad
{
    public static function maybe($x = null)
    {
        if(!is_null($x)) {
            return new Just($x);
        }
        return new Nothing();
    }
}
