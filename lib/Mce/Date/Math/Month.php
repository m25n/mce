<?php

/*
 * This file is part of the Mce package.
 *
 * (c) Matthew Conger-Eldeen <mceldeen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mce\Date\Math;

/**
 * Useful utilities for month math
 *
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
class Month {
    /**
     * The first range of the month
     * 
     * @param \DateTime $date Any date within the desired month
     * @return \Mce\Date\Range 
     */
    public static function getRange(\DateTime $date) {
        $start = new \DateTime($date->format("Y-m-01 00:00:00"), $date->getTimezone());
        $end = new \DateTime($date->format("Y-m-t 23:59:59"), $date->getTimezone());
        
        return new \Mce\Date\Range($start, $end);
    }
    
    /**
     *
     * @param int $weekDay ISO-8601 numeric representation of the day of the 
     *                     week (1 = Monday,..., 7 = Sunday)
     * @param \DateTime $month Any date within the desired month
     * @return \DateTime
     */
    public static function getFirstWeekday($weekDay, \DateTime $month) {
        $firstOfMonth = new \DateTime($month->format("Y-m-01 00:00:00"), $month->getTimezone());
        $firstOfMonthWeekday = intval($firstOfMonth->format("N"));
       
        $dayOfFirstWeekday = 1 + ($weekDay <= $firstOfMonthWeekday ? 7:0) + $weekDay - $firstOfMonthWeekday;
        
        $firstWeekdayOfMonth = clone $firstOfMonth;
        $firstWeekdayOfMonth->setTime(0, 0, 0);
        $firstWeekdayOfMonth->add(new \DateInterval("P" . ($dayOfFirstWeekday - 1). "D"));
        
        return $firstWeekdayOfMonth;
    }
    
    /**
     *
     * @param int $n
     * @param int $weekDay ISO-8601 numeric representation of the day of the 
     *                     week (1 = Monday,..., 7 = Sunday)
     * @param \DateTime $month Any date within the desired month
     * @return type 
     */
    public static function getNWeekday($n, $weekDay, \DateTime $month) {
        $weekdayOfMonth = self::getFirstWeekday($weekDay, $month);
        $weekdayOfMonth->add(new \DateInterval("P" . (($n - 1) * 7) . "D"));
        
        return $weekdayOfMonth;
    }
}
?>
