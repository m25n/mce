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

use \DateTime,
    \DateInterval,
    \DateTimeZone,
    \Mce\Date\Period as DatePeriod;

/**
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
class PeriodTest extends \PHPUnit_Framework_TestCase
{
    public function testFowardIteration()
    {
        $tz = new DateTimeZone('GMT');
        $start = new DateTime('2012-12-01 00:00:00', $tz);
        $interval = new DateInterval('P1D');
        $recurrences = 1;

        $period = new DatePeriod($start, $interval, $recurrences);

        // test forward iteration
        $count = 0;
        foreach($period as $i => $dt) {
            $this->assertEquals($count, $i);
            $count++;
            switch($count) {
                case 2:
                    $this->assertEquals(new DateTime('2012-12-02 00:00:00', $tz), $dt);
                    break;

                case 1:
                default:
                    $this->assertEquals(new DateTime('2012-12-01 00:00:00', $tz), $dt);
                    break;
            }
        }
        $this->assertEquals($recurrences + 1, $count);


        // test forward iteration afer iteration
        $count = 0;
        foreach($period as $i => $dt) {
            $this->assertEquals($count, $i);
            $count++;
            switch($count) {
                case 2:
                    $this->assertEquals(new DateTime('2012-12-02 00:00:00', $tz), $dt);
                    break;

                case 1:
                default:
                    $this->assertEquals(new DateTime('2012-12-01 00:00:00', $tz), $dt);
                    break;
            }
        }
        $this->assertEquals($recurrences + 1, $count);
    }

    public function testReverseIteration()
    {
        $tz = new DateTimeZone('GMT');
        $start = new DateTime('2012-12-02 00:00:00', $tz);
        $interval = new DateInterval('P1D');
        $recurrences = 1;

        $period = new DatePeriod($start, $interval, $recurrences, '\Mce\Date\Period\ReverseIterator');

        // test reverse iteration
        $count = 0;
        foreach($period as $i => $dt) {
            $this->assertEquals($count, $i);
            $count++;
            switch($count) {
                case 2:
                    $this->assertEquals(new DateTime('2012-12-01 00:00:00', $tz), $dt);
                    break;

                case 1:
                default:
                    $this->assertEquals(new DateTime('2012-12-02 00:00:00', $tz), $dt);
                    break;
            }
        }
        $this->assertEquals($recurrences + 1, $count);


        // test reverse iteration afer iteration
        $count = 0;
        foreach($period as $i => $dt) {
            $this->assertEquals($count, $i);
            $count++;
            switch($count) {
                case 2:
                    $this->assertEquals(new DateTime('2012-12-01 00:00:00', $tz), $dt);
                    break;

                case 1:
                default:
                    $this->assertEquals(new DateTime('2012-12-02 00:00:00', $tz), $dt);
                    break;
            }
        }
        $this->assertEquals($recurrences + 1, $count);
    }
    
    public function testNearestBefore()
    {
        $tz = new DateTimeZone('GMT');
        $start = new DateTime('2012-12-01 00:00:00', $tz);
        $interval = new DateInterval('P1D');
        $recurrences = 10;

        $period = new DatePeriod($start, $interval, $recurrences);
        
        
        // assert that dates stop after the maximum number of reccurences
        $this->assertEquals(new DateTime('2012-12-11 00:00:00', $tz), $period->nearestBefore(new DateTime('2012-12-12 00:00:00', $tz)));
        
        // assert that dates stop at the maximum number of reccurences
        $this->assertEquals(new DateTime('2012-12-11 00:00:00', $tz), $period->nearestBefore(new DateTime('2012-12-11 00:00:00', $tz)));
        
        // assert that dates before the maximum number of recurrences are handled properly
        $this->assertEquals(new DateTime('2012-12-10 00:00:00', $tz), $period->nearestBefore(new DateTime('2012-12-10 00:00:00', $tz)));
        $this->assertEquals(new DateTime('2012-12-05 00:00:00', $tz), $period->nearestBefore(new DateTime('2012-12-05 00:00:00', $tz)));
    }

    public function testRecurrencesBetween()
    {
        $tz = new DateTimeZone('GMT');
        $start = new DateTime('2012-12-01 00:00:00', $tz);
        $interval = new DateInterval('P1D');
        $recurrences = 10;

        $period = new DatePeriod($start, $interval, $recurrences);
        
        
        // assert that dates stop after the maximum number of reccurences
        $this->assertEquals($recurrences, $period->recurrencesBetween(new DateTime('2012-12-12 00:00:00', $tz)));
        
        // assert that dates stop at the maximum number of reccurences
        $this->assertEquals($recurrences, $period->recurrencesBetween(new DateTime('2012-12-11 00:00:00', $tz)));
        
        // assert that dates before the maximum number of recurrences are handled properly
        $this->assertEquals(9, $period->recurrencesBetween(new DateTime('2012-12-10 00:00:00', $tz)));
        $this->assertEquals(4, $period->recurrencesBetween(new DateTime('2012-12-05 00:00:00', $tz)));
    }

    public function testInfinity()
    {
        $tz = new DateTimeZone('GMT');
        $start = new DateTime('2013-01-01 00:00:00', $tz);
        $interval = new DateInterval('P1D');
        $recurrences = 300;

        $period = new DatePeriod($start, $interval);
        
        // assert that dates stop after the maximum number of reccurences
        $this->assertEquals(300, $period->recurrencesBetween(new DateTime('2013-10-28 00:00:00', $tz)));
    }
}
