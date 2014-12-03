<?php

namespace piola {

    class CookieManager
    {

        private $_cookies;

        public function __construct()
        {
            $this->_cookies = array();
        }

        public function exists($name)
        {
            foreach ($this->_cookies as $cookie)
            {
                if ($name == $cookie->_name)
                {
                    return true;
                }
            }
            return false;
        }

        public function add(Cookie $cookie)
        {
            if ($this->exists($cookie->getName()))
            {
                throw new Exception("Ya existe la galletita que quiere agregar.");
            }
            $this->_cookies[] = $cookie;
        }

        public function remove($name)
        {
            if (!$this->exists($name))
            {
                throw new Exception("No existe la galletita que quiere borrar.");
            }

            $this->_cookies = array_filter($this->_cookies, function($item) use ($name) {
                return strcmp($item->_name, $name) == 0;
            });
        }

        public function activateAll()
        {
            foreach ($this->_cookies as $cookie)
            {
                if (!$cookie->status())
                {
                    $cookie->activate();
                }
            }
        }
        
        public function activate($name = null)
        {
            if (!$this->exists($name))
            {
                throw new Exception("No existe la galletita que quiere activar.");
            }
            
            $index = $this->getCookieIndex($name);
            if ($index > -1)
            {
                if (!$this->_cookies[$index]->status())
                {
                    $this->_cookies[$index]->activate();
                }
            }
        }

        public function deactivateAll()
        {
            foreach ($this->_cookies as $cookie)
            {
                $this->deactivate($cookie->_name);
            }
        }
        
        public function deactivate($name)
        {
            if (!$this->exists($name))
            {
                throw new Exception("No existe la galletita que quiere desactivar.");
            }
            
            $index = $this->getCookieIndex($name);
            if ($index > -1)
            {
                if ($this->_cookies[$index]->status())
                {
                    $this->_cookies[$index]->deactivate();
                }
            }
        }

        public function getCookie($name)
        {
            if ($this->exists($name))
            {
                $index = $this->getCookieIndex($name);
                if (-1 < $index)
                {
                    return $this->_cookies[$index];
                }
            }
            return null;
        }

        private function getCookieIndex($name)
        {
            if ($this->exists($name))
            {
                $counter = 0;
                foreach ($this->_cookies as $cookie)
                {
                    if (strcmp($cookie->_name, $name) == 0)
                    {
                        return $counter;
                    }
                    $counter++;
                }
            }
            return -1;
        }
    }
}

?>