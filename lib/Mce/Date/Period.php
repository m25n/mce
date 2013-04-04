<?php

/*
 * This file is part of the Mce package.
 *
 * (c) Matthew Conger-Eldeen <mceldeen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mce\Date;

class Period implements \IteratorAggregate
{
    protected $iterator;
    protected $start;
    protected $interval;
    protected $recurrences;

    public function __construct(\DateTime $start, \DateInterval $interval, $recurrences = null, $iteratorClass = '\Mce\Date\Period\ForwardIterator')
    {
        $this->iterator = new $iteratorClass($start, $interval, $recurrences);
        $this->start = $start;
        $this->interval = $interval;
        $this->recurrences = intval($recurrences);
    }

    public function getIterator()
    {
        return $this->iterator;
    }
    
    /**
     * Find the nearest ocurrence that is before $now
     *
     * @param   \DateTime   $now
     * @return  \DateTime
     */
    public function nearestBefore(\DateTime $now)
    {
        $m = $this->recurrencesBetween($now);

        $current = clone $this->start;

        if($m > 0) {
            $current->add(new \DateInterval(
                  "P" 
                . $this->interval->y * $m . "Y"
                . $this->interval->m * $m . "M"
                . $this->interval->d * $m . "D"
                . "T"
                . $this->interval->h * $m . "H"
                . $this->interval->i * $m . "M"
                . $this->interval->s * $m . "S"
            ));
        }
        
        return $current;
    }

    public function recurrencesBetween(\DateTime $now)
    {
        if($this->start == $now) return 0;

        // multiply by the maximum days for each interval to ensure that when we
        // divide the difference by this that we get the smallest number 
        // possible
        $d = $this->interval->y * 366
           + $this->interval->m * 31
           + $this->interval->d
           + $this->interval->h / 24
           + $this->interval->i / 1440
           + $this->interval->s / 86400;

        $m = floor($this->start->diff($now)->days / $d);
        
        if(null !== $this->recurrences && $m > $this->recurrences) return $this->recurrences;
               
        $current = clone $this->start;

        if($m > 0) {
            $current->add(new \DateInterval(
                  "P" 
                . $this->interval->y * $m . "Y"
                . $this->interval->m * $m . "M"
                . $this->interval->d * $m . "D"
                . "T"
                . $this->interval->h * $m . "H"
                . $this->interval->i * $m . "M"
                . $this->interval->s * $m . "S"
            ));
        }

        $extra = 0;
        while($now > $current) {
            if($m + $extra >= $this->recurrences) return $this->recurrences;
            $extra++;
            $current->add($this->interval);
        }
        $m += $extra;
        
        return $m;
    }
}
