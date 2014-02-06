<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class PedidoModel extends core\AModel
    {
        public function Delete(array $model)
        {
            
        }

        public function Insert(array $model)
        {
            
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $params = array();
                $sp = null;

                if(array_key_exists("idUsuario", $model->_data))
                {
                    $params = array(
                        ':id'   =>  $model->_data['idUsuario'],
                    );

                    $sp = "sp_selPedidoByIdUsuario";
                }
                else if(array_key_exists("idPedido", $model->_data))
                {
                    $params = array(
                        ':id'   =>  $model->_data['idPedido'],
                    );

                    $sp = "sp_selPedidoByIdPedido";
                }

                if(!empty($params) || !empty($sp))
                {
                    return Database::getInstance()->DoQuery($sp , $params);
                }
                else
                {
                    return Database::getInstance()->DoQuery("sp_selPedidos");
                }
            }
            else
            {
                return Database::getInstance()->DoQuery("sp_selPedidos");
            }
        }

        public function SelectItem(core\AModel $model)
        {
            $params = array(
                ':id'   =>  $model->_idPedido,
            );

            return Database::getInstance()->DoQuery("sp_selPedidoItems" , $params);
            
        }
        
        public function SelectDisponibilidad()
        {
            $this->_sp = "sp_selPedidoDisponibilidad";
            
            return Database::getInstance()->DoQuery($this->_sp);
        }
        
        public function Update(array $model)
        {
            
        }
    }
}

?>