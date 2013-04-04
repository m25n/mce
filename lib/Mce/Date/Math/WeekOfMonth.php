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

use \Mce\Date\Range\Inclusive as InclusiveRange;

/**
 * Useful utilities for week math
 *
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
class WeekOfMonth {
    /**
     * Calculates the date range of the week for an arbitrary datetime. The 
     * first week of the month starts on the first occurence of $weekStart. 
     * $weekStart is the ISO-8601 numeric representation of the day of the week. 
     * 
     * @param \DateTime $date
     * @param int $weekStart ISO-8601 numeric representation of the day of the 
     *                       week (1 = Monday,..., 7 = Sunday)
     * @return \Mce\Date\Range\Inclusive
     */
    public static function getRangeFromDate(\DateTime $date, $weekStart = 1) {
        $dayOfWeek = intval($date->format("N"));    
        $weekEnd = (($weekStart + 5) % 7) + 1;
        
        $start = clone $date;
        $start->setTime(0, 0, 0);
        $startDiff = 0; // days
        if($dayOfWeek >= $weekStart) {
            $startDiff = ($dayOfWeek - $weekStart);
        } elseif($dayOfWeek < $weekStart) {
            $startDiff = ($dayOfWeek + (7 - $weekEnd) - 1);
        }
        $start->sub(new \DateInterval("P" . $startDiff . "D"));
        
        $end = clone $start;
        $end->add(new \DateInterval("P6D"));
        $end->setTime(23, 59, 59);
        
        return new InclusiveRange($start, $end);
    }
    
    /**
     * Gets the week range from the week of month.
     * 
     * @param int $weekOfMonth The week of the month
     * @param \DateTime $month Any date time within the month you want
     * @param int $weekStart ISO-8601 numeric representation of the day of the 
     *                       week (1 = Monday,..., 7 = Sunday)
     * @return \Mce\Date\Range\Inclusive
     */
    public static function getRangeFromNumber($weekOfMonth, \DateTime $month, $weekStart = 1) {
        // find the week day of the first of the month and add $weekOfMonth - 1 weeks to it 
        try {
            $start = Month::getNWeekday($weekOfMonth, $weekStart, $month);
        } catch(\RangeException $e) {
            throw new \RangeException("{$month->format('F Y')} does not have {$weekOfMonth} weeks (week start = {$weekStart}");
        }
        
        $end = clone $start;
        $end->add(new \DateInterval("P6D"));
        $end->setTime(23, 59, 59);
        
        return new InclusiveRange($start, $end);
    }
    
    /**
     * Gets the the number of weeks since the first occurance of the specified 
     * weekday ($weekStart) of the month.
     * 
     * @param \DateTime $date 
     * @param int $weekStart ISO-8601 numeric representation of the day of the 
     *                       week (1 = Monday,..., 7 = Sunday)
     * @return Array(int, int, int) tuple with format (year, month, week) 
     */
    public static function getNumber(\DateTime $date, $weekStart = 1) {
        $firstWeekdayOfMonth = Month::getFirstWeekday($weekStart, $date);
        $firstDay = intval($firstWeekdayOfMonth->format("j"));
        $currentDay = intval($date->format("j"));
        
        if($currentDay >= $firstDay) { // we're in the current month  
            $year = intval($firstWeekdayOfMonth->format('Y'));
            $month = intval($firstWeekdayOfMonth->format('n'));
            return array($year, $month, intval(floor(($currentDay - $firstDay) / 7) + 1));
        } else { // we're in the previous month's last week
            return self::getNumber($firstWeekdayOfMonth->sub(new \DateInterval('P7D')), $weekStart); // so caclulate the week number of the previous month
        }
    }
    
    /**
     * Gets the number of weeks that exist in a given month.
     * 
     * @param \DateTime $month
     * @param int $weekStart ISO-8601 numeric representation of the day of the 
     *                       week (1 = Monday,..., 7 = Sunday)
     * @return int 
     */
    public static function getMaxNumber(\DateTime $month, $weekStart = 1) {
        $firstWeekdayOfMonth = Month::getFirstWeekday($weekStart, $month);
        $firstDay = intval($firstWeekdayOfMonth->format("j"));
        $lastDay = intval($month->format("t"));
        
        return (int)floor(($lastDay - $firstDay) / 7) + 1;
    }
}
?>
