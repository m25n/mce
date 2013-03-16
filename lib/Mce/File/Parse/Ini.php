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
 * Parses an ini file using php's building parsing function.
 *
 * @author Matthew Conger-Eldeen <mceldeen@gmail.com>
 */
class Ini {
    /**
     * Parses an ini file and returns the variables as an array
     * 
     * @param string $filename the name of the file
     * @return array the ini variables
     */
    public function parse($filename, $section = "production") {
        if(!file_exists($filename)) {
            require_once __DIR__ . "/../Exception/NotFound.php";
            throw new \Mce\File\Exception\NotFound("\"$filename\" does not appear to exist.");
        }
        
        if(!is_readable($filename)) {
            require_once __DIR__ . "/../Exception/NotReadable.php";
            throw new \Mce\File\Exception\NotReadable("\"$filename\" is not readable.");
        }
        
        $sections = parse_ini_file($filename, true);
        $sectionValues = $this->resolveValuesForSection($sections, $section);
        
        return $sectionValues;
    }
    
    private function resolveValuesForSection($sections, $section) {
        $section = strtolower($section);
        
        foreach($sections as $name => $values) {
            if(preg_match("/^{$section}/i", $name)) {
                $foundSection = true;
                $sectionValues = $this->splitKeys($values);
                
                // get parents for this section 
                $parents = explode(":", $name);
                array_shift($parents); // exclude the name of this section
                
                if(count($parents) > 0) { // if it has parents
                    $parentValues = array();
                    $parents = array_reverse($parents); // order so the deepest parent is first
                    foreach($parents as $parent) { // start with the deepest parent and progressively replace the values all the way to the closest parent
                        $parentValues = array_replace_recursive($parentValues, $this->resolveValuesForSection($sections, trim($parent)));
                    }
                    return array_replace_recursive($parentValues, $sectionValues); // make sure we overwrite the parent values with this section's values
                } else { // if it doesn't have parents just resolve these values
                    return $sectionValues;
                }
            }
        }

        require_once __DIR__ . "/Ini/Exception/BadSection.php";
        throw new \Mce\File\Parse\Ini\Exception\BadSection("Section \"" . $section . "\" could not be found.");
    }
    
    private function splitKeys($values) {
        $split = array();
        foreach($values as $key => $value) {
            $keyParts = explode(".", $key);
            $n = count($keyParts);
            $prevKey =& $split;
            for($i=0; $i<$n; $i++) {
                if($i == $n - 1) {
                    $prevKey[$keyParts[$i]] = $value;
                } elseif(!isset($prevKey[$keyParts[$i]])) {
                    $prevKey[$keyParts[$i]] = array();
                }
                $prevKey =& $prevKey[$keyParts[$i]];

            }
        }
        return $split;
    }
}
