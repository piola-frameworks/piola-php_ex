<?php
namespace CEIT\mvc\models
{
    use \CEIT\core;
    use \CEIT\core\CMySQLDatabase as Database;
    
    final class CajaModel extends core\AModel
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
                array_push($this->_sp, "sp_delCaja");
                array_push($this->_params, array(
                    ':idCaja'  =>  (int)$item->_idCaja,
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
                array_push($this->_sp, "sp_insCaja");
                array_push($this->_params, array(
                    ':creadoPor'    =>  (int)$item->_creadoPor,
                    ':creado'       =>  (string)$item->_creado,
                    ':subTotal'     =>  (float)$item->_subTotal,
                    ':pago'         =>  (float)$item->_pago,
                    ':vuelto'       =>  (float)$item->_vuelto,
                    ':gabinete'     =>  (bool)$item->_gabinete,
                ));
            }
            
            return Database::getInstance()->DoScalar($this->_sp, $this->_params, $this->_trans);
        }

        public function Select(core\AModel $model = null)
        {
            $this->init();
            
            if(!empty($model))
            {
                $this->_sp = "sp_sel";
                $this->_params = array(
                    ':idCaja'   =>  (int)$model->_idCaja,
                );
                
                return Database::getInstance()->DoQuery($this->_sp, $this->_params);
            }
            else
            {
                $this->_sp = "sp_sel";
                
                return Database::getInstance()->DoQuery($this->_sp);
            }
        }

        /*
         * Start Custom Select
         */
        
        public function SelectCierraCaja()
        {
            $this->init();
            
            $this->_sp = "sp_selCajaCierreParcial";
            
            return Database::getInstance()->DoQuery($this->_sp);
        }
        
        /*
         * End custom Select
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
                array_push($this->_sp, "sp_updCaja");
                array_push($this->_params, array(
                    ':idCaja'       =>  (int)$item->_idCaja,
                    ':creadoPor'    =>  (int)$item->_creadoPor,
                    ':creado'       =>  (string)$item->_creado,
                    ':subTotal'     =>  (float)$item->_subTotal,
                    ':pago'         =>  (float)$item->_pago,
                    ':vuelto'       =>  (float)$item->_vuelto,
                    ':gabinete'     =>  (bool)$item->_gabinete,
                ));
            }
            
            return Database::getInstance()->DoNonQuery($this->_sp, $this->_params, $this->_trans);
        }
    }
}

?>