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
class JustTest extends \PHPUnit_Framework_TestCase
{
    public function testJustReturnsOriginalValueWhenThereIsNoFunction()
    {
        $subject = new Just(10);

        $this->assertEquals(10, $subject(-1));
    }

    public function testJustReturnsFunctionAppliedToOriginalValue()
    {
        $subject = new Just(10);

        $this->assertEquals(100, $subject(-1, function($x) { return $x * $x; }));
    }

    public function testBind()
    {
        $subject1 = new Just(11);
        $subject2 = new Just(10);

        $onlyGreaterThanTen = function($x) {
            if($x > 10) {
                return new Just($x);
            }
            return new Nothing();
        };

        $minusOne = function($x) {
            return new Just($x - 1);
        };

        $this->assertEquals(new Just(11), $subject1->bind($onlyGreaterThanTen));
        $this->assertEquals(new Nothing(), $subject2->bind($onlyGreaterThanTen));
        $this->assertEquals(new Nothing(), $subject1
            ->bind($onlyGreaterThanTen)
            ->bind($minusOne)
            ->bind($onlyGreaterThanTen)
            ->bind($minusOne)
        );
    }
}
