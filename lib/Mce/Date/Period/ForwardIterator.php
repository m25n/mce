<?php
namespace Mce\Date\Period;

class ForwardIterator implements \Iterator
{
    protected $i = 0;
    protected $recurrences = 0;
    protected $current;
    protected $interval;
    protected $start;

    public function __construct(\DateTime $start, \DateInterval $interval, $recurrences)
    {
        $this->recurrences = $recurrences;
        $this->start = $start;
        $this->current = clone $start;
        $this->interval = $interval;
    }

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->i;
    }

    public function next()
    {
        ++$this->i;
        $this->current->add($this->interval);
    }

    public function rewind()
    {
        $this->i = 0;
        $this->current = clone $this->start;
    }

    public function valid()
    {
        return $this->i < ($this->recurrences + 1);
    }
}
