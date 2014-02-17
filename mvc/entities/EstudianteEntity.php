<?php

namespace mvc\entities
{
    abstract class EstudianteEntity extends PersonaEntity
    {
        protected $_idEstudiante = 0;
        protected $_legajo = 0;
        protected $_idCarrera;
        
        public function __construct($id, $nombre, $apellido, $dni, $telefono = 0, $celular = 0, $email = "", $idEstudiante, $legajo, $carrera = 0)
        {
            parent::__construct($id, $nombre, $apellido, $dni, $telefono, $celular, $email);
            
            if(is_int($idEstudiante))
            {
                $this->_idEstudiante = $idEstudiante;
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
            
            if(isset($carrera))
            {
                if(is_int($carrera))
                {
                    $this->_idCarrera = $carrera;
                }
            }
        }
    }
}

?>