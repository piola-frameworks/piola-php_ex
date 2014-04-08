<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class UsuarioModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delUsuario");
                array_push($this->_params, array(
                    ':idUsuario'   =>  (int)$item->_idUsuario,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }

        public function Insert(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_insUsuario");
                array_push($this->_params, array(
                    ":idPersona"    =>  is_null($item->_idPersona) ? null : $model->_idPersona,
                    ":nombre"       =>  is_null($item->_nombre) ? null : (string)$model->_nombre,
                    ":apellido"     =>  is_null($item->_apellido) ? null : (string)$model->_apellido,
                    ":contrasena"   =>  is_null($item->_contrasena) ? null : (string)$model->_contrasena,
                    ":comentario"   =>  is_null($item->_comentario) ? null : (string)$model->_comentario,
                    ":email"        =>  (bool)$model->_email,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
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
            $this->_sp = "sp_selUsuarioByUsernameOrDNI";
            $this->_params = array(
                ':username'   =>  $model->_username,
            );

            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectByLegajoOrDNI(core\AModel $model)
        {
            $this->_sp = "sp_selUsuarioByLegajoOrDNI";
            $this->_params = array(
                ':legajoDni'    =>  (int)$model->_legajoDNI,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectByEmail(core\AModel $model)
        {
            $this->_sp = "sp_selUsuarioByEmail";
            $this->_params = array(
                ':username'   =>  $model->_email,
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
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_updUsuario");
                array_push($this->_params, array(
                    ":idUsuario"    =>  (int)$item->_idUsuario,
                    ":idPersona"    =>  is_null($item->_idPersona) ? null : (int)$item->_idPersona,
                    ":usuario"      =>  is_null($item->_usuario) ? null : (string)$item->_usuario,
                    ":contrasena"   =>  is_null($item->_contrasena) ? null : (string)$item->_contrasena,
                    ":comentario"   =>  is_null($item->_comentario) ? null : (string)$item->_comentario,
                    ":email"        =>  (bool)$item->_emailValidado,
                ));
            }

            //var_dump($this->_sp, $this->_params, $this->_trans);
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
        
        /*
         * Start custom updates
         */
        
        public function UpdateContrasena(core\AModel $model)
        {
            $this->init();
            
            array_push($this->_sp, "sp_updUsuarioContrasena");
            array_push($this->_params, array(
                ':idUsuario'    =>  $model->_idUsuario,
                ':contrasena'   =>  $model->_contrasena,
            ));
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params);
        }
        
        /*
         * End custom updates
         */
    }
}

?>