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
 * Parses csv files using php's building parsing function with a customizable
 * callback for each line.
 *
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
class Csv implements CsvInterface {

    private $callback;
    private $validateHeader;
    private $validate;
    private $headerError;
    private $errors;
    private $rate = 0;

    /**
     * Parses a csv file and passes each row to a callback function
     *
     * @param string $filename the name of the file
     * @param bool $headerRow whether or not the file has heade row
     * @return int how many rows were parsed
     * @throw \Mce\File\Exception\NotFound when the file does not exist
     * @throw \Mce\File\Exception\NotReadable when the file is not readable
     */
    public function parse($filename, $headerRow = true) {
        if(!file_exists($filename)) {
            require_once __DIR__ . "/../Exception/NotFound.php";
            throw new \Mce\File\Exception\NotFound("\"$filename\" does not appear to exist.");
        }

        if(!is_readable($filename)) {
            require_once __DIR__ . "/../Exception/NotReadable.php";
            throw new \Mce\File\Exception\NotReadable("\"$filename\" is not readable.");
        }

        $fh = fopen($filename, 'r');

        $headers = $headerRow ? $this->normalizeArray(fgetcsv($fh)):null;

        if($headers !== null) {
            $validateHeader = $this->getHeaderValidator();
            $this->headerError = $validateHeader($headers);
            if(strlen($this->headerError) > 0) {
                return 0;
            }
            $rowData = false;
            $n = 0;
            $this->errors = array();
            $validate = $this->getValidator();
            while(($rowData = fgetcsv($fh)) !== false && $rowData !== null) {
                $row = array_combine($headers, $rowData);
                if(null !== $validate) {
                    if($this->rate > 1) {
                        if($n % $this->rate != 0) {
                            goto CALLBACK;
                        }
                    }
                    $err = $validate($row);
                    if(strlen($err) > 0) {
                        $this->errors[] = array(
                            'line' => $n + 1,
                            'message' => $err
                        );
                    }
                }
                CALLBACK:
                $callback = $this->getCallback();
                $callback($n, $row);
                $n++;
            }
        } else {
            $row = false;
            $n = 0;
            while(($row = fgetcsv($fh)) !== false && $row !== null) {
                $callback = $this->getCallback();
                $callback($n, $row);
                $n++;
            }
        }

        fclose($fh);

        return $n;
    }


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
    public function setCallback($callback) {
        $this->callback = $callback;
        return $this;
    }

    public function getCallback() {
        if($this->callback === null) {
            $this->setCallback(function($n, $row) {});
        }
        return $this->callback;
    }

    /**
     * Sets the validator function for the headers (assuming it has headers).
     *
     * The validator function has the arguments
     *  $columns: an array of column names
     * The validator should return a string if there were validation errors and an empty string if not
     *
     * @param callable $validator
     * @return CsvInterface this instance
     */
    public function setHeaderValidator($validator) {
        $this->validateHeader = $validator;
        return $this;
    }

    public function getHeaderValidator() {
        if($this->validateHeader === null) {
            $this->setHeaderValidator(function($columns) { return ""; });
        }
        return $this->validateHeader;
    }

    public function getHeaderError() {
        return $this->headerError;
    }

    /**
     * Sets the validator function for the rows
     *
     * The validator function has the arguments
     *  $row: an associative array of data
     * The validator should return a string if there were validation errors and an empty string if not
     *
     * @param callable $validator
     * @return CsvInterface this instance
     */
    public function setValidator($validator) {
        $this->validate = $validator;
        return $this;
    }

    public function getValidator() {
        return $this->validate;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function setValidateRate($outOf) {
        $this->rate = $outOf;
        return $this;
    }


    private function normalizeArray($headers) {
        foreach($headers as &$header) {
            $header = $this->normalizeString($header);
        }
        return $headers;
    }

    private function normalizeString($str) {
        $words = explode(" ", $str);
        $nStr = "";
        $first = true;
        foreach($words as $word) {
            $word = strtolower($word);
            if($first === false) {
                $word = ucfirst($word);
            } else {
                $first = false;
            }
            $nStr .= $word;
        }
        return $nStr;
    }
}
