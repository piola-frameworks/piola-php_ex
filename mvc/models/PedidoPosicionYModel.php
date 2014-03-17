<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    class PedidoPosicionYModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delPedidoPosicionY");
                array_push($this->_params, array(
                        ':idPosicionY'   =>  (int)$item->_idPosicionY,
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
                array_push($this->_sp, "sp_insPedidoPosicionY");
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
                    ':idPosicionY'   =>  (int)$model->_idPosicionY,
                );

                return Database::getInstance()->DoQuery("sp_selPedidoPosicionY", $this->_params);
            }
            else
            {
                return Database::getInstance()->DoQuery("sp_selPedidosPosicionesY");
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

            return Database::getInstance()->DoQuery("sp_selPedidoPosicionesYByIdPedido", $this->_params);
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
                array_push($this->_sp, "sp_updPedidoPosicionY");
                array_push($this->_params, array(
                    ":idPosicionY"      =>  (int)$item->_idPosicionY,
                    ':descripcion'      =>  (string)$item->_descripcion,
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>