<?php

namespace piola
{
    class StringBuilder
    {
        private $_string;
        
        public function __construct($string = "")
        {
            $this->_string = $string;
            
            if ($string === null) {
                $this->_string = "";
            }
        }
        
        public function Append($string)
        {
            $this->_string .= $string;
        }
        
        public function AppendLine($string)
        {
            $this->_string .= $string . '\n';
        }
        
        public function __toString()
        {
            return $this->_string;
        }
    }
}

?>