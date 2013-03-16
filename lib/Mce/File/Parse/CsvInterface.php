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
 * Parses csv files with a customizable callback for each line
 *
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
interface CsvInterface {
    
    /**
     * Parses a csv file and passes each row to a callback function
     * 
     * @param string $filename the name of the file
     * @param bool $headerRow whether or not the file has heade row
     * @return int how many rows were parsed
     */
    public function parse($filename, $headerRow = true);
    
    
    /**
     * Sets the callback function that handles the data foreach row.
     * 
     * The callback function has the arguments
     *  $n  : the row number
     *  $row: the row's data (if there is no header then each value is accessed 
     *        by the index)
     * 
     * @param string|function $callback
     * @return CsvInterface this instance
     */
    public function setCallback($callback);
}