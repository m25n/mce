<?php

/*
 * This file is part of the Mce package.
 *
 * (c) Matthew Conger-Eldeen <mceldeen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mce\Date\Range;

/**
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
class Inclusive extends \Mce\Date\RangeAbstract
{
    public function contains(\DateTime $date)
    {
        return $this->getStart() <= $date && $date <= $this->getEnd();
    }
}
