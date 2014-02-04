<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class RolPermisosModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push(
                    $this->_sp,
                    "sp_delRolPermiso"
                );
                
                array_push(
                    $this->_params,
                    array(
                        ':idRol'        =>  (int)$item->_idRol,
                        ':idPermiso'    =>  (int)$item->_idPermiso,
                    )
                );
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }

        public function DeleteByIdRol(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push(
                    $this->_sp,
                    "sp_delRolPermisoByIdRol"
                );
                
                array_push(
                    $this->_params,
                    array(
                        ':idRol'    =>  (int)$item->_idRol,
                    )
                );
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
        
        public function DeleteByIdPermiso(core\AModel $model)
        {
            $this->_params = array(
                ':idPermiso'    =>  $model->_idPermiso,
            );
            
            return Database::getInstance()->DoNonQuery("sp_delRolPermisoByIdPermiso", $this->_params);
        }
        
        public function Insert(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push(
                    $this->_sp,
                    "sp_insRolPermiso"
                );
                
                array_push(
                    $this->_params,
                    array(
                        ':idRol'        =>  (int)$item->_idRol,
                        ':idPermiso'    =>  (int)$item->_idPermiso,
                    )
                );
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $this->_sp = "sp_selRolPermiso";
                
                $this->_params = array(
                    ':idRol'        =>  (int)$model->_idRol,
                    ':idPermiso'    =>  (int)$model->_idPermiso,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selRolPermisos";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }

        public function Update(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push(
                    $this->_sp,
                    "sp_updRolPermiso"
                );
                
                array_push(
                    $this->_params,
                    array(
                        ':idRol'        =>  (int)$item->_idRol,
                        ':idPermiso'    =>  (int)$item->_idPermiso,
                    )
                );
            }

            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>