<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    class CajaItemModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_");
                array_push($this->_params, array(
                    ''  =>  $item,
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }

        public function Insert(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_insCajaItem");
                array_push($this->_params, array(
                    ':idCaja'       =>  (int)$item->_idCaja,
                    ':idPedido'     =>  is_null($item->_idPedido) ? null : (int)$item->_idPedido,
                    ':idItem'       =>  is_null($item->_idItem) ? null : (string)$item->_idItem,
                    ':desciprion'   =>  is_null($item->_descripcion) ? null : (string)$item->_descripcion,
                    ':preunit'      =>  (float)$item->_precioUnitario,
                    ':cantidad'     =>  (int)$item->_cantidad,
                ));
            }
            
            Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if(!empty($model))
            {
                $this->_sp = "sp_selCajaItem";
                $this->_params = array(
                    ':idItem'  =>  $model->_idItem,
                );
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selCajaItems";
                
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
                array_push($this->_sp, "sp_");
                array_push($this->_params, array(
                    ''  =>  $item,
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>