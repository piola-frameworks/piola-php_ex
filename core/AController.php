<?php

namespace CEIT\core
{
    abstract class AController
    {
        protected $_model;
        protected $_view;
        protected $_template;
        protected $_dataCollection = array();
        protected $_ajaxRequest = false;

        public function __construct()
        {
            $this->_dataCollection['page_title'] = 'ApunTec';
            $this->_dataCollection['copyright'] = 'ApunTec - Sistema Integral de Gesti&oacute;n de Apuntes.';
        }
        
        public function __destruct()
        {
            ;
        }
        
        public function __get($name)
        {
            if(array_key_exists($name, $this->_dataCollection))
            {
                return $this->_dataCollection[$name];
            }
        }
        
        public function __set($name, $value)
        {
            $this->_dataCollection[$name] = $value;
        }
        
        public function __isset($name)
        {
            if(!is_null($name) && is_string($name))
            {
                return isset($this->_dataCollection[$name]);
            }
        }
        
        public function __unset($name)
        {
            if(!is_null($name) && is_string($name))
            {
                unset($this->_dataCollection[$name]);
            }
        }
        
        public function __toString()
        {
            $retValue = "";
            
            $retValue .= "Variables: " . PHP_EOL;
            foreach(get_class_vars(get_class($this)) as $key => $value)
            {
                $retValue .= "\t" . "Name: " . $key . "\t" . "Value: " . print_r($value) . PHP_EOL;
            }
            
            $retValue .= "Methods: " . PHP_EOL;
            foreach(get_class_methods($this) as $key => $value)
            {
                $retValue .= "\t" . "Value: " . $value . PHP_EOL;
            }
            
            return $retValue;
        }
    }
}