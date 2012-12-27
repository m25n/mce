<?php
namespace Mce\Date;

use \Mce\Date\Range\Inclusive as Range;

/**
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
class Math {
    public static function getWeekRange(\DateTime $date, $weekStart = 1) {
        $dayOfWeek = intval($date->format("N"));    
        $weekEnd = (($weekStart + 5) % 7) + 1;
        
        $start = clone $date;
        $start->setTime(0, 0, 0);
        if($dayOfWeek >= $weekStart) {
            $start->sub(new \DateInterval("P" . ($dayOfWeek - $weekStart) . "D"));
        } elseif($dayOfWeek < $weekStart) {
            $start->sub(new \DateInterval("P" . ($dayOfWeek + (7 - $weekEnd) - 1) . "D"));
        }
        
        $end = clone $start;
        $end->add(new \DateInterval("P6D"));
        $end->setTime(23, 59, 59);
        
        return new Range($start, $end);
    }
       
    public static function getWeekOfMonthRange($weekOfMonth, \DateTime $month, $weekStart = 1) {
        // find the week day of the first of the month and add $weekOfMonth - 1 weeks to it 
        $start = self::getNWeekdayOfMonth($weekOfMonth, $weekStart, $month);
        
        $end = clone $start;
        $end->add(new \DateInterval("P6D"));
        $end->setTime(23, 59, 59);
        
        return new Range($start, $end);
        
    }
    
    public static function getWeekOfMonthNumber(\DateTime $month, $weekStart = 1) {
        $firstWeekdayOfMonth = self::getFirstWeekdayOfMonth($weekStart, $month);
        $firstDay = intval($firstWeekdayOfMonth->format("j"));
        
        $currentDay = intval($month->format("j"));
        
        if($currentDay >= $firstDay) {        
            return (int)floor(($currentDay - $firstDay) / 7) + 1;
        } else {
            return self::getWeekOfMonthNumber($firstWeekdayOfMonth->sub(new \DateInterval("P7D")), $weekStart);
        }
    }
    
    public static function getMonthRange(\DateTime $date) {
        $start = new \DateTime($date->format("Y-m-01 00:00:00"), $date->getTimezone());
        $end = new \DateTime($date->format("Y-m-t 23:59:59"));
        
        return new Range($start, $end);
    }
    
    public static function getFirstWeekdayOfMonth($weekDay, \DateTime $month) {
        $firstOfMonth = new \DateTime($month->format("Y-m-01 00:00:00"), $month->getTimezone());
        $firstOfMonthWeekday = intval($firstOfMonth->format("N"));
       
        $dayOfFirstWeekday = 1 + ($weekDay <= $firstOfMonthWeekday ? 7:0) + $weekDay - $firstOfMonthWeekday;
        
        $firstWeekdayOfMonth = clone $firstOfMonth;
        $firstWeekdayOfMonth->setTime(0, 0, 0);
        $firstWeekdayOfMonth->add(new \DateInterval("P" . ($dayOfFirstWeekday - 1). "D"));
        
        return $firstWeekdayOfMonth;
    }
    
    public static function getNWeekdayOfMonth($n, $weekDay, \DateTime $month) {
        $weekdayOfMonth = self::getFirstWeekdayOfMonth($weekDay, $month);
        $weekdayOfMonth->add(new \DateInterval("P" . (($n - 1) * 7) . "D"));
        
        return $weekdayOfMonth;
    }
}
