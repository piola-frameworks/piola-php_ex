<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    class PedidoItemModel extends core\AModel
    {
        public function Delete(array $model)
        {
            
        }

        public function Insert(array $model)
        {
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
                    ':simpleFaz'    =>  (bool)$item->_simpleFaz,
                    ':idEstado'     =>  (int)$item->_idEstado,
                ));
            }
            
            var_dump($this->_sp, $this->_params, $this->_trans);
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if(empty($model))
            {
                // No deberia entrar aca por que no se puede obtener directamente todos los items de todos los pedidos.
                trigger_error("No se puede obtener directamente todos los items de los pedidos.", E_USER_ERROR);
            }
            else
            {
                $this->_sp = "sp_selPedidoItems";
                $this->_params = array(
                    ':idPedido' => $this->_idPedido,
                );
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
        }

        public function Update(array $model)
        {
            
        }

    }
}

?>