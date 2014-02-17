<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class UsuarioModel extends core\AModel
    {
        public function Delete(array $model)
        {
            $this->_sp = "sp_delUsuarios";
            $this->_params = array(
                ':id'   =>  $model->_idUsuario,
            );

            Database::getInstance()->DoNonQuery($this->_sp, $this->_params);
        }

        public function Insert(array $model)
        {
            $this->_sp = "sp_insUsuario";
            $this->_params = array(
                ":idAlumno"     =>  $model->_idAlumno,
                ":nombre"       =>  $model->_nombre,
                ":apellido"     =>  $model->_apellido,
                ":contrasena"   =>  $model->_contrasena,
                ":comentario"   =>  $model->_comentario,
                ":privilegio"   =>  $model->_privilegio,
            );

            Database::getInstance()->DoScalar($this->_sp, $this->_params);
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $this->_sp = "sp_selUsuario";
                $this->_params = array(
                    ':idUsuario'   =>  $model->_idUsuario,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selUsuarios";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }
        
        /*
         * Start custom selects
         */
        
        public function SelectByUsername(core\AModel $model)
        {
            $this->_sp = "sp_selUsuarioByLegajoOrUsername";
            $this->_params = array(
                ':username'   =>  $model->_username,
            );

            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectRoles(core\AModel $model)
        {
            $this->_sp = "sp_selUsuarioRoles";
            $this->_params = array(
                ':idUsuario'    =>  $model->_idUsuario,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectPermissionByIdUsuario(core\AModel $model)
        {
            $this->_sp = "sp_selUsuarioPermisosByIdUsuario";
            $this->_params = array(
                ':idUsuario'    =>  $model->_idUsuario,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectPermissionByIdRol(core\AModel $model)
        {
            $this->_sp = "sp_selUsuarioPermisosByIdRol";
            $this->_params = array(
                ':idRol'  =>  $model->_idRol,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectAllRolesAndMarkByUser(core\AModel $model)
        {
            $this->_sp = "sp_selUsuariosRoles2";
            $this->_params = array(
                ':idUsuario'    =>  $model->_idUsuario,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        /*
         * End custom selects
         */
        
        public function Update(array $model)
        {
            $this->_sp = "sp_updUsuario";
            $this->_params = array(
                ":idUsuario"    =>  $model->_idUsuario,
                ":idAlumno"     =>  $model->_idAlumno,
                ":nombre"       =>  $model->_nombre,
                ":apellido"     =>  $model->_apellido,
                ":contrasena"   =>  $model->_contrasena,
                ":comentario"   =>  $model->_comentario,
                ":privilegio"   =>  $model->_privilegio,
            );

            Database::getInstance()->DoNonQuery($this->_sp, $this->_params);
        }
    }
}

?>