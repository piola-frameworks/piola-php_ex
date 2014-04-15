<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use CEIT\core\CMySQLDatabase as Database;
    
    class GabineteItemModel extends core\AModel
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
                array_push($this->_sp, "sp_delGabineteItem");
                array_push($this->_params, array(
                    ":idGabientePedido"         =>  (int)$item->_idPedido,
                    ":idGabinetePedidoItem"     =>  (int)$item->_idItem,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
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
                array_push($this->_sp, "sp_insGabineteItem");
                array_push($this->_params, array(
                    ":idGabientePedido"         =>  (int)$item->_idPedido,
                    ":idGabinetePedidoItem"     =>  (int)$item->_idItem,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            
        }
        
        public function Update(array $model)
        {
            $this->init();
            
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_updGabineteItem");
                array_push($this->_params, array(
                    ":idGabientePedido"         =>  (int)$item->_idPedido,
                    ":idGabinetePedidoItem"     =>  (int)$item->_idItem,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>