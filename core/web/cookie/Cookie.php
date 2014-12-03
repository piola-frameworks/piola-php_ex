<?php

namespace piola
{
    class Cookie implements ICookie
    {
        protected $_name;
        protected $_value;
        protected $_expire;
        protected $_path;
        protected $_domain;
        protected $_secure;
        protected $_httpOnly;

        public function getName()
        {
            return $this->_name;
        }

        protected function setName($name)
        {
            $this->_name = $name;
        }

        public function __construct($name, $value, $expire = 0, $path = "", $domain = "", $secure = false, $httpOnly = false)
        {
           $this->_name = $name;
           $this->_value = (string) $value;
           $this->_expire = $expire;
           $this->_path = $path;
           $this->_domain = $domain;
           $this->_secure = is_bool($secure) ? $secure : false;
           $this->_httpOnly = is_bool($httpOnly) ? $httpOnly : false;
        }

        public function activate()
        {
            return setcookie($this->_name, $this->_value, $this->_expire, $this->_path, $this->_domain, $this->_secure, $this->_httpOnly);
        }

        public function deactivate()
        {
            return setcookie($this->_name, "", time() - 10800);
        }

        public function status()
        {
            $var = filter_input(INPUT_COOKIE, $this->_name);
            return ($var === null || $var === false) ? false : true;
        }
    }
}

?>