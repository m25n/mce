<?php

/*
 * This file is part of the Mce package.
 *
 * (c) Matthew Conger-Eldeen <mceldeen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mce\File\Parse;

/**
 * Parses an ini file and returns an array of data
 *
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
class IniInterface {
    /**
     * Parses an ini file and returns the variables as an array
     * 
     * @param string $filename the name of the file
     * @param string $section the section you want to get variables from
     * @return array the ini variables
     */
    public function parse($filename, $section = "production");
}
