<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class PermisoModel extends core\AModel
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
                    "sp_delPermiso"
                );
                
                array_push(
                    $this->_params,
                    array(
                        ':idPermiso'   =>  (int)$item->_idPermiso,
                    )
                );
            }
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params, $this->_trans);
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
                    "sp_insPermiso"
                );
                
                array_push(
                    $this->_params,
                    array(
                        ":descripcion"     =>  (int)$item->_descripcion
                    )
                );
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $this->_params = array(
                    ':idPermiso'   =>  (int)$model->_idPermiso,
                );

                return Database::getInstance()->DoQuery("sp_selPermiso", $this->_params);
            }
            else
            {
                return Database::getInstance()->DoQuery("sp_selPermisos");
            }
        }

        public function SelectAssignedRoles(core\AModel $model)
        {
            $this->_params = array(
                ':idPermiso'   =>  (int)$model->_idPermiso,
            );

            return Database::getInstance()->DoQuery("sp_selRolesByIdPermiso", $this->_params);
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
                    "sp_updPermiso"
                );
                
                array_push(
                    $this->_params,
                    array(
                        ":idPermiso"    =>  (int)$item->_idPermiso,
                        ":descripcion"  =>  (int)$item->_descripcion,
                    )
                );
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>