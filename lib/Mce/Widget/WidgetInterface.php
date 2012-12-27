<?php
namespace Mce\Widget;

interface WidgetInterface {
    public function __construct($options = array());
    
    public function render($type = "html");
}
