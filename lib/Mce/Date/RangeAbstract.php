<?php
namespace Mce\Date;

/**
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
abstract class RangeAbstract implements RangeInterface {
    protected $start  = null;
    protected $end    = null;

    public function __construct(\DateTime $start, \DateTime $end) { 
        if($start >= $end) {
            throw new \LogicException("The start of your range must be before the end of your range.");
        }
        
        $this->start = $start;
        $this->end = $end;
        $this->end->setTimezone($start->getTimezone());
    }
    
    /**
     * @return \DateTime the start of the range
     */
    public function getStart() {
        return $this->start;
    }
    
    /**
     * @return \DateTime the end of the range
     */
    public function getEnd() {
        return $this->end;
    }
}
