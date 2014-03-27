<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use CEIT\core\CMySQLDatabase as Database;
    
    final class GabineteModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delGabinetePedido");
                array_push($this->_params, array(
                    ':idGabinetePedido' =>  (int)$item->_idGabinetePedido,
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
                array_push($this->_sp, "sp_insGabinetePedido");
                array_push($this->_params, array(
                    ':idGabinetePedido' =>  (int)$item->_idGabinetePedido,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $this->_sp = "sp_selGabinetePedido";
                $this->_params = array(
                    ':idPedido'   =>  (int)$model->_idGabinetePedido,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selGabinetePedidos";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }

        /*
         * Start custom selects
         */
        
        public function SelectCaja()
        {
            $this->_sp = "sp_selGabineteCaja";
            
            return Database::getInstance()->DoQuery($this->_sp);
        }
        
        public function SelectCajaItem(core\AModel $model)
        {
            $this->_sp = "sp_selGabineteCajaPedido";
            $this->_params = array(
                ':idPedido' =>  (int)$model->_idPedido,
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
                array_push($this->_sp, "sp_updGabinetePedido");
                
                array_push($this->_params, array(
                    ':idGabinetePedido' =>  (int)$item->_idGabinetePedido,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>