<?php

namespace dn\models
{
    class UsuarioModel
    {
        protected $_idUsuario;
        protected $_usuario;
        protected $_contrasena;
        protected $_email;
        
        protected function getIdUsuario()
        {
            return $this->_idUsuario;
        }
        
        protected function setIdUsuario($idUsuario)
        {
            $this->_idUsuario = $idUsuario;
        }

        public function getUsuario()
        {
            return $this->_usuario;
        }
        
        protected function setUsuario($usuario)
        {
            $this->_usuario = $usuario;
        }
        
        public function getContrasena()
        {
            return $this->_contrasena;
        }
        
        public function setContrasena($contrasena)
        {
            $this->_contrasena = $contrasena;
        }
        
        public function getEmail()
        {
            return $this->_email;
        }
        
        public function setEmail($email)
        {
            $this->_email = $email;
        }
        
        public function __construct($idUsuario, $usuario, $contrasena)
        {
            $this->setIdUsuario($idUsuario);
            $this->setUsuario($usuario);
            $this->setContrasena($contrasena);
        }
    }
}

?>