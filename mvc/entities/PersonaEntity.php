<?php

namespace mvc\entities
{
    abstract class PersonaEntity
    {
        protected $_idPersona = 0;
        protected $_nombre = "";
        protected $_apellido = "";
        protected $_dni = 0;
        protected $_telefono;
        protected $_celular;
        protected $_email;
        
        public function __construct($id, $nombre, $apellido, $dni, $telefono = 0, $celular = 0, $email = "")
        {
            if(is_int($id) && $id > 1)
            {
                $this->_idPersona = $id;
            }
            else
            {
                trigger_error("El identificador de la persona tiene que ser un numero natural.", E_USER_ERROR);
            }
            
            if(is_string($nombre) && strlen($nombre) <= 50)
            {
                $this->_nombre = $nombre;
            }
            else
            {
                trigger_error("El nombre de la persona tiene que ser una cadena de caracteres y de largo menor o igual a 50.", E_USER_ERROR);
            }
            
            if(is_string($apellido) && strlen($apellido) <= 50)
            {
                $this->_apellido = $apellido;
            }
            else
            {
                trigger_error("El apellido de la peronsa tiene que ser una cadena de caracteres y de largo menor o igual a 50.", E_USER_ERROR);
            }
            
            if(is_int($dni) && $dni >= 99999999)
            {
                $this->_dni = $dni;
            }
            else
            {
                trigger_error("", E_USER_ERROR);
            }
            
            if(isset($telefono))
            {
                if(is_int($telefono))
                {
                    $this->_telefono = $telefono;
                }
            }
            
            if(isset($celular))
            {
                if(is_int($celular))
                {
                    $this->_celular = $celular;
                }
            }
            
            if(isset($email))
            {
                if(is_string($email) && strlen($email) <= 100)
                {
                    $this->_email = $email;
                }
            }
        }
    }
}

?>