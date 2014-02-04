<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    class PedidoItemEstadosModel extends core\AModel
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
                    "sp_delPedidoItemEstado"
                );
                
                array_push(
                    $this->_params,
                    array(
                        ':idEstadoItem' =>  $item->_idEstadoItem,
                    )
                );
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
                array_push(
                    $this->_sp,
                    "sp_insPedidoItemEstado"
                );
                
                array_push(
                    $this->_params,
                    array(
                        ':descripcion' =>  $item->_descripcion,
                    )
                );
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if(empty($model))
            {
                $this->_sp = "sp_selPedidoItemEstados";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
            else
            {
                $this->_sp = "sp_selPedidoItemEstado";
                $this->_params = array(
                    ':idEstadoItem' =>  $model->_idEstadoItem,
                );
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
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
                array_push(
                    $this->_sp,
                    "sp_updPedidoItemEstado"
                );
                
                array_push(
                    $this->_params,
                    array(
                        ':idEstadoItem' =>  $item->_idEstadoItem,
                        ':descripcion'  =>  $item->_descripcion,
                    )
                );
            }
            
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>