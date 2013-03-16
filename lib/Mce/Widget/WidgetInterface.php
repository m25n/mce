<?php

/*
 * This file is part of the Mce package.
 *
 * (c) Matthew Conger-Eldeen <mceldeen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mce\Widget;

interface WidgetInterface {
    public function __construct($options = array());
    
    public function render($type = "html");
}
