<?php

namespace CEIT\core
{
    use \CEIT\core;

    abstract class AModel implements core\ICommands
    {
        protected $_sp = array();
        protected $_params = array();
        protected $_trans = false;
        protected $_data = array();

        public function __get($name)
        {
            if(array_key_exists($name, $this->_data))
            {
                return $this->_data[$name];
            }
        }
        
        public function __set($name, $value)
        {
            $this->_data[$name] = $value;
        }
        
        public function __isset($name)
        {
            return isset($this->_data[$name]);
        }
        
        public function __unset($name)
        {
            unset($this->_data[$name]);
        }
        
        public function init()
        {
            $this->_data = array();
            $this->_params = array();
            $this->_sp = array();
            $this->_trans = false;
        }
    }
}