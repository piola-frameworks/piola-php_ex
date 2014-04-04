<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class PedidoModel extends core\AModel
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
                array_push($this->_sp, "sp_delPedido");
                array_push($this->_params, array(
                    ':idPedido' =>  (int)$item->_idPedido,
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
                array_push($this->_sp, "sp_insPedido");
                array_push($this->_params, array(
                    ':idUsuario'        =>  (int)$item->_idUsuario,
                    ':creadoPor'        =>  (int)$item->_creadoPor,
                    ':creadoDia'        =>  (string)$item->_creadoDia,
                    ':modificadoPor'    =>  is_null($item->_modificadoPor) ? null : (int)$item->_modificadoPor,
                    ':modificadoDia'    =>  is_null($item->_modificadoDia) ? null : (string)$item->_creadoDia,
                    ':anillado'         =>  (bool)$item->_anillado,
                    ':comentario'       =>  is_null($item->_comentario) ? null : (string)$item->_comentario,
                    ':posicionX'        =>  is_null($item->_posicionX) ? null : (int)$item->_posicionX,
                    ':posicionY'        =>  is_null($item->_posicionY) ? null : (int)$item->_posicionY,
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
            $this->init();
            
            if($model != null)
            {
                if(array_key_exists("_idUsuario", $model->_data))
                {
                    $this->_sp = "sp_selPedidosByIdUsuario";
                    $this->_params = array(
                        ':id'   =>  (int)$model->_idUsuario,
                    );
                }
                else if(array_key_exists("_idPedido", $model->_data))
                {
                    $this->_sp = "sp_selPedidoByIdPedido";
                    $this->_params = array(
                        ':id'   =>  (int)$model->_idPedido,
                    );
                }

                if(!empty($this->_params) || !empty($this->_sp))
                {
                    //var_dump($this->_sp, $this->_params);
                    
                    return Database::getInstance()->DoQuery($this->_sp , $this->_params);
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
            $this->init();
            
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
        
        public function SelectByIdPedidoOrDNI(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selPedidosByIdPedidoOrDNI";
            $this->_params = array(
                ':id'   =>  $model->_id,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectItem(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selPedidoItems";
            $this->_params = array(
                ':id'   =>  $model->_idPedido,
            );

            return Database::getInstance()->DoQuery($this->_sp , $this->_params);   
        }
        
        public function SelectForDeliver()
        {
            $this->init();
            
            $this->_sp = "sp_selPedidoForDeliver";
                
            return Database::getInstance()->DoQuery($this->_sp);
        }
        
        public function SelectForDeliverByIdPedidoOrLegajo(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selPedidoForDeliverByIdPedidoOrLegajo";
            $this->_params = array(
                ':idPedidoOrLegajo'   =>  (string)$model->_idPedidoLegajo,
            );

            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectFinished()
        {
            $this->init();
            
            $this->_sp = "sp_selPedidosTerminados";
            
            return Database::getInstance()->DoQuery($this->_sp);
        }
        
        public function SelectItemCaja(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selPedidoCaja";
            $this->_params = array(
                ':idPedido' =>  $model->_idPedido,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectDisponibilidad()
        {
            $this->init();
            
            $this->_sp = "sp_selPedidoDisponibilidad";
            
            return Database::getInstance()->DoQuery($this->_sp);
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
                    ':posicionX'        =>  is_null($item->_posicionX) ? null : (int)$item->_posicionX,
                    ':posicionY'        =>  is_null($item->_posicionY) ? null : (int)$item->_posicionY,
                    ':retiro'           =>  (string)$item->_retiro,
                    ':idFranja'         =>  (int)$item->_idFranja,
                    ':pagado'           =>  (bool)$item->_pagado,
                    ':idEstado'         =>  (int)$item->_idEstado,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
        
        /*
         * Start custom Updates
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
                array_push($this->_sp, "sp_updPedidoEstado");
                array_push($this->_params, array(
                    ':idPedido'         =>  (int)$item->_idPedido,
                    ':idEstado'         =>  (int)$item->_idEstado,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
        
        /*
         * End custom Updates
         */
    }
}

?>