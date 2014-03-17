<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    class PedidoPosicionXModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delPedidoPosicionX");
                array_push($this->_params, array(
                        ':idPosicionX'   =>  (int)$item->_idPosicionX,
                ));
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
                array_push($this->_sp, "sp_insPedidoPosicionX");
                array_push($this->_params, array(
                        ':descripcion'  =>  (string)$item->_descripcion,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $this->_params = array(
                    ':idPosicionX'   =>  (int)$model->_idPosicionX,
                );

                return Database::getInstance()->DoQuery("sp_selPedidoPosicionX", $this->_params);
            }
            else
            {
                return Database::getInstance()->DoQuery("sp_selPedidosPosicionesX");
            }
        }

        /*
         * Start custom selects
         */
        
        public function SelectWithMarkByIdPedido(core\AModel $model)
        {
            $this->_params = array(
                ':idPedido'   =>  (int)$model->_idPedido,
            );

            return Database::getInstance()->DoQuery("sp_selPedidoPosicionesXByIdPedido", $this->_params);
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
                array_push($this->_sp, "sp_updPedidoPosicionX");
                array_push($this->_params, array(
                    ":idPosicionX"      =>  (int)$item->_idPosicionX,
                    ':descripcion'      =>  (string)$item->_descripcion,
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>