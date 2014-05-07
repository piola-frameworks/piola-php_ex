<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    class PedidoItemModel extends core\AModel
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
                array_push($this->_sp, "sp_delPedidoItem");
                array_push($this->_params, array(
                    ':idItem'  =>  (int)$item->_idItem,
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
                array_push($this->_sp, "sp_insPedidoItem");
                array_push($this->_params, array(
                    ':idPedido'     =>  (int)$item->_idPedido,
                    ':cantidad'     =>  (int)$item->_cantidad,
                    ':idTexto'      =>  (int)$item->_idTexto,
                    ':anillado'     =>  (bool)$item->_anillado,
                    ':abrochado'    =>  (bool)$item->_abrochado,
                    ':simpleFaz'    =>  (bool)$item->_simpleFaz,
                    ':idEstado'     =>  (int)$item->_idEstado,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            $this->init();
            
            if(empty($model))
            {
                // No deberia entrar aca por que no se puede obtener directamente todos los items de todos los pedidos.
                trigger_error("No se puede obtener directamente todos los items de los pedidos.", E_USER_ERROR);
            }
            else
            {
                $this->_sp = "sp_selPedidoItems";
                $this->_params = array(
                    ':idPedido' =>  (int)$model->_idPedido,
                );
                
                //var_dump($this->_sp, $this->_params);
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
        }

        /*
         * Start custom selects
         */
        
        public function SelectEstadosAndMarkByIdPedidoItem(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selPedidoItemEstadoListAndSelected";
            $this->_params = array(
                ':idItem' =>  (int)$model->_idItem
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
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
                array_push($this->_sp, "sp_updPedidoItem");
                array_push($this->_params, array(
                    ':idItem'       =>  (int)$item->_idItem,
                    ':cantidad'     =>  (int)$item->_cantidad,
                    ':idTexto'      =>  (int)$item->_idTexto,
                    ':anillado'     =>  (bool)$item->_anillado,
                    ':abrochado'    =>  (bool)$item->_abrochado,
                    ':simpleFaz'    =>  (bool)$item->_simpleFaz,
                    ':idEstado'     =>  (int)$item->_idEstado,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
        
        /*
         * Start custom updates
         */
        
        public function UpdateEstado(array $model)
        {
            $this->init();
            
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_updPedidoItemEstado");
                array_push($this->_params, array(
                    ':idItem'   =>  (int)$item->_idItem,
                    ':idEstado' =>  (int)$item->_idEstado,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params);
        }
        
        /*
         * End custom updates
         */
    }
}

?>