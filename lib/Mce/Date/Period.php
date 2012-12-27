<?php
namespace Mce\Date;

class Period implements \IteratorAggregate
{
    protected $iterator;

    public function __construct(\DateTime $start, \DateInterval $interval, $recurrences, $iteratorClass = '\Mce\Date\Period\ForwardIterator')
    {
        $this->iterator = new $iteratorClass($start, $interval, $recurrences);
    }

    public function getIterator()
    {
        return $this->iterator;
    }
}
