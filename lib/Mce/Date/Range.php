<?php
namespace Mce\Date;
require_once "RangeInterface.php";

/**
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
class Range implements RangeInterface {
    private $start  = null;
    private $end    = null;
    
    public function __construct(\DateTime $start, \DateTime $end) { 
        if($start > $end) {
            throw new \InvalidArgumentException("The start of your range must be before your range.");
        }
        
        $this->start = $start;
        $this->end = $end;
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
?>
