<?php

namespace mvc\entities
{
    final class UsuarioEntity extends PersonaEntity
    {
        private $_idUsuario = 0;
        private $_usuario;
        private $_contrasena;
        private $_comentario;
        private $_emailValidado = false;
        
        private $_roles = array();
        private $_permisos = array();
        
        public function __construct($id, $nombre, $apellido, $dni, $telefono = 0, $celular = 0, $email = "", $idUsuario, $usuario = "", $contraseña = "", $comentario = "", $emailValidado)
        {
            parent::__construct($id, $nombre, $apellido, $dni, $telefono, $celular, $email);
            
            if(is_int($idUsuario) && $idUsuario > 0)
            {
                $this->_idUsuario = $idUsuario;
            }
            else
            {
                trigger_error("", E_USER_ERROR);
            }
            
            if(is_bool($emailValidado))
            {
                $this->_emailValidado = $emailValidado;
            }
            else
            {
                trigger_error("", E_USER_ERROR);
            }
            
            if(isset($usuario))
            {
                if(is_string($usuario) && strlen($usuario) <= 20)
                {
                    $this->_usuario = $usuario;
                }
            }
            
            if(isset($contraseña))
            {
                if(is_string($contraseña) && strlen($contraseña) <= 20)
                {
                    $this->_contrasena = $contraseña;
                }
            }
            
            if(isset($comentario))
            {
                if(is_string($comentario) && strlen($comentario) <= 255)
                {
                    $this->_comentario = $comentario;
                }
            }
        }
        
        public function tieneRol($rol)
        {
            foreach($this->_roles as $rol)
            {
                if($rol->tienePermisos())
                {
                    
                }
            }
        }
        
        private function tienePermisos($permiso)
        {
            return isset($this->_permisos[$permiso]);
        }
    }
}

?>