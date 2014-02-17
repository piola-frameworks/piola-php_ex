<?php

namespace mvc\entities
{
    abstract class DocenteEntity extends PersonaEntity
    {
        protected $_idDocente = 0;
        protected $_legajo = 0;
        
        public function __construct($id, $nombre, $apellido, $dni, $telefono = 0, $celular = 0, $email = "", $idDocente, $legajo)
        {
            parent::__construct($id, $nombre, $apellido, $dni, $telefono, $celular, $email);
            
            if(is_int($idDocente))
            {
                $this->_idDocente = $idDocente;
            }
            else
            {
                trigger_error("", E_USER_ERROR);
            }
            
            if(is_string($legajo) && strlen($legajo) <= 10)
            {
                $this->_legajo = $legajo;
            }
            else
            {
                trigger_error("", E_USER_ERROR);
            }
        }
    }
}

?>