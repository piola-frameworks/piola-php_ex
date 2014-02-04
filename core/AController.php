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
            $this->_dataCollection['page_title'] = 'CEIT';
            //$this->_dataCollection['header_img_src'] = '/public/img/header.png';
            //$this->_dataCollection['header_img_alt'] = 'Header CEIT';
            $this->_dataCollection['copyright'] = 'CEIT - Centro de Estudiantes de Ingeniera Tecnologica';
        }
        
        public function __destruct()
        {
            ;           
        }
        
        public function __get($name)
        {
            try
            {
                if(array_key_exists($name, $this->_dataCollection))
                {
                    return $this->_dataCollection[$name];
                }
            }
            catch(Exception $ex)
            {
                echo $ex->getTraceAsString();
            }
        }
        
        public function __set($name, $value)
        {
            try
            {
                $this->_dataCollection[$name] = $value;
            }
            catch(Exception $ex)
            {
                echo $ex->getTraceAsString();
            }
        }
        
        public function __isset($name)
        {
            return isset($this->_dataCollection[$name]);
        }
        
        public function __unset($name)
        {
            unset($this->_dataCollection[$name]);
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