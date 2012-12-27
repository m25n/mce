<?php
namespace Mce\Widget;

abstract class WidgetAbstract implements WidgetInterface {
    protected $viewDir;
    protected $viewVariables;
    
    public function __construct($options = array()) {
        $this->setOptions($options);
    }
    
    protected function setOptions($options) {
        foreach($options as $key => $value) {
            $setMethod = "set" . ucfirst($key);
            if(method_exists($this, $setMethod)) {
                $this->$setMethod($value);
            } else {
                throw new \InvalidArgumentException("Option '{$key}' does not exist");
            }
        }
    }
    
    protected function renderView($type) {
        $filename = $this->getViewDir() . "/{$type}.php";
        
        if(!file_exists($filename)) {
            throw new \Mce\File\Exception\NotFound("'{$filename}' cannot be found");
        }
        
        if(!is_readable($filename)) {
            throw new \Mce\File\Exception\NotFound("'{$filename}' is not readable");
        }
        
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    
    public function __get($key) {
        if(!isset($this->viewVariables[$key])) {
            throw new \InvalidArgumentException("Variable '{$key}' does not exist");
        }
        
        return $this->viewVariables[$key];
    }
    
    public function __set($key, $value) {
        $this->viewVariables[$key] = $value;
    }
    
    
    public function getViewDir() {
        return $this->viewDir;
    }
    
    protected function setViewDir($viewDir) {
        $this->viewDir = $viewDir;
    }
    
}
