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
class MonadTest extends \PHPUnit_Framework_TestCase
{
    public function testMaybeReturnsNothingWhenNoArgument()
    {
        $this->assertTrue(Monad::maybe() instanceof Nothing);
    }

    public function testMaybeReturnsNothingWhenArgumentIsNull()
    {
        $this->assertTrue(Monad::maybe(null) instanceof Nothing);
    }

    public function testMaybeReturnsJustWhenArgumentIsNotNull()
    {
        $this->assertTrue(Monad::maybe('') instanceof Just);
    }
}
