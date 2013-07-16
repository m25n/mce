<?php

/*
 * This file is part of the Mce package.
 *
 * (c) Matthew Conger-Eldeen <mceldeen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mce;

/**
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
class NothingTest extends \PHPUnit_Framework_TestCase
{
    public function testNothingReturnsDefaultValue()
    {
        $subject = new Nothing();

        $this->assertEquals(-1, $subject(-1));
        $this->assertEquals(-1, $subject(-1, function($x) { return $x * $x; }));
    }

    public function testBind()
    {
        $subject = new Nothing();

        $onlyGreaterThanTen = function($x) {
            if($x > 10) {
                return new Just($x);
            }
            return new Nothing();
        };

        $this->assertEquals(new Nothing(), $subject->bind($onlyGreaterThanTen));
    }
}
