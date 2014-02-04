<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class RolModel extends core\AModel
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
                    "sp_delRol"
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
                    "sp_insRol"
                );
                
                array_push(
                    $this->_params, 
                    array(
                        ':descripcion'  =>  (string)$item->_descripcion,
                    )
                );
            }

            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $this->_sp = "sp_selRol";
                $this->_params = array(
                    ':idRol'   =>  (int)$model->_idRol,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selRoles";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }
        
        public function SelectAssignedPermissions(core\AModel $model)
        {
            $this->_sp = "sp_selPermisosByIdRol";
            $this->_params = array(
                ':idRol'   =>  (int)$model->_idRol,
            );

            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
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
                    "sp_updRol"
                );
                
                array_push(
                    $this->_params,
                    array(
                        ':idRol'        =>  (int)   $item->_idRol,
                        ':descripcion'  =>  (string)$item->_descripcion,
                    )
                );
            }

            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>