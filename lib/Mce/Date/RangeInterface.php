<?php
namespace Mce\Date;

/**
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
interface RangeInterface {
    
    public function __construct(\DateTime $start, \DateTime $end);
   
    /**
     * @return \DateTime the start of the range
     */
    public function getStart();
    
    /**
     * @return \DateTime the end of the range
     */
    public function getEnd();
}
?>
