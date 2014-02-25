<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class PedidoModel extends core\AModel
    {
        public function Delete(array $model)
        {
            if(count($model) > 1)
            {
                $this->_trans = true;
            }
            
            foreach($model as $item)
            {
                array_push($this->_sp, "sp_delPedido");
                array_push($this->_params, array(
                    ':idPedido' =>  (int)$item->_idPedido,
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
                array_push($this->_sp, "sp_insPedido");
                
                array_push($this->_params, array(
                    ':idUsuario'        =>  (int)$item->_idUsuario,
                    ':creadoPor'        =>  (int)$item->_creadoPor,
                    ':creadoDia'        =>  (string)$item->_creadoDia,
                    ':modificadoPor'    =>  is_null($item->_modificadoPor) ? null : (int)$item->_modificadoPor,
                    ':modificadoDia'    =>  is_null($item->_modificadoDia) ? null : (string)$item->_creadoDia,
                    ':anillado'         =>  (bool)$item->_anillado,
                    ':comentario'       =>  is_null($item->_comentario) ? null : (string)$item->_comentario,
                    ':posicion'         =>  is_null($item->_posicion) ? null : (string)$item->_posicion,
                    ':retiro'           =>  (string)$item->_retiro,
                    ':idFranja'         =>  (int)$item->_idFranja,
                    ':pagado'           =>  (bool)$item->_pagado,
                    ':idEstado'         =>  (int)$item->_idEstado
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            if($model != null)
            {
                $params = array();
                $sp = null;

                if(array_key_exists("_idUsuario", $model->_data))
                {
                    $params = array(
                        ':id'   =>  (int)$model->_idUsuario,
                    );

                    $sp = "sp_selPedidoByIdUsuario";
                }
                else if(array_key_exists("_idPedido", $model->_data))
                {
                    $params = array(
                        ':id'   =>  (int)$model->_idPedido,
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

        /*
         * Start custom select
         */
        
        public function SelectByIdEstado(core\AModel $model)
        {
            if(array_key_exists("_idUsuario", $model->_data))
            {
                $this->_sp = "sp_selPedidosByIdUsuarioAndIdEstado";
                $this->_params = array(
                    ':idUsuario'    =>  (int)$model->_idUsuario,
                    ':idEstado'     =>  (int)$model->_idEstado,
                );
            }
            else
            {
                $this->_sp = "sp_selPedidosByIdEstado";
                $this->_params = array(
                    ':idEstado' =>  (int)$model->_idEstado,
                );
            }
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectByIdPedidoOrLegajo(core\AModel $model)
        {
            $this->_sp = "sp_selPedidosByIdPedidoOrLegajo";
            $this->_params = array(
                ':id'   =>  $model->_id,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectItem(core\AModel $model)
        {
            $this->_sp = "sp_selPedidoItems";
            $this->_params = array(
                ':id'   =>  $model->_idPedido,
            );

            return Database::getInstance()->DoQuery($this->_sp , $this->_params);   
        }
        
        public function SelectForDeliver(core\AModel $model = null)
        {
            if(!empty($model))
            {
                $this->_sp = "sp_selPedidoForDeliverByIdPedidoOrLegajo";
                $this->_params = array(
                    ':idPedidoOrLegajo'   =>  (string)$model->_id,
                );
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selPedidoForDeliver";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }
        
        public function SelectFinished()
        {
            $this->_sp = "sp_selPedidosTerminados";
            
            return Database::getInstance()->DoQuery($this->_sp);
        }
        
        public function SelectItemCaja(core\AModel $model)
        {
            $this->_sp = "sp_selPedidoCaja";
            $this->_params = array(
                ':idPedido' =>  $model->_idPedido,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectDisponibilidad()
        {
            $this->_sp = "sp_selPedidoDisponibilidad";
            
            return Database::getInstance()->DoQuery($this->_sp);
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
                array_push($this->_sp, "sp_updPedido");
                array_push($this->_params, array(
                    ':idPedido'         =>  (int)$item->_idPedido,
                    ':idUsuario'        =>  (int)$item->_idUsuario,
                    ':creado'           =>  (string)$item->_creado,
                    ':creadoPor'        =>  (int)$item->_creadoPor,
                    ':modificado'       =>  (string)$item->_modificado,
                    ':modificadoPor'    =>  (int)$item->_modificadoPor,
                    ':anillado'         =>  (bool)$item->_anillado,
                    ':comentario'       =>  is_null($item->_comentario) ? null : (string)$item->_comentario,
                    ':posicion'         =>  is_null($item->_posicion) ? null : (string)$item->_posicion,
                    ':retiro'           =>  (string)$item->_retiro,
                    ':idFranja'         =>  (int)$item->_idFranja,
                    ':pagado'           =>  (bool)$item->_pagado,
                    ':idEstado'         =>  (int)$item->_idEstado,
                ));
            }
            
            Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>