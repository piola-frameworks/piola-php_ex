<?php

namespace mvc\entities
{
    final class WebEntity
    {
        private $_clave;
        private $_valor = 0;
        
        public function __construct($clave, $valor = 0)
        {
            if(is_string($clave) && (strlen($clave) > 0 && strlen($clave) <= 50))
            {
                $this->_clave = $clave;
            }
            else
            {
                trigger_error("", E_USER_ERROR);
            }
            
            if(is_float($valor) && $valor >= 0)
            {
                $this->_valor = $valor;
            }
            else
            {
                trigger_error("", E_USER_ERROR);
            }
        }
    }
}

?>