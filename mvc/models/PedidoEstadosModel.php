<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class PedidoEstadosModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "");
                array_push($this->_params, array(
                    
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
                array_push($this->_sp, "");
                array_push($this->_params, array(
                    
                ));
            }
            
            Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if(!empty($model))
            {
                $this->_sp = "sp_selPedidoEstado";
                $this->_params = array(
                    ':idEstado'  =>  $model->_idEstado,
                );
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selPedidoEstados";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }
        
        /*
         * Start Custom Selects
         */
        
        public function SelectByIdPedido(core\AModel $model)
        {
            $this->_sp = "sp_selPedidoEstadosByIdPedido";
            $this->_params = array(
                ':idPedido'  =>  $model->_idPedido,
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
                array_push($this->_sp, "");
                array_push($this->_params, array(
                    
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>