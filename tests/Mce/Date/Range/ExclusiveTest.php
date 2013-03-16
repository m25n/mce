<?php

/*
 * This file is part of the Mce package.
 *
 * (c) Matthew Conger-Eldeen <mceldeen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mce\Date\Range;

use \DateTime,
    \DateInterval,
    \Mce\Date\Range\Exclusive as Range;

/**
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
class ExclusiveTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Mce\Date\Range\Inclusive::__construct
     *
     * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
     */
    public function testThrowsLogicExceptionWhenStartIsAfterEnd()
    {
        $start = new DateTime("2012-01-01 00:00:00");
        $end = new DateTime("2012-01-31 23:59:59");

        $this->setExpectedException("LogicException");
        new Range($end, $start);
    }

    /**
     * @covers Mce\Date\Range\Inclusive::getStart
     * @covers Mce\Date\Range\Inclusive::getEnd
     *
     * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
     */
    public function testGetStartEnd()
    {
        $start = new DateTime("2012-01-01 00:00:00");
        $end = new DateTime("2012-01-31 23:59:59");
        $range = new Range($start, $end);

        $this->assertTrue($range->getStart() === $start);
        $this->assertTrue($range->getEnd() === $end);
    }

    /**
     * @covers Mce\Date\Range\Inclusive::contains
     *
     * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
     */
    public function testContains()
    {
        $start = new DateTime("2012-01-01 00:00:00");

        $middle = new DateTime("2012-01-15 12:00:00");

        $end = new DateTime("2012-01-31 23:59:59");

        $before = clone $start;
        $before->sub(new DateInterval("PT1S"));

        $after = clone $end;
        $after->add(new DateInterval("PT1S"));

        $range = new Range($start, $end);

        $this->assertFalse($range->contains($start));

        $this->assertTrue($range->contains($middle));

        $this->assertFalse($range->contains($end));

        $this->assertFalse($range->contains($before));

        $this->assertFalse($range->contains($after));
    }
}
