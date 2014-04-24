<?php

namespace CEIT\mvc\models
{
    use \CEIT\core;
    use CEIT\core\CMySQLDatabase as Database;
    
    final class GabineteModel extends core\AModel
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
                array_push($this->_sp, "sp_delGabinetePedido");
                array_push($this->_params, array(
                    ':idGabinetePedido' =>  (int)$item->_idGabinetePedido,
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
                array_push($this->_sp, "sp_insGabinetePedido");
                array_push($this->_params, array(
                    ':idGabinetePedido' =>  (int)$item->_idGabinetePedido,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            $this->init();
            
            if($model != null)
            {
                $this->_sp = "sp_selGabinetePedido";
                $this->_params = array(
                    ':idPedido'   =>  (int)$model->_idGabinetePedido,
                );

                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_selGabinetePedidos";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }

        /*
         * Start custom selects
         */
        
        public function SelectItem(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selGabineteItems";
            $this->_params = array(
                ':idPedido'     => (int)$model->_idPedido,
            );
            
            //var_dump($this->_sp, $this->_params);
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectByIdEstado(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selGabinetePedidosByIdEstado";
            $this->_params = array(
                ':idEstado'     => (int)$model->_idEstado,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectEstado(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selGabinetePedidoEstado";
            $this->_params = array(
                ":idPedido" =>  (int)$model->_idPedido,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectCaja()
        {
            $this->init();
            
            $this->_sp = "sp_selGabineteCaja";
            
            return Database::getInstance()->DoQuery($this->_sp);
        }
        
        public function SelectCajaByIdPedidoOrDNI(core\AModel $model)
        {
            $this->init();
            
            $this->_sp = "sp_selGabineteCajaByIdPedidoOrDNI";
            $this->_params = array(
                ':id'   =>  $model->_id,
            );
            
            return Database::getInstance()->DoQuery($this->_sp, $this->_params);
        }
        
        public function SelectCajaItem(core\AModel $model)
        {
            $this->init();
                
            $this->_sp = "sp_selGabineteCajaPedido";
            $this->_params = array(
                ':idPedido' =>  (int)$model->_idGabinetePedido,
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
                array_push($this->_sp, "sp_updGabinetePedido");
                array_push($this->_params, array(
                    ":idGabinetePedido" =>  (int)$item->_idGabinetePedido,
                    ":idUsuario"        =>  (int)$item->_idUsuario,
                    ":creadoPor"        =>  (int)$item->_creadoPor,
                    ":creado"           =>  (string)$item->_creado,
                    ":modificadoPor"    =>  is_null($item) ? null : (int)$item->_modificadoPor,
                    ":modificado"       =>  is_null($item) ? null : (string)$item->_modificado,
                    ":anillado"         =>  (bool)$item->_anillado,
                    ":posicionX"        =>  is_null($item) ? null : (int)$item->_posicionX,
                    ":posicionY"        =>  is_null($item) ? null : (int)$item->_posicionY,
                    ":retiro"           =>  (string)$item->_retiro,
                    ":idFranja"         =>  (int)$item->_idFranja,
                    ":pagado"           =>  (bool)$item->_pagado,
                    ":idEstado"         =>  (int)$item->_idEstado,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>