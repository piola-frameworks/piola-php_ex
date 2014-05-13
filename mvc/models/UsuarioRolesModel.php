<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class UsuarioRolesModel extends core\AModel
    {
        public function Delete(array $model)
        {
            $this->init();
            
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delUsuarioRol");
                array_push($this->_params, array(
                    ":idUsuario"    =>  (int)$item->_idUsuario,
                    ":idRol"        =>  (int)$item->_idRol,
                ));
            }
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params, $this->_trans);
        }

        public function Insert(array $model)
        {
            $this->init();
            
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_insUsuarioRol");
                array_push($this->_params, array(
                    ":idUsuario"    =>  (int)$item->_idUsuario,
                    ":idRol"        =>  (int)$item->_idRol,
                ));
            }

            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            $this->init();
            
            if(!empty($model))
            {
                $this->_sp = "sp_selUsuarioRoles";
                $this->_params = array(
                    ':id'   =>  (int)$model->_idUsuario,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selUsuariosRoles";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }

        /*
         * Start custom selects
         */
        
        
        
        /*
         * End custom selects
         */
        
        public function Update(array $model)
        {
            $this->init();
            
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_updUsuarioRol");
                array_push($this->_params, array(
                    ":idUsuario"    =>  (int)$item->_idUsuario,
                    ":idRol"        =>  (int)$item->_idRol,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>