<?php

namespace CEIT\core\wrappers
{
    final class CIntegerWrapper
    {
        private $_value = 0;
        private $_unsigned = false;
        
        public function __construct(int $value = 0, boolean $unsigned = false)
        {
            $this->_value = $value;
            $this->_unsigned = $unsigned;
        }
    }
}

?>